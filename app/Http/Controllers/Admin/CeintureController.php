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
     * SUIVI PROGRESSION - Liste des attributions de ceintures
     */
    public function index(Request $request)
    {
        $query = UserCeinture::with(['user', 'ceinture', 'instructeur'])
            ->orderBy('date_obtention', 'desc');
        
        // Multi-tenant: Admin d'école voit ses attributions
        if (auth()->user()->hasRole('admin_ecole')) {
            $query->whereHas('user', function($q) {
                $q->where('ecole_id', auth()->user()->ecole_id);
            });
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
        
        return view('admin.ceintures.index', compact('attributions', 'ceintures', 'stats'));
    }

    /**
     * Attribution individuelle - CORRECTION: s'assurer que les données sont chargées
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
        
        // CORRECTION: S'assurer que les ceintures sont chargées
        $ceintures = Ceinture::orderBy('ordre')->get();
        
        // Debug si pas de ceintures
        if ($ceintures->isEmpty()) {
            \Log::warning('Aucune ceinture trouvée dans la base de données');
        }
        
        $membres = $this->getMembresForUser();
        
        // Debug si pas de membres
        if ($membres->isEmpty()) {
            \Log::warning('Aucun membre trouvé pour l\'utilisateur connecté');
        }
        
        return view('admin.ceintures.create', compact('user', 'ceintures', 'membres'));
    }

    /**
     * Enregistrer attribution individuelle
     */
    public function store(CeintureAttributionRequest $request)
    {
        $validated = $request->validated();
        
        // Ajouter données auto
        $validated['instructeur_id'] = auth()->id();
        
        // Déterminer l'école
        $user = User::find($validated['user_id']);
        $validated['ecole_id'] = auth()->user()->hasRole('admin_ecole') 
            ? auth()->user()->ecole_id 
            : $user->ecole_id;
        
        $attribution = UserCeinture::create($validated);
        
        return redirect()->route('admin.ceintures.index')
            ->with('success', 'Ceinture attribuée avec succès à ' . $attribution->user->name);
    }

    /**
     * ATTRIBUTION EN MASSE - APRÈS EXAMEN DE GROUPE
     */
    public function createMasse()
    {
        $ceintures = Ceinture::orderBy('ordre')->get();
        $membres = $this->getMembresForUser();
        
        return view('admin.ceintures.create-masse', compact('ceintures', 'membres'));
    }

    /**
     * ATTRIBUTION EN MASSE - Traitement après examen
     */
    public function storeMasse(CeintureAttributionMasseRequest $request)
    {
        $validated = $request->validated();
        $examenId = 'EXAM_' . Str::uuid();
        $count = 0;
        
        foreach ($validated['attributions'] as $attribution) {
            if (isset($attribution['user_id'])) {
                $user = User::find($attribution['user_id']);
                
                UserCeinture::create([
                    'user_id' => $attribution['user_id'],
                    'ceinture_id' => $validated['ceinture_id'],
                    'date_obtention' => $validated['date_obtention'],
                    'ecole_id' => auth()->user()->hasRole('admin_ecole') 
                        ? auth()->user()->ecole_id 
                        : $user->ecole_id,
                    'instructeur_id' => auth()->id(),
                    'examen_id' => $examenId,
                    'notes' => $validated['notes'] ?? null,
                    'valide' => true,
                ]);
                $count++;
            }
        }
        
        return redirect()->route('admin.ceintures.index')
            ->with('success', "Ceinture {$validated['ceinture_nom']} attribuée à {$count} membres avec succès !");
    }

    /**
     * Modifier une attribution
     */
    public function edit(UserCeinture $userCeinture)
    {
        if (auth()->user()->hasRole('admin_ecole')) {
            abort_unless($userCeinture->user->ecole_id === auth()->user()->ecole_id, 403);
        }

        $ceintures = Ceinture::orderBy('ordre')->get();
        
        return view('admin.ceintures.edit', compact('userCeinture', 'ceintures'));
    }

    /**
     * Mettre à jour attribution
     */
    public function update(CeintureAttributionRequest $request, UserCeinture $userCeinture)
    {
        if (auth()->user()->hasRole('admin_ecole')) {
            abort_unless($userCeinture->user->ecole_id === auth()->user()->ecole_id, 403);
        }

        $userCeinture->update($request->validated());
        
        return redirect()->route('admin.ceintures.index')
            ->with('success', 'Attribution modifiée avec succès');
    }

    /**
     * Supprimer attribution
     */
    public function destroy(UserCeinture $userCeinture)
    {
        if (auth()->user()->hasRole('admin_ecole')) {
            abort_unless($userCeinture->user->ecole_id === auth()->user()->ecole_id, 403);
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
        $query = User::whereHas('roles', function($q) {
            $q->where('name', 'membre');
        })->with(['ecole', 'userCeintures.ceinture'])->orderBy('name');
        
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
            $baseQuery->whereHas('user', function($q) {
                $q->where('ecole_id', auth()->user()->ecole_id);
            });
        }
        
        return [
            'total_attributions' => $baseQuery->count(),
            'cette_semaine' => $baseQuery->clone()->where('date_obtention', '>=', now()->subDays(7))->count(),
            'ce_mois' => $baseQuery->clone()->where('date_obtention', '>=', now()->subDays(30))->count(),
            'cette_annee' => $baseQuery->clone()->whereYear('date_obtention', now()->year)->count(),
        ];
    }
}
