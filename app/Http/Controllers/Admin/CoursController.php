<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseAdminController;
use App\Http\Requests\Admin\CoursRequest;
use App\Models\Cours;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CoursController extends BaseAdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('permission:cours.view')->only(['index', 'show']);
        $this->middleware('permission:cours.create')->only(['create', 'store']);
        $this->middleware('permission:cours.edit')->only(['edit', 'update']);
        $this->middleware('permission:cours.delete')->only(['destroy']);
    }

    public function index(Request $request): View
    {
        try {
            $query = Cours::query();
            
            // Multi-tenant filtering
            if (!auth()->user()->hasRole('superadmin')) {
                $query->where('ecole_id', auth()->user()->ecole_id);
            }
            
            // Search filter
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function($q) use ($search) {
                    $q->where('nom', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%");
                });
            }
            
            // Status filter
            if ($request->filled('statut')) {
                $query->where('statut', $request->get('statut'));
            }
            
            // Get courses with pagination
            $cours = $query->withCount('inscriptions')
                         ->orderBy('created_at', 'desc')
                         ->paginate($this->getPaginationSize($request));
            
            // Calculate statistics
            $stats = $this->getCoursStatistics();
            
            $this->logBusinessAction('Consultation liste cours', 'info', [
                'search' => $request->get('search'),
                'statut' => $request->get('statut'),
                'results_count' => $cours->count()
            ]);
            
            return view('admin.cours.index', compact('cours', 'stats'));
            
        } catch (\Exception $e) {
            $this->logBusinessAction('Erreur affichage liste cours', 'error', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            // Pour la méthode index(), on doit TOUJOURS retourner une View
            $cours = collect([])->paginate(0);
            $stats = [
                'cours_actifs' => 0,
                'cours_inactifs' => 0,
                'total_inscrits' => 0,
                'sessions_semaine' => 0
            ];
            
            // Flash error pour la prochaine requête
            session()->flash('error', 'Une erreur est survenue lors du chargement des cours.');
            
            return view('admin.cours.index', compact('cours', 'stats'));
        }
    }

    public function show(Cours $cours): View
    {
        $this->authorize('view', $cours);
        
        try {
            $cours->load(['inscriptions.user', 'sessions' => function($query) {
                $query->latest()->limit(10);
            }]);
            
            $this->logBusinessAction('Consultation détail cours', 'info', [
                'cours_id' => $cours->id,
                'cours_nom' => $cours->nom
            ]);
            
            return view('admin.cours.show', compact('cours'));
            
        } catch (\Exception $e) {
            $this->logBusinessAction('Erreur affichage détail cours', 'error', [
                'cours_id' => $cours->id,
                'error' => $e->getMessage()
            ]);
            
            session()->flash('error', 'Erreur lors du chargement du cours.');
            return view('admin.cours.show', compact('cours'));
        }
    }

    public function create(): View
    {
        $this->authorize('create', Cours::class);
        
        return view('admin.cours.create');
    }

    public function store(CoursRequest $request): RedirectResponse
    {
        $this->authorize('create', Cours::class);
        
        try {
            $data = $request->validated();
            $data['ecole_id'] = auth()->user()->ecole_id;
            
            $cours = Cours::create($data);
            
            $this->logCreate('cours', $cours->id, $data);
            
            return $this->redirectWithSuccess(
                'admin.cours.index',
                'Cours créé avec succès.'
            );
            
        } catch (\Exception $e) {
            $this->logBusinessAction('Erreur création cours', 'error', [
                'error' => $e->getMessage(),
                'data' => $request->validated()
            ]);
            
            return $this->backWithError('Erreur lors de la création du cours.');
        }
    }

    public function edit(Cours $cours): View
    {
        $this->authorize('update', $cours);
        
        return view('admin.cours.edit', compact('cours'));
    }

    public function update(CoursRequest $request, Cours $cours): RedirectResponse
    {
        $this->authorize('update', $cours);
        
        try {
            $oldData = $cours->toArray();
            $newData = $request->validated();
            
            $cours->update($newData);
            
            $this->logUpdate('cours', $cours->id, $oldData, $newData);
            
            return $this->redirectWithSuccess(
                'admin.cours.index',
                'Cours modifié avec succès.'
            );
            
        } catch (\Exception $e) {
            $this->logBusinessAction('Erreur modification cours', 'error', [
                'cours_id' => $cours->id,
                'error' => $e->getMessage()
            ]);
            
            return $this->backWithError('Erreur lors de la modification du cours.');
        }
    }

    public function destroy(Cours $cours): RedirectResponse
    {
        $this->authorize('delete', $cours);
        
        try {
            $coursData = $cours->toArray();
            $cours->delete();
            
            $this->logDelete('cours', $cours->id, $coursData);
            
            return $this->redirectWithSuccess(
                'admin.cours.index',
                'Cours supprimé avec succès.'
            );
            
        } catch (\Exception $e) {
            $this->logBusinessAction('Erreur suppression cours', 'error', [
                'cours_id' => $cours->id,
                'error' => $e->getMessage()
            ]);
            
            return $this->backWithError('Erreur lors de la suppression du cours.');
        }
    }

    private function getCoursStatistics(): array
    {
        try {
            $query = Cours::query();
            
            if (!auth()->user()->hasRole('superadmin')) {
                $query->where('ecole_id', auth()->user()->ecole_id);
            }
            
            return [
                'cours_actifs' => (clone $query)->where('statut', 'actif')->count(),
                'cours_inactifs' => (clone $query)->where('statut', 'inactif')->count(),
                'total_inscrits' => 0, // Temporaire - à implémenter selon votre logique
                'sessions_semaine' => 0 // Temporaire - à implémenter selon votre logique
            ];
            
        } catch (\Exception $e) {
            $this->logBusinessAction('Erreur calcul statistiques cours', 'error', [
                'error' => $e->getMessage()
            ]);
            
            return [
                'cours_actifs' => 0,
                'cours_inactifs' => 0,
                'total_inscrits' => 0,
                'sessions_semaine' => 0
            ];
        }
    }
}
