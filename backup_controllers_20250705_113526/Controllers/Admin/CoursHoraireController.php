<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\StoreCoursHoraireRequest;
use App\Http\Requests\Admin\UpdateCoursHoraireRequest;
use App\Models\CoursHoraire;
use App\Models\Cours;
use App\Models\SessionCours;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Contrôleur de gestion des horaires de cours
 * 
 * Migré vers BaseAdminController avec standards Laravel 12
 */
class CoursHoraireController extends BaseAdminController
{
    /**
     * Initialise le contrôleur avec permissions
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->middleware('can:viewAny,App\Models\CoursHoraire')->only(['index']);
        $this->middleware('can:view,coursHoraire')->only(['show']);
        $this->middleware('can:create,App\Models\CoursHoraire')->only(['create', 'store']);
        $this->middleware('can:update,coursHoraire')->only(['edit', 'update', 'dupliquer']);
        $this->middleware('can:delete,coursHoraire')->only(['destroy']);
    }

    /**
     * Display horaires for a specific cours and session
     */
    public function index(Request $request): View
    {
        return $this->executeWithExceptionHandling(function() use ($request) {
            $user = auth()->user();
            $ecoleId = $user->ecole_id;

            $query = CoursHoraire::pourEcole($ecoleId)
                ->with(['cours', 'session', 'ecole'])
                ->withCount(['inscriptions']);

            // Filtres
            if ($request->filled('cours_id')) {
                $query->where('cours_id', $request->cours_id);
            }

            if ($request->filled('session_id')) {
                $query->where('session_id', $request->session_id);
            }

            if ($request->filled('jour_semaine')) {
                $query->where('jour_semaine', $request->jour_semaine);
            }

            $horaires = $this->paginateWithParams(
                $query->orderBy('jour_semaine')->orderBy('heure_debut'),
                $request
            );

            // Options pour les filtres
            $cours = Cours::pourEcole($ecoleId)->actifs()->orderBy('nom')->get();
            $sessions = SessionCours::pourEcole($ecoleId)->orderBy('date_debut', 'desc')->get();
            $jours = [
                'lundi' => 'Lundi',
                'mardi' => 'Mardi', 
                'mercredi' => 'Mercredi',
                'jeudi' => 'Jeudi',
                'vendredi' => 'Vendredi',
                'samedi' => 'Samedi',
                'dimanche' => 'Dimanche'
            ];

            $this->logBusinessAction('Consultation index horaires', 'info', [
                'total_horaires' => $horaires->total(),
                'filters' => $request->only(['cours_id', 'session_id', 'jour_semaine'])
            ]);

            return view('pages.admin.cours-horaires.index', compact('horaires', 'cours', 'sessions', 'jours'));
            
        }, 'consultation horaires');
    }

    /**
     * Show the form for creating a new horaire
     */
    public function create(Request $request): View
    {
        $user = auth()->user();
        
        // Pré-remplir si cours_id et session_id fournis
        $coursSelectionne = null;
        $sessionSelectionnee = null;
        
        if ($request->filled('cours_id')) {
            $coursSelectionne = Cours::pourEcole($user->ecole_id)->findOrFail($request->cours_id);
        }
        
        if ($request->filled('session_id')) {
            $sessionSelectionnee = SessionCours::pourEcole($user->ecole_id)->findOrFail($request->session_id);
        }

        $cours = Cours::pourEcole($user->ecole_id)->actifs()->orderBy('nom')->get();
        $sessions = SessionCours::pourEcole($user->ecole_id)->orderBy('date_debut', 'desc')->get();
        
        $jours = [
            'lundi' => 'Lundi',
            'mardi' => 'Mardi',
            'mercredi' => 'Mercredi',
            'jeudi' => 'Jeudi',
            'vendredi' => 'Vendredi',
            'samedi' => 'Samedi',
            'dimanche' => 'Dimanche'
        ];

        return view('pages.admin.cours-horaires.create', compact(
            'cours', 'sessions', 'jours', 'coursSelectionne', 'sessionSelectionnee'
        ));
    }

    /**
     * Store a newly created horaire
     */
    public function store(StoreCoursHoraireRequest $request): RedirectResponse
    {
        return $this->executeWithExceptionHandling(function() use ($request) {
            $user = auth()->user();
            $validated = $request->validated();
            
            // Vérifier que le cours appartient à la même école
            $cours = Cours::findOrFail($validated['cours_id']);
            if ($cours->ecole_id !== $user->ecole_id) {
                return $this->backWithError('Cours non autorisé pour votre école.');
            }

            // Vérifier que la session appartient à la même école si fournie
            if ($validated['session_id']) {
                $session = SessionCours::findOrFail($validated['session_id']);
                if ($session->ecole_id !== $user->ecole_id) {
                    return $this->backWithError('Session non autorisée pour votre école.');
                }
            }

            $validated['ecole_id'] = $user->ecole_id;
            
            $horaire = CoursHoraire::create($validated);

            $this->logCreate('Horaire', $horaire->id, $validated);

            return $this->redirectWithSuccess(
                'pages.admin.cours.show',
                "Horaire créé avec succès pour '{$cours->nom}'.",
                ['cours' => $cours]
            );
            
        }, 'création horaire', ['form_data' => $request->validated()]);
    }

    /**
     * Display the specified horaire
     */
    public function show(CoursHoraire $coursHoraire): View
    {
        $coursHoraire->load([
            'cours.ecole',
            'session',
            'inscriptions.user'
        ]);

        // Statistiques
        $stats = [
            'nombre_inscrits' => $coursHoraire->inscriptions->where('statut', '!=', 'annule')->count(),
            'places_restantes' => $coursHoraire->nombre_places_restantes,
            'taux_occupation' => $coursHoraire->capacite_effective > 0 
                ? round(($coursHoraire->nombre_inscrits / $coursHoraire->capacite_effective) * 100, 1)
                : 0,
            'revenus' => $coursHoraire->inscriptions->where('statut', '!=', 'annule')->sum('prix_paye')
        ];

        return view('pages.admin.cours-horaires.show', compact('coursHoraire', 'stats'));
    }

    /**
     * Show the form for editing the specified horaire
     */
    public function edit(CoursHoraire $coursHoraire): View
    {
        $cours = Cours::pourEcole($coursHoraire->ecole_id)->actifs()->orderBy('nom')->get();
        $sessions = SessionCours::pourEcole($coursHoraire->ecole_id)->orderBy('date_debut', 'desc')->get();
        
        $jours = [
            'lundi' => 'Lundi',
            'mardi' => 'Mardi',
            'mercredi' => 'Mercredi',
            'jeudi' => 'Jeudi',
            'vendredi' => 'Vendredi',
            'samedi' => 'Samedi',
            'dimanche' => 'Dimanche'
        ];

        return view('pages.admin.cours-horaires.edit', compact('coursHoraire', 'cours', 'sessions', 'jours'));
    }

    /**
     * Update the specified horaire
     */
    public function update(UpdateCoursHoraireRequest $request, CoursHoraire $coursHoraire): RedirectResponse
    {
        return $this->executeWithExceptionHandling(function() use ($request, $coursHoraire) {
            $validated = $request->validated();
            $oldData = $coursHoraire->toArray();

            $coursHoraire->update($validated);

            $this->logUpdate('Horaire', $coursHoraire->id, $oldData, $validated);

            return $this->redirectWithSuccess(
                'pages.admin.cours-horaires.show',
                'Horaire mis à jour avec succès.',
                ['coursHoraire' => $coursHoraire]
            );
            
        }, 'modification horaire', ['horaire_id' => $coursHoraire->id]);
    }

    /**
     * Remove the specified horaire
     */
    public function destroy(CoursHoraire $coursHoraire): RedirectResponse
    {
        return $this->executeWithExceptionHandling(function() use ($coursHoraire) {
            // Vérifier qu'il n'y a pas d'inscriptions actives
            $inscriptionsActives = $coursHoraire->inscriptions()->actives()->count();
            if ($inscriptionsActives > 0) {
                return $this->backWithError("Impossible de supprimer cet horaire : {$inscriptionsActives} inscription(s) active(s).");
            }

            $cours = $coursHoraire->cours;
            
            $this->logDelete('Horaire', $coursHoraire->id, [
                'cours_nom' => $cours->nom,
                'jour_semaine' => $coursHoraire->jour_semaine
            ]);

            $coursHoraire->delete();

            return $this->redirectWithSuccess(
                'pages.admin.cours.show',
                'Horaire supprimé avec succès.',
                ['cours' => $cours]
            );
            
        }, 'suppression horaire', ['horaire_id' => $coursHoraire->id]);
    }

    /**
     * Duplicate an horaire to multiple days or sessions
     */
    public function dupliquer(Request $request, CoursHoraire $coursHoraire): RedirectResponse
    {
        return $this->executeWithExceptionHandling(function() use ($request, $coursHoraire) {
            $validated = $request->validate([
                'jours' => 'array|min:1',
                'jours.*' => 'in:lundi,mardi,mercredi,jeudi,vendredi,samedi,dimanche',
                'session_ids' => 'array|min:1',
                'session_ids.*' => 'exists:sessions_cours,id'
            ]);

            $compteur = 0;
            
            foreach ($validated['session_ids'] ?? [$coursHoraire->session_id] as $sessionId) {
                foreach ($validated['jours'] as $jour) {
                    // Éviter de dupliquer sur soi-même
                    if ($jour === $coursHoraire->jour_semaine && $sessionId === $coursHoraire->session_id) {
                        continue;
                    }

                    // Vérifier si un horaire similaire existe déjà
                    $existe = CoursHoraire::where([
                        'cours_id' => $coursHoraire->cours_id,
                        'session_id' => $sessionId,
                        'jour_semaine' => $jour,
                        'heure_debut' => $coursHoraire->heure_debut
                    ])->exists();

                    if (!$existe) {
                        $nouveauHoraire = $coursHoraire->replicate();
                        $nouveauHoraire->session_id = $sessionId;
                        $nouveauHoraire->jour_semaine = $jour;
                        $nouveauHoraire->save();
                        $compteur++;
                    }
                }
            }

            $this->logBusinessAction('Duplication horaires', 'info', [
                'horaire_original_id' => $coursHoraire->id,
                'horaires_dupliques' => $compteur
            ]);

            return $this->backWithSuccess("{$compteur} horaire(s) dupliqué(s) avec succès.");
            
        }, 'duplication horaires', ['horaire_id' => $coursHoraire->id]);
    }
}
