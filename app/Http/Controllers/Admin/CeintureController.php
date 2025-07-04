<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Models\UserCeinture;
use App\Models\Ceinture;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

/**
 * Contrôleur de gestion des ceintures et attributions
 * 
 * Migré vers BaseAdminController avec permissions Laravel Policy
 */
class CeintureController extends BaseAdminController
{
    /**
     * Initialise le contrôleur avec permissions Policy (comme l'ancien)
     */
    public function __construct()
    {
        parent::__construct();
        
        // UTILISER LES MÊMES PERMISSIONS QUE L'ANCIEN CONTRÔLEUR
        $this->middleware('can:viewAny,App\Models\UserCeinture')->only(['index']);
        $this->middleware('can:create,App\Models\UserCeinture')->only(['create', 'store', 'createMasse', 'storeMasse']);
        $this->middleware('can:update,userCeinture')->only(['edit', 'update']);
        $this->middleware('can:delete,userCeinture')->only(['destroy']);
    }

    /**
     * SUIVI PROGRESSION - Liste des attributions de ceintures (CORRIGÉ)
     */
    public function index(Request $request): View
    {
        try {
            // CORRECTION: Afficher les ATTRIBUTIONS au lieu des ceintures
            $query = UserCeinture::with(['user', 'ceinture', 'instructeur', 'ecole'])
                ->orderBy('date_obtention', 'desc');
            
            // Multi-tenant: Admin d'école voit ses attributions
            if (auth()->user()->hasRole('admin_ecole')) {
                $query->where('ecole_id', auth()->user()->ecole_id);
            }
            
            // Filtres
            if ($request->filled('ceinture_id')) {
                $query->where('ceinture_id', $request->ceinture_id);
            }
            
            if ($request->filled('periode')) {
                match($request->periode) {
                    '7j' => $query->where('date_obtention', '>=', now()->subDays(7)),
                    '30j' => $query->where('date_obtention', '>=', now()->subDays(30)),
                    '3m' => $query->where('date_obtention', '>=', now()->subDays(90)),
                    '6m' => $query->where('date_obtention', '>=', now()->subDays(180)),
                    default => null
                };
            }
            
            if ($request->filled('search')) {
                $query->whereHas('user', function($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%');
                });
            }
            
            $attributions = $query->paginate(20);
            
            // Données pour filtres
            $ceintures = Ceinture::orderBy('ordre')->get();
            
            // Statistiques
            $stats = $this->getStats();
            
            // Données pour attribution masse
            $membres = $this->getMembresForUser();

            Log::info('Consultation index ceintures', [
                'user_id' => auth()->id(),
                'ecole_id' => auth()->user()->ecole_id,
                'total_attributions' => $attributions->total()
            ]);
            
            return view('admin.ceintures.index', compact('attributions', 'ceintures', 'stats', 'membres'));

        } catch (\Exception $e) {
            Log::error('Erreur index ceintures', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            return $this->redirectWithError('admin.dashboard', 'Erreur lors du chargement des ceintures');
        }
    }

    /**
     * Attribution individuelle
     */
    public function create(Request $request): View
    {
        $userId = $request->user_id;
        $user = null;
        
        if ($userId) {
            $user = User::with('ecole')->findOrFail($userId);
            
            // Vérifier autorisation multi-tenant
            if (auth()->user()->hasRole('admin_ecole') && 
                $user->ecole_id !== auth()->user()->ecole_id) {
                abort(403);
            }
        }
        
        $ceintures = Ceinture::orderBy('ordre')->get();
        $membres = $this->getMembresForUser();
        
        return view('admin.ceintures.create', compact('user', 'ceintures', 'membres'));
    }

    /**
     * Enregistrer attribution individuelle
     */
    public function store(Request $request): RedirectResponse
    {
        // VALIDATION IDENTIQUE À L'ANCIEN
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'ceinture_id' => 'required|exists:ceintures,id',
            'date_obtention' => 'required|date',
            'examinateur' => 'nullable|string|max:255',
            'commentaires' => 'nullable|string|max:1000',
            'valide' => 'nullable|boolean',
        ]);

        try {
            DB::beginTransaction();
            
            $user = User::findOrFail($request->user_id);
            
            // Vérification multi-tenant
            if (auth()->user()->hasRole('admin_ecole') && 
                $user->ecole_id !== auth()->user()->ecole_id) {
                abort(403);
            }
            
            $attribution = UserCeinture::create([
                'user_id' => $request->user_id,
                'ceinture_id' => $request->ceinture_id,
                'date_attribution' => now(),
                'date_obtention' => $request->date_obtention,
                'attribue_par' => auth()->id(),
                'instructeur_id' => auth()->id(),
                'ecole_id' => $user->ecole_id,
                'examinateur' => $request->examinateur,
                'commentaires' => $request->commentaires,
                'valide' => $request->boolean('valide', true),
                'certifie' => true,
            ]);

            Log::info('Ceinture attribuée', [
                'user_id' => auth()->id(),
                'attribution_id' => $attribution->id,
                'membre_id' => $user->id,
                'ceinture_id' => $attribution->ceinture_id
            ]);

            DB::commit();
            
            return redirect()->route('admin.ceintures.index')
                ->with('success', 'Ceinture attribuée avec succès à ' . $user->name);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Erreur attribution ceinture', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            return back()->withErrors(['error' => 'Erreur lors de l\'attribution : ' . $e->getMessage()]);
        }
    }

    /**
     * ATTRIBUTION EN MASSE - Interface
     */
    public function createMasse(): View
    {
        $ecoleId = auth()->user()->ecole_id;
        
        // Ceintures disponibles
        $ceintures = Ceinture::orderBy('ordre')->get();
        
        // Membres de l'école avec leur ceinture actuelle
        $users = User::where('ecole_id', $ecoleId)
                    ->where('id', '!=', auth()->id())
                    ->with(['ceintures' => function($query) {
                        $query->wherePivot('valide', true)
                              ->latest('user_ceintures.date_obtention');
                    }])
                    ->orderBy('name')
                    ->get();
        
        return view('admin.ceintures.create-masse', compact('ceintures', 'users'));
    }

    /**
     * ATTRIBUTION EN MASSE - Traitement
     */
    public function storeMasse(Request $request): RedirectResponse
    {
        // VALIDATION IDENTIQUE À L'ANCIEN
        $request->validate([
            'ceinture_id' => 'required|exists:ceintures,id',
            'date_obtention' => 'required|date',
            'notes' => 'nullable|string|max:1000',
            'attributions' => 'required|array|min:1',
            'attributions.*.user_id' => 'required|exists:users,id',
        ]);

        try {
            $user = auth()->user();
            $ecoleId = $user->ecole_id;
            
            // Vérifier que la ceinture existe
            $ceinture = Ceinture::findOrFail($request->ceinture_id);
            
            // Extraire les user_ids
            $userIds = collect($request->attributions)->pluck('user_id')->filter();
            
            // Vérifier que tous les utilisateurs appartiennent à l'école
            $users = User::whereIn('id', $userIds)
                        ->where('ecole_id', $ecoleId)
                        ->get();

            if ($users->count() !== $userIds->count()) {
                return back()->withErrors(['attributions' => 'Certains utilisateurs ne sont pas valides.']);
            }

            DB::beginTransaction();
            
            $count = 0;
            $examenId = 'EXAM_' . now()->format('Ymd_His');
            
            foreach ($request->attributions as $attribution) {
                if (isset($attribution['user_id']) && !empty($attribution['user_id'])) {
                    // Éviter les doublons
                    $exists = UserCeinture::where('user_id', $attribution['user_id'])
                                        ->where('ceinture_id', $request->ceinture_id)
                                        ->where('ecole_id', $ecoleId)
                                        ->exists();
                    
                    if (!$exists) {
                        UserCeinture::create([
                            'user_id' => $attribution['user_id'],
                            'ceinture_id' => $request->ceinture_id,
                            'date_attribution' => now(),
                            'date_obtention' => $request->date_obtention,
                            'attribue_par' => $user->id,
                            'instructeur_id' => $user->id,
                            'ecole_id' => $ecoleId,
                            'examen_id' => $examenId,
                            'notes' => $request->notes,
                            'valide' => true,
                            'certifie' => true,
                        ]);
                        $count++;
                    }
                }
            }

            Log::info('Attribution masse ceintures', [
                'user_id' => auth()->id(),
                'ceinture_id' => $request->ceinture_id,
                'count' => $count,
                'examen_id' => $examenId
            ]);
            
            DB::commit();
            
            return redirect()->route('admin.ceintures.index')
                           ->with('success', "✅ {$ceinture->nom} attribuée à {$count} membre(s) avec succès !");
            
        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('Erreur attribution masse', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            return back()->withErrors(['error' => 'Erreur : ' . $e->getMessage()]);
        }
    }

    /**
     * Modifier une attribution
     */
    public function edit(UserCeinture $userCeinture): View
    {
        if (auth()->user()->hasRole('admin_ecole')) {
            abort_unless($userCeinture->ecole_id === auth()->user()->ecole_id, 403);
        }

        $ceintures = Ceinture::orderBy('ordre')->get();
        
        return view('admin.ceintures.edit', compact('userCeinture', 'ceintures'));
    }

    /**
     * Mettre à jour attribution
     */
    public function update(Request $request, UserCeinture $userCeinture): RedirectResponse
    {
        // VALIDATION IDENTIQUE À L'ANCIEN
        $request->validate([
            'ceinture_id' => 'required|exists:ceintures,id',
            'date_obtention' => 'required|date',
            'examinateur' => 'nullable|string|max:255',
            'commentaires' => 'nullable|string|max:1000',
            'valide' => 'nullable|boolean',
        ]);
        
        if (auth()->user()->hasRole('admin_ecole')) {
            abort_unless($userCeinture->ecole_id === auth()->user()->ecole_id, 403);
        }

        try {
            DB::beginTransaction();

            $userCeinture->update($request->only([
                'ceinture_id', 'date_obtention', 'examinateur', 'commentaires', 'valide'
            ]));

            Log::info('Attribution ceinture modifiée', [
                'user_id' => auth()->id(),
                'attribution_id' => $userCeinture->id
            ]);

            DB::commit();
            
            return redirect()->route('admin.ceintures.index')
                ->with('success', 'Attribution modifiée avec succès');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Erreur modification attribution', [
                'user_id' => auth()->id(),
                'attribution_id' => $userCeinture->id,
                'error' => $e->getMessage()
            ]);

            return back()->withErrors(['error' => 'Erreur lors de la modification']);
        }
    }

    /**
     * Supprimer attribution
     */
    public function destroy(UserCeinture $userCeinture): RedirectResponse
    {
        if (auth()->user()->hasRole('admin_ecole')) {
            abort_unless($userCeinture->ecole_id === auth()->user()->ecole_id, 403);
        }

        try {
            $userName = $userCeinture->user->name;
            $ceintureName = $userCeinture->ceinture->nom;

            Log::info('Attribution ceinture supprimée', [
                'user_id' => auth()->id(),
                'attribution_id' => $userCeinture->id,
                'membre_name' => $userName
            ]);
            
            $userCeinture->delete();
            
            return redirect()->route('admin.ceintures.index')
                ->with('success', "Attribution {$ceintureName} supprimée pour {$userName}");

        } catch (\Exception $e) {
            Log::error('Erreur suppression attribution', [
                'user_id' => auth()->id(),
                'attribution_id' => $userCeinture->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('admin.ceintures.index')
                ->with('error', 'Erreur lors de la suppression');
        }
    }

    /**
     * Helpers privés - IDENTIQUES À L'ANCIEN
     */
    private function getMembresForUser()
    {
        $query = User::with(['ecole', 'userCeintures.ceinture'])->orderBy('name');
        
        // Multi-tenant filtering
        if (auth()->user()->hasRole('admin_ecole')) {
            $query->where('ecole_id', auth()->user()->ecole_id);
        }
        
        return $query->get();
    }

    private function getStats(): array
    {
        $baseQuery = UserCeinture::query();
        
        if (auth()->user()->hasRole('admin_ecole')) {
            $baseQuery->where('ecole_id', auth()->user()->ecole_id);
        }
        
        return [
            'total_attributions' => $baseQuery->count(),
            'cette_semaine' => $baseQuery->clone()->where('date_obtention', '>=', now()->subDays(7))->count(),
            'ce_mois' => $baseQuery->clone()->where('date_obtention', '>=', now()->subDays(30))->count(),
            'cette_annee' => $baseQuery->clone()->whereYear('date_obtention', now()->year)->count(),
        ];
    }
}
