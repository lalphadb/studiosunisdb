<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\StorePresenceRequest;
use App\Http\Requests\Admin\UpdatePresenceRequest;
use App\Models\Presence;
use App\Models\User;
use App\Models\SessionCours;
use App\Models\CoursHoraire;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

/**
 * Contrôleur de gestion des présences
 * 
 * Gère l'enregistrement et le suivi des présences aux cours avec:
 * - Multi-tenant strict par ecole_id
 * - Statistiques de fréquentation
 * - Export et rapports
 */
class PresenceController extends BaseAdminController
{
    /**
     * Initialise le contrôleur avec permissions
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('permission:view_presences')->only(['index', 'show']);
        $this->middleware('permission:create_presences')->only(['create', 'store']);
        $this->middleware('permission:edit_presences')->only(['edit', 'update']);
        $this->middleware('permission:delete_presences')->only(['destroy']);
    }

    /**
     * Liste des présences avec filtres
     */
    public function index(Request $request): View
    {
        try {
            $query = Presence::with(['user', 'coursHoraire.cours', 'sessionCours', 'ecole']);

            // Filtrage multi-tenant strict
            if (auth()->user()->hasRole('admin_ecole')) {
                $query->where('ecole_id', auth()->user()->ecole_id);
            }

            // Filtres
            if ($request->filled('search')) {
                $search = $request->search;
                $query->whereHas('user', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            }

            if ($request->filled('session_id')) {
                $query->where('session_cours_id', $request->session_id);
            }

            if ($request->filled('cours_id')) {
                $query->whereHas('coursHoraire', function($q) use ($request) {
                    $q->where('cours_id', $request->cours_id);
                });
            }

            if ($request->filled('statut')) {
                $query->where('statut', $request->statut);
            }

            if ($request->filled('date_debut')) {
                $query->where('date_presence', '>=', $request->date_debut);
            }

            if ($request->filled('date_fin')) {
                $query->where('date_presence', '<=', $request->date_fin);
            }

            $presences = $query->orderBy('date_presence', 'desc')
                             ->orderBy('created_at', 'desc')
                             ->paginate(25);

            // Données pour les filtres
            $sessions = $this->getSessionsForUser();
            $cours = $this->getCoursForUser();

            Log::info('Consultation index présences', [
                'user_id' => auth()->id(),
                'ecole_id' => auth()->user()->ecole_id,
                'total_presences' => $presences->total()
            ]);

            return view('admin.presences.index', compact('presences', 'sessions', 'cours'));

        } catch (\Exception $e) {
            Log::error('Erreur index présences', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            return $this->redirectWithError('admin.dashboard', 'Erreur lors du chargement des présences');
        }
    }

    /**
     * Formulaire création présence
     */
    public function create(): View
    {
        $users = $this->getMembresForUser();
        $sessions = $this->getSessionsForUser();
        $coursHoraires = collect(); // Sera rempli via AJAX selon la session

        return view('admin.presences.create', compact('users', 'sessions', 'coursHoraires'));
    }

    /**
     * Enregistrer nouvelle présence
     */
    public function store(StorePresenceRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $validated = $request->validated();
            
            // Auto-assignation ecole_id
            $validated['ecole_id'] = $this->getEcoleIdForUser($validated['user_id']);
            $validated['enregistre_par'] = auth()->id();

            $presence = Presence::create($validated);

            Log::info('Présence créée', [
                'user_id' => auth()->id(),
                'presence_id' => $presence->id,
                'membre_id' => $presence->user_id,
                'date_presence' => $presence->date_presence
            ]);

            DB::commit();

            return $this->redirectWithSuccess('admin.presences.index', 'Présence enregistrée avec succès');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Erreur création présence', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'data' => $request->validated()
            ]);

            return $this->redirectWithError('admin.presences.create', 'Erreur lors de l\'enregistrement');
        }
    }

    /**
     * Afficher détails présence
     */
    public function show(Presence $presence): View
    {
        // Vérification multi-tenant
        if (auth()->user()->hasRole('admin_ecole') && 
            $presence->ecole_id !== auth()->user()->ecole_id) {
            abort(403);
        }

        $presence->load(['user', 'coursHoraire.cours', 'sessionCours', 'ecole']);
        return view('admin.presences.show', compact('presence'));
    }

    /**
     * Formulaire édition présence
     */
    public function edit(Presence $presence): View
    {
        // Vérification multi-tenant
        if (auth()->user()->hasRole('admin_ecole') && 
            $presence->ecole_id !== auth()->user()->ecole_id) {
            abort(403);
        }

        $users = $this->getMembresForUser();
        $sessions = $this->getSessionsForUser();
        $coursHoraires = $this->getCoursHorairesForSession($presence->session_cours_id);

        return view('admin.presences.edit', compact('presence', 'users', 'sessions', 'coursHoraires'));
    }

    /**
     * Mettre à jour présence
     */
    public function update(UpdatePresenceRequest $request, Presence $presence): RedirectResponse
    {
        // Vérification multi-tenant
        if (auth()->user()->hasRole('admin_ecole') && 
            $presence->ecole_id !== auth()->user()->ecole_id) {
            abort(403);
        }

        try {
            DB::beginTransaction();

            $validated = $request->validated();
            $presence->update($validated);

            Log::info('Présence mise à jour', [
                'user_id' => auth()->id(),
                'presence_id' => $presence->id,
                'old_statut' => $presence->getOriginal('statut'),
                'new_statut' => $presence->statut
            ]);

            DB::commit();

            return $this->redirectWithSuccess('admin.presences.index', 'Présence mise à jour avec succès');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Erreur mise à jour présence', [
                'user_id' => auth()->id(),
                'presence_id' => $presence->id,
                'error' => $e->getMessage()
            ]);

            return $this->redirectWithError('admin.presences.edit', 'Erreur lors de la mise à jour');
        }
    }

    /**
     * Supprimer présence
     */
    public function destroy(Presence $presence): RedirectResponse
    {
        // Vérification multi-tenant
        if (auth()->user()->hasRole('admin_ecole') && 
            $presence->ecole_id !== auth()->user()->ecole_id) {
            abort(403);
        }

        try {
            Log::info('Présence supprimée', [
                'user_id' => auth()->id(),
                'presence_id' => $presence->id,
                'membre_name' => $presence->user->name
            ]);

            $presence->delete();

            return $this->redirectWithSuccess('admin.presences.index', 'Présence supprimée avec succès');

        } catch (\Exception $e) {
            Log::error('Erreur suppression présence', [
                'user_id' => auth()->id(),
                'presence_id' => $presence->id,
                'error' => $e->getMessage()
            ]);

            return $this->redirectWithError('admin.presences.index', 'Erreur lors de la suppression');
        }
    }

    /**
     * MÉTHODES PRIVÉES - HELPERS
     */

    private function getMembresForUser()
    {
        $query = User::select('id', 'name', 'email', 'ecole_id')->orderBy('name');
        
        if (auth()->user()->hasRole('admin_ecole')) {
            $query->where('ecole_id', auth()->user()->ecole_id);
        }
        
        return $query->get();
    }

    private function getSessionsForUser()
    {
        $query = SessionCours::select('id', 'nom', 'date_debut', 'date_fin', 'ecole_id')
                            ->orderBy('date_debut', 'desc');
        
        if (auth()->user()->hasRole('admin_ecole')) {
            $query->where('ecole_id', auth()->user()->ecole_id);
        }
        
        return $query->get();
    }

    private function getCoursForUser()
    {
        $query = \App\Models\Cours::select('id', 'nom', 'ecole_id')
                                 ->where('active', true)
                                 ->orderBy('nom');
        
        if (auth()->user()->hasRole('admin_ecole')) {
            $query->where('ecole_id', auth()->user()->ecole_id);
        }
        
        return $query->get();
    }

    private function getCoursHorairesForSession(?int $sessionId)
    {
        if (!$sessionId) return collect();

        $query = CoursHoraire::with('cours')
                           ->where('session_id', $sessionId)
                           ->where('actif', true)
                           ->orderBy('jour_semaine')
                           ->orderBy('heure_debut');
        
        if (auth()->user()->hasRole('admin_ecole')) {
            $query->where('ecole_id', auth()->user()->ecole_id);
        }
        
        return $query->get();
    }

    private function getEcoleIdForUser(int $userId): int
    {
        if (auth()->user()->hasRole('admin_ecole')) {
            return auth()->user()->ecole_id;
        }
        
        return User::findOrFail($userId)->ecole_id;
    }
}
