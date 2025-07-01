<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cours;
use App\Models\CoursHoraire;
use App\Models\SessionCours;
use App\Models\Ecole;
use App\Http\Requests\Admin\CoursRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CoursController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware(['auth', 'verified']),
            new Middleware('can:view-cours', only: ['index', 'show']),
            new Middleware('can:create-cours', only: ['create', 'store']),
            new Middleware('can:update-cours', only: ['edit', 'update']),
            new Middleware('can:delete-cours', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of cours for the authenticated user's school.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Multi-tenant strict filtering
        $query = Cours::with(['ecole'])
            ->when(!$user->hasRole('super_admin'), function ($query) use ($user) {
                return $query->where('ecole_id', $user->ecole_id);
            })
            ->withCount(['coursHoraires'])
            ->orderBy('nom');

        // Filtres de recherche
        if (request('search')) {
            $query->where('nom', 'like', '%' . request('search') . '%');
        }

        if (request('niveau')) {
            $query->where('niveau', request('niveau'));
        }

        if (request('actif') !== null) {
            $query->where('active', request('actif'));
        }

        $cours = $query->paginate(15)->withQueryString();
        
        // Stats for dashboard
        $stats = [
            'total' => Cours::when(!$user->hasRole('super_admin'), function ($query) use ($user) {
                return $query->where('ecole_id', $user->ecole_id);
            })->count(),
            'actifs' => Cours::when(!$user->hasRole('super_admin'), function ($query) use ($user) {
                return $query->where('ecole_id', $user->ecole_id);
            })->where('active', true)->count(),
            'avec_horaires' => Cours::when(!$user->hasRole('super_admin'), function ($query) use ($user) {
                return $query->where('ecole_id', $user->ecole_id);
            })->whereHas('coursHoraires')->count()
        ];

        // Filtres pour la vue
        $niveaux = Cours::when(!$user->hasRole('super_admin'), function ($query) use ($user) {
            return $query->where('ecole_id', $user->ecole_id);
        })->distinct()->pluck('niveau')->filter()->sort();

        return view('admin.cours.index', compact('cours', 'stats', 'niveaux'));
    }

    /**
     * Show the form for creating a new cours.
     */
    public function create()
    {
        $ecoles = $this->getEcolesForUser();
        $sessions = $this->getSessionsActives();
        
        return view('admin.cours.create', compact('ecoles', 'sessions'));
    }

    /**
     * Store a newly created cours.
     */
    public function store(CoursRequest $request)
    {
        $data = $request->validated();
        
        // Force ecole_id for non-super-admin users
        if (!Auth::user()->hasRole('super_admin')) {
            $data['ecole_id'] = Auth::user()->ecole_id;
        }

        $cours = Cours::create($data);

        // Créer les horaires si fournis
        if ($request->filled('horaires')) {
            $this->creerHoraires($cours, $request->horaires);
        }

        return redirect()
            ->route('admin.cours.index')
            ->with('success', 'Cours créé avec succès.');
    }

    /**
     * Display the specified cours.
     */
    public function show(Cours $cour)
    {
        Gate::authorize('view', $cour);
        
        // Load related data
        $cour->load([
            'ecole', 
            'coursHoraires.session',
            'inscriptionsCours'
        ]);

        // Stats du cours
        $stats = [
            'total_horaires' => $cour->coursHoraires->count(),
            'sessions_differentes' => $cour->coursHoraires->pluck('session_id')->unique()->count(),
            'inscriptions_actives' => $cour->inscriptionsCours->where('statut', 'active')->count(),
            'revenus_total' => $cour->coursHoraires->sum('prix')
        ];

        // Horaires groupés par session
        $horairesParsession = $cour->coursHoraires->groupBy('session.nom');

        return view('admin.cours.show', compact('cour', 'stats', 'horairesParsession'));
    }

    /**
     * Show the form for editing the specified cours.
     */
    public function edit(Cours $cour)
    {
        Gate::authorize('update', $cour);
        
        $ecoles = $this->getEcolesForUser();
        $sessions = $this->getSessionsActives();
        
        return view('admin.cours.edit', compact('cour', 'ecoles', 'sessions'));
    }

    /**
     * Update the specified cours.
     */
    public function update(CoursRequest $request, Cours $cour)
    {
        Gate::authorize('update', $cour);
        
        $data = $request->validated();
        
        // Prevent ecole_id changes for non-super-admin
        if (!Auth::user()->hasRole('super_admin')) {
            unset($data['ecole_id']);
        }

        $cour->update($data);

        return redirect()
            ->route('admin.cours.show', $cour)
            ->with('success', 'Cours mis à jour avec succès.');
    }

    /**
     * Remove the specified cours.
     */
    public function destroy(Cours $cour)
    {
        Gate::authorize('delete', $cour);
        
        // Check if cours has related horaires or inscriptions
        if ($cour->coursHoraires()->count() > 0) {
            return redirect()
                ->route('admin.cours.index')
                ->with('error', 'Impossible de supprimer un cours avec des horaires associés.');
        }

        if ($cour->inscriptionsCours()->count() > 0) {
            return redirect()
                ->route('admin.cours.index')
                ->with('error', 'Impossible de supprimer un cours avec des inscriptions.');
        }

        $cour->delete();

        return redirect()
            ->route('admin.cours.index')
            ->with('success', 'Cours supprimé avec succès.');
    }

    /**
     * Show horaires management for a cours.
     */
    public function horaires(Cours $cour)
    {
        Gate::authorize('view', $cour);
        
        $cour->load(['coursHoraires.session']);
        $sessions = $this->getSessionsActives();
        
        return view('admin.cours.horaires', compact('cour', 'sessions'));
    }

    /**
     * Add horaire to cours.
     */
    public function ajouterHoraire(Request $request, Cours $cour)
    {
        Gate::authorize('update', $cour);
        
        $request->validate([
            'session_id' => 'required|exists:sessions_cours,id',
            'jour_semaine' => 'required|string',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
            'salle' => 'nullable|string|max:255',
            'instructeur_affecte' => 'nullable|string|max:255',
            'capacite_max' => 'nullable|integer|min:1',
            'prix' => 'nullable|numeric|min:0'
        ]);

        // Vérifier que session appartient à même école
        $session = SessionCours::findOrFail($request->session_id);
        if ($session->ecole_id !== $cour->ecole_id) {
            return redirect()->back()->with('error', 'Session invalide pour cette école.');
        }

        $cour->coursHoraires()->create([
            'session_id' => $request->session_id,
            'ecole_id' => $cour->ecole_id,
            'jour_semaine' => $request->jour_semaine,
            'heure_debut' => $request->heure_debut,
            'heure_fin' => $request->heure_fin,
            'nom_affiche' => $request->nom_affiche ?? $cour->nom,
            'salle' => $request->salle,
            'instructeur_affecte' => $request->instructeur_affecte,
            'capacite_max' => $request->capacite_max ?? $cour->capacite_max_defaut,
            'prix' => $request->prix ?? $cour->prix_defaut,
            'actif' => true
        ]);

        return redirect()
            ->route('admin.cours.horaires', $cour)
            ->with('success', 'Horaire ajouté avec succès.');
    }

    /**
     * Duplicate cours to another session.
     */
    public function dupliquerVersSession(Request $request, Cours $cour)
    {
        Gate::authorize('update', $cour);
        
        $request->validate([
            'session_id' => 'required|exists:sessions_cours,id',
            'horaires_ids' => 'required|array',
            'horaires_ids.*' => 'exists:cours_horaires,id'
        ]);

        $session = SessionCours::findOrFail($request->session_id);
        
        // Vérifier appartenance école
        if ($session->ecole_id !== $cour->ecole_id) {
            return redirect()->back()->with('error', 'Session invalide.');
        }

        $horaires = CoursHoraire::whereIn('id', $request->horaires_ids)
            ->where('cours_id', $cour->id)
            ->get();

        $duplicated = 0;
        foreach ($horaires as $horaire) {
            // Éviter doublons
            $exists = CoursHoraire::where('cours_id', $cour->id)
                ->where('session_id', $session->id)
                ->where('jour_semaine', $horaire->jour_semaine)
                ->where('heure_debut', $horaire->heure_debut)
                ->exists();

            if (!$exists) {
                $cour->coursHoraires()->create([
                    'session_id' => $session->id,
                    'ecole_id' => $cour->ecole_id,
                    'jour_semaine' => $horaire->jour_semaine,
                    'heure_debut' => $horaire->heure_debut,
                    'heure_fin' => $horaire->heure_fin,
                    'nom_affiche' => $horaire->nom_affiche,
                    'salle' => $horaire->salle,
                    'instructeur_affecte' => $horaire->instructeur_affecte,
                    'capacite_max' => $horaire->capacite_max,
                    'prix' => $horaire->prix,
                    'actif' => true
                ]);
                $duplicated++;
            }
        }

        return redirect()
            ->route('admin.cours.show', $cour)
            ->with('success', "{$duplicated} horaires dupliqués vers {$session->nom}.");
    }

    /**
     * Get schools available for the current user.
     */
    private function getEcolesForUser()
    {
        if (Auth::user()->hasRole('super_admin')) {
            return Ecole::orderBy('nom')->get();
        }
        
        return Ecole::where('id', Auth::user()->ecole_id)->get();
    }

    /**
     * Get active sessions for current user's school.
     */
    private function getSessionsActives()
    {
        $user = Auth::user();
        
        return SessionCours::when(!$user->hasRole('super_admin'), function ($query) use ($user) {
                return $query->where('ecole_id', $user->ecole_id);
            })
            ->where('actif', true)
            ->orderBy('date_debut', 'desc')
            ->get();
    }

    /**
     * Create horaires for a cours.
     */
    private function creerHoraires(Cours $cours, array $horaires)
    {
        foreach ($horaires as $horaire) {
            if (!isset($horaire['session_id']) || !isset($horaire['jour_semaine'])) {
                continue;
            }

            $cours->coursHoraires()->create([
                'session_id' => $horaire['session_id'],
                'ecole_id' => $cours->ecole_id,
                'jour_semaine' => $horaire['jour_semaine'],
                'heure_debut' => $horaire['heure_debut'],
                'heure_fin' => $horaire['heure_fin'],
                'nom_affiche' => $horaire['nom_affiche'] ?? $cours->nom,
                'salle' => $horaire['salle'] ?? null,
                'instructeur_affecte' => $horaire['instructeur_affecte'] ?? null,
                'capacite_max' => $horaire['capacite_max'] ?? $cours->capacite_max_defaut,
                'prix' => $horaire['prix'] ?? $cours->prix_defaut,
                'actif' => true
            ]);
        }
    }
}
