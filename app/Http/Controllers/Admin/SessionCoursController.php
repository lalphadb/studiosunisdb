<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SessionCours;
use App\Models\Ecole;
use App\Http\Requests\Admin\SessionCoursRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class SessionCoursController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware(['auth', 'verified']),
            new Middleware('can:view-sessions', only: ['index', 'show']),
            new Middleware('can:create-sessions', only: ['create', 'store']),
            new Middleware('can:update-sessions', only: ['edit', 'update']),
            new Middleware('can:delete-sessions', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of sessions for the authenticated user's school.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Multi-tenant strict filtering
        $query = SessionCours::with(['ecole'])
            ->when(!$user->hasRole('super_admin'), function ($query) use ($user) {
                return $query->where('ecole_id', $user->ecole_id);
            })
            ->orderBy('date_debut', 'desc');

        $sessions = $query->paginate(15);
        
        // Stats for dashboard cards
        $stats = [
            'total' => $sessions->total(),
            'actives' => SessionCours::when(!$user->hasRole('super_admin'), function ($query) use ($user) {
                    return $query->where('ecole_id', $user->ecole_id);
                })
                ->where('actif', true)
                ->count(),
            'inscriptions_ouvertes' => SessionCours::when(!$user->hasRole('super_admin'), function ($query) use ($user) {
                    return $query->where('ecole_id', $user->ecole_id);
                })
                ->inscriptionsOuvertes()
                ->count()
        ];

        return view('admin.sessions.index', compact('sessions', 'stats'));
    }

    /**
     * Show the form for creating a new session.
     */
    public function create()
    {
        $ecoles = $this->getEcolesForUser();
        $sessionsPrecedentes = $this->getSessionsPourDuplication();
        
        return view('admin.sessions.create', compact('ecoles', 'sessionsPrecedentes'));
    }

    /**
     * Store a newly created session.
     */
    public function store(SessionCoursRequest $request)
    {
        $data = $request->validated();
        
        // Force ecole_id for non-super-admin users
        if (!Auth::user()->hasRole('super_admin')) {
            $data['ecole_id'] = Auth::user()->ecole_id;
        }

        $session = SessionCours::create($data);

        // Si duplication demandée
        if ($request->filled('dupliquer_depuis_session_id')) {
            $sessionSource = SessionCours::findOrFail($request->dupliquer_depuis_session_id);
            
            // Vérifier que session source appartient à même école
            if ($sessionSource->ecole_id === $session->ecole_id) {
                $this->dupliquerHoraires($sessionSource, $session, $request->all());
            }
        }

        return redirect()
            ->route('admin.sessions.index')
            ->with('success', 'Session créée avec succès.');
    }

    /**
     * Display the specified session.
     */
    public function show(SessionCours $session)
    {
        Gate::authorize('view', $session);
        
        // Load related data with counts
        $session->load([
            'ecole', 
            'coursHoraires.cours',
            'inscriptionsHistorique'
        ]);

        $stats = [
            'total_horaires' => $session->coursHoraires->count(),
            'cours_differents' => $session->coursHoraires->pluck('cours_id')->unique()->count(),
            'inscriptions_total' => $session->inscriptionsHistorique->where('statut', 'active')->count(),
            'revenus_total' => $session->inscriptionsHistorique->where('statut', 'active')->sum('montant_total')
        ];

        return view('admin.sessions.show', compact('session', 'stats'));
    }

    /**
     * Show the form for editing the specified session.
     */
    public function edit(SessionCours $session)
    {
        Gate::authorize('update', $session);
        
        $ecoles = $this->getEcolesForUser();
        
        return view('admin.sessions.edit', compact('session', 'ecoles'));
    }

    /**
     * Update the specified session.
     */
    public function update(SessionCoursRequest $request, SessionCours $session)
    {
        Gate::authorize('update', $session);
        
        $data = $request->validated();
        
        // Prevent ecole_id changes for non-super-admin
        if (!Auth::user()->hasRole('super_admin')) {
            unset($data['ecole_id']);
        }

        $session->update($data);

        return redirect()
            ->route('admin.sessions.index')
            ->with('success', 'Session mise à jour avec succès.');
    }

    /**
     * Remove the specified session.
     */
    public function destroy(SessionCours $session)
    {
        Gate::authorize('delete', $session);
        
        // Check if session has related data
        if ($session->coursHoraires()->count() > 0) {
            return redirect()
                ->route('admin.sessions.index')
                ->with('error', 'Impossible de supprimer une session avec des horaires associés.');
        }

        if ($session->inscriptionsHistorique()->count() > 0) {
            return redirect()
                ->route('admin.sessions.index')
                ->with('error', 'Impossible de supprimer une session avec des inscriptions.');
        }

        $session->delete();

        return redirect()
            ->route('admin.sessions.index')
            ->with('success', 'Session supprimée avec succès.');
    }

    /**
     * Toggle session active status.
     */
    public function toggleActif(SessionCours $session)
    {
        Gate::authorize('update', $session);
        
        $session->update(['actif' => !$session->actif]);

        $message = $session->actif ? 'Session activée' : 'Session désactivée';

        return redirect()
            ->route('admin.sessions.index')
            ->with('success', $message);
    }

    /**
     * Toggle inscriptions status.
     */
    public function toggleInscriptions(SessionCours $session)
    {
        Gate::authorize('update', $session);
        
        if ($session->inscriptions_ouvertes) {
            $session->fermerInscriptions();
            $message = 'Inscriptions fermées';
        } else {
            $session->ouvrirInscriptions();
            $message = 'Inscriptions ouvertes - Les membres vont recevoir un email';
        }

        return redirect()
            ->route('admin.sessions.show', $session)
            ->with('success', $message);
    }

    /**
     * Show duplication form.
     */
    public function showDuplicationForm(SessionCours $session)
    {
        Gate::authorize('view', $session);
        
        if (!$session->peutEtreDupliquee()) {
            return redirect()
                ->route('admin.sessions.index')
                ->with('error', 'Cette session ne peut pas être dupliquée (aucun horaire).');
        }

        $ecoles = $this->getEcolesForUser();
        
        return view('admin.sessions.dupliquer', compact('session', 'ecoles'));
    }

    /**
     * Process session duplication.
     */
    public function dupliquer(Request $request, SessionCours $session)
    {
        Gate::authorize('update', $session);
        
        $request->validate([
            'nom' => 'required|string|max:255',
            'date_debut' => 'required|date|after:today',
            'date_fin' => 'required|date|after:date_debut',
            'ajuster_prix' => 'boolean',
            'pourcentage_augmentation' => 'nullable|numeric|min:0|max:100'
        ]);

        try {
            $nouvelleSession = $session->dupliquerVers($request->all());
            
            return redirect()
                ->route('admin.sessions.show', $nouvelleSession)
                ->with('success', 'Session dupliquée avec succès. ' . $nouvelleSession->coursHoraires->count() . ' horaires copiés.');
                
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.sessions.index')
                ->with('error', 'Erreur lors de la duplication: ' . $e->getMessage());
        }
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
     * Get sessions available for duplication.
     */
    private function getSessionsPourDuplication()
    {
        $user = Auth::user();
        
        return SessionCours::when(!$user->hasRole('super_admin'), function ($query) use ($user) {
                return $query->where('ecole_id', $user->ecole_id);
            })
            ->whereHas('coursHoraires')
            ->orderBy('date_debut', 'desc')
            ->limit(10)
            ->get();
    }

    /**
     * Duplicate horaires from source session to target session.
     */
    private function dupliquerHoraires(SessionCours $source, SessionCours $target, array $options = [])
    {
        $ajusterPrix = $options['ajuster_prix'] ?? false;
        $pourcentage = $options['pourcentage_augmentation'] ?? 0;

        foreach ($source->coursHoraires as $horaire) {
            $target->coursHoraires()->create([
                'cours_id' => $horaire->cours_id,
                'ecole_id' => $target->ecole_id,
                'jour_semaine' => $horaire->jour_semaine,
                'heure_debut' => $horaire->heure_debut,
                'heure_fin' => $horaire->heure_fin,
                'nom_affiche' => $horaire->nom_affiche,
                'salle' => $horaire->salle,
                'instructeur_affecte' => $horaire->instructeur_affecte,
                'capacite_max' => $horaire->capacite_max,
                'prix' => $ajusterPrix ? 
                    $horaire->prix * (1 + $pourcentage / 100) : 
                    $horaire->prix,
                'actif' => true
            ]);
        }
    }
}
