<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseAdminController;
use App\Http\Requests\Admin\PresenceRequest;
use App\Models\Presence;
use App\Models\Cours;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PresenceController extends BaseAdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('permission:presences.view')->only(['index', 'show']);
        $this->middleware('permission:presences.create')->only(['create', 'store']);
        $this->middleware('permission:presences.edit')->only(['edit', 'update']);
        $this->middleware('permission:presences.delete')->only(['destroy']);
    }

    public function index(Request $request): View
    {
        return $this->executeWithExceptionHandling(function() use ($request) {
            $query = Presence::with(['user', 'cours.ecole'])->orderBy('date_cours', 'desc');
            
            // Multi-tenant filtering
            if (!auth()->user()->hasRole('superadmin')) {
                $query->whereHas('cours', function($q) {
                    $q->where('ecole_id', auth()->user()->ecole_id);
                });
            }
            
            // Recherche
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->whereHas('user', function($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%");
                    })->orWhereHas('cours', function($cq) use ($search) {
                        $cq->where('nom', 'like', "%{$search}%");
                    });
                });
            }
            
            // Filtres
            if ($request->filled('cours_id')) {
                $query->where('cours_id', $request->cours_id);
            }
            
            if ($request->filled('date_cours')) {
                $query->where('date_cours', $request->date_cours);
            }
            
            if ($request->has('present')) {
                $query->where('present', $request->boolean('present'));
            }
            
            $presences = $this->paginateWithParams($query, $request);
            $cours = $this->getCoursForUser(auth()->user());
            
            // Statistiques
            $stats = [
                'total_presences' => $presences->total(),
                'presences_aujourd_hui' => Presence::whereDate('date_cours', today())->count(),
                'taux_presence' => $this->calculateTauxPresence()
            ];
            
            $this->logBusinessAction('Consultation présences', 'info', [
                'total' => $presences->total(),
                'filters' => $request->only(['search', 'cours_id', 'date_cours', 'present'])
            ]);
            
            return view('admin.presences.index', compact('presences', 'cours', 'stats'));
            
        }, 'consultation présences');
    }

    public function create(): View
    {
        $cours = $this->getCoursForUser(auth()->user());
        $users = $this->getUsersForPresence();
        
        return view('admin.presences.create', compact('cours', 'users'));
    }

    public function store(PresenceRequest $request): RedirectResponse
    {
        return $this->executeWithExceptionHandling(function() use ($request) {
            $validated = $request->validated();
            
            // Ajouter l'école pour admin_ecole
            if (!auth()->user()->hasRole('superadmin')) {
                // Vérifier que le cours appartient à l'école de l'utilisateur
                $cours = Cours::findOrFail($validated['cours_id']);
                if ($cours->ecole_id !== auth()->user()->ecole_id) {
                    return $this->backWithError('Cours non autorisé pour votre école.');
                }
            }
            
            $presence = Presence::create($validated);
            
            $this->logCreate('Présence', $presence->id, $validated);
            
            return $this->redirectWithSuccess(
                'admin.presences.index',
                'Présence enregistrée avec succès.'
            );
            
        }, 'création présence', ['form_data' => $request->validated()]);
    }

    public function show(Presence $presence): View
    {
        $this->authorize('view', $presence);
        $presence->load(['user', 'cours.ecole']);
        
        return view('admin.presences.show', compact('presence'));
    }

    public function edit(Presence $presence): View
    {
        $this->authorize('update', $presence);
        
        $cours = $this->getCoursForUser(auth()->user());
        $users = $this->getUsersForPresence();
        
        return view('admin.presences.edit', compact('presence', 'cours', 'users'));
    }

    public function update(PresenceRequest $request, Presence $presence): RedirectResponse
    {
        return $this->executeWithExceptionHandling(function() use ($request, $presence) {
            $this->authorize('update', $presence);
            
            $oldData = $presence->toArray();
            $newData = $request->validated();
            
            $presence->update($newData);
            
            $this->logUpdate('Présence', $presence->id, $oldData, $newData);
            
            return $this->redirectWithSuccess(
                'admin.presences.index',
                'Présence mise à jour avec succès.'
            );
            
        }, 'modification présence', ['presence_id' => $presence->id]);
    }

    public function destroy(Presence $presence): RedirectResponse
    {
        return $this->executeWithExceptionHandling(function() use ($presence) {
            $this->authorize('delete', $presence);
            
            $presenceData = [
                'user_name' => $presence->user->name,
                'cours_nom' => $presence->cours->nom,
                'date_cours' => $presence->date_cours
            ];
            
            $this->logDelete('Présence', $presence->id, $presenceData);
            
            $presence->delete();
            
            return $this->redirectWithSuccess(
                'admin.presences.index',
                'Présence supprimée avec succès.'
            );
            
        }, 'suppression présence', ['presence_id' => $presence->id]);
    }

    /**
     * Marquer une présence rapidement (AJAX)
     */
    public function marquer(Request $request)
    {
        return $this->executeWithExceptionHandling(function() use ($request) {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'cours_id' => 'required|exists:cours,id',
                'date_cours' => 'required|date',
                'present' => 'boolean'
            ]);

            // Vérifier les permissions multi-tenant
            if (!auth()->user()->hasRole('superadmin')) {
                $cours = Cours::findOrFail($validated['cours_id']);
                if ($cours->ecole_id !== auth()->user()->ecole_id) {
                    return $this->apiError('Cours non autorisé pour votre école.', 403);
                }
            }

            $presence = Presence::updateOrCreate([
                'user_id' => $validated['user_id'],
                'cours_id' => $validated['cours_id'],
                'date_cours' => $validated['date_cours']
            ], [
                'present' => $validated['present'] ?? true
            ]);

            $this->logBusinessAction('Présence marquée', 'info', [
                'presence_id' => $presence->id,
                'user_id' => $validated['user_id'],
                'cours_id' => $validated['cours_id'],
                'present' => $validated['present'] ?? true
            ]);

            return $this->apiResponse([
                'presence' => $presence
            ], 'Présence marquée avec succès');
            
        }, 'marquage présence');
    }

    /**
     * Obtenir les cours selon les permissions
     */
    private function getCoursForUser($user)
    {
        $query = Cours::with('ecole')->orderBy('nom');
        
        if (!$user->hasRole('superadmin')) {
            $query->where('ecole_id', $user->ecole_id);
        }
        
        return $query->get();
    }

    /**
     * Obtenir les utilisateurs selon les permissions
     */
    private function getUsersForPresence()
    {
        $query = User::with('ecole')->orderBy('name');
        
        if (!auth()->user()->hasRole('superadmin')) {
            $query->where('ecole_id', auth()->user()->ecole_id);
        }
        
        return $query->get();
    }

    /**
     * Calculer le taux de présence global
     */
    private function calculateTauxPresence(): float
    {
        $totalPresences = Presence::count();
        if ($totalPresences === 0) return 0;

        $presentsCount = Presence::where('present', true)->count();
        
        return round(($presentsCount / $totalPresences) * 100, 1);
    }
}
