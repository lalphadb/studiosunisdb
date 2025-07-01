<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserCeinture;
use App\Models\Ceinture;
use App\Models\User;
use App\Http\Requests\CeintureAttributionRequest;
use App\Http\Requests\CeintureAttributionMasseRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CeintureController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('can:viewAny,App\Models\UserCeinture', only: ['index']),
            new Middleware('can:create,App\Models\UserCeinture', only: ['create', 'store', 'createMasse', 'storeMasse']),
            new Middleware('can:update,userCeinture', only: ['edit', 'update']),
            new Middleware('can:delete,userCeinture', only: ['destroy']),
        ];
    }

    /**
     * SUIVI PROGRESSION - Liste des attributions de ceintures (CORRIGÉ)
     */
    public function index(Request $request)
    {
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
        
        return view('admin.ceintures.index', compact('attributions', 'ceintures', 'stats', 'membres'));
    }

    /**
     * Attribution individuelle
     */
    public function create(Request $request)
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
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'ceinture_id' => 'required|exists:ceintures,id',
            'date_obtention' => 'required|date',
            'examinateur' => 'nullable|string|max:255',
            'commentaires' => 'nullable|string|max:1000',
            'valide' => 'nullable|boolean',
        ]);
        
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
        
        return redirect()->route('admin.ceintures.index')
            ->with('success', 'Ceinture attribuée avec succès à ' . $user->name);
    }

    /**
     * ATTRIBUTION EN MASSE - Interface
     */
    public function createMasse()
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
    public function storeMasse(Request $request)
    {
        $request->validate([
            'ceinture_id' => 'required|exists:ceintures,id',
            'date_obtention' => 'required|date',
            'notes' => 'nullable|string|max:1000',
            'attributions' => 'required|array|min:1',
            'attributions.*.user_id' => 'required|exists:users,id',
        ]);
        
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
        
        try {
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
            
            DB::commit();
            
            return redirect()->route('admin.ceintures.index')
                           ->with('success', "✅ {$ceinture->nom} attribuée à {$count} membre(s) avec succès !");
            
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Erreur : ' . $e->getMessage()]);
        }
    }

    /**
     * Modifier une attribution
     */
    public function edit(UserCeinture $userCeinture)
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
    public function update(Request $request, UserCeinture $userCeinture)
    {
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

        $userCeinture->update($request->only([
            'ceinture_id', 'date_obtention', 'examinateur', 'commentaires', 'valide'
        ]));
        
        return redirect()->route('admin.ceintures.index')
            ->with('success', 'Attribution modifiée avec succès');
    }

    /**
     * Supprimer attribution
     */
    public function destroy(UserCeinture $userCeinture)
    {
        if (auth()->user()->hasRole('admin_ecole')) {
            abort_unless($userCeinture->ecole_id === auth()->user()->ecole_id, 403);
        }

        $userName = $userCeinture->user->name;
        $ceintureName = $userCeinture->ceinture->nom;
        
        $userCeinture->delete();
        
        return redirect()->route('admin.ceintures.index')
            ->with('success', "Attribution {$ceintureName} supprimée pour {$userName}");
    }

    /**
     * Helpers privés
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

    private function getStats()
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
