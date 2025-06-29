#!/bin/bash
# Mise à jour sécurisée du PaiementController - StudiosDB v5.7.1

echo "=== MISE À JOUR SÉCURISÉE PAIEMENTCONTROLLER ==="

# Mise à jour du controller avec version sécurisée
cat > app/Http/Controllers/Admin/PaiementController.php << 'CONTROLLER_EOF'
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePaiementRequest;
use App\Http\Requests\Admin\UpdatePaiementRequest;
use App\Http\Requests\Admin\BulkValidatePaiementRequest;
use App\Models\Paiement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class PaiementController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('can:viewAny,App\Models\Paiement', only: ['index']),
            new Middleware('can:view,paiement', only: ['show']),
            new Middleware('can:create,App\Models\Paiement', only: ['create', 'store']),
            new Middleware('can:update,paiement', only: ['edit', 'update', 'quickValidate']),
            new Middleware('can:delete,paiement', only: ['destroy']),
            new Middleware('can:bulkUpdate,App\Models\Paiement', only: ['bulkValidate']),
        ];
    }

    public function index(Request $request): View
    {
        $query = Paiement::with(['user', 'ecole']);

        // Filtrage multi-tenant
        if (auth()->user()->hasRole('admin_ecole')) {
            $query->where('ecole_id', auth()->user()->ecole_id);
        }

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('reference_interne', 'like', "%{$search}%")
                  ->orWhere('montant', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filtrage par statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        // Tri par défaut : les paiements en attente en premier
        $paiements = $query->orderByRaw("CASE WHEN statut = 'attente' THEN 0 ELSE 1 END")
                          ->orderBy('created_at', 'desc')
                          ->paginate(50);

        return view('admin.paiements.index', compact('paiements'));
    }

    public function create(): View
    {
        $users = User::select('id', 'name', 'ecole_id');
        
        if (auth()->user()->hasRole('admin_ecole')) {
            $users->where('ecole_id', auth()->user()->ecole_id);
        }
        
        $users = $users->orderBy('name')->get();

        return view('admin.paiements.create', compact('users'));
    }

    public function store(StorePaiementRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        
        // Auto-assignation ecole_id pour admin_ecole
        if (auth()->user()->hasRole('admin_ecole')) {
            $validated['ecole_id'] = auth()->user()->ecole_id;
        }

        // Auto-génération référence interne si vide avec préfixe école
        if (empty($validated['reference_interne'])) {
            $prefix = auth()->user()->ecole_id ? 'PAY-ECO' . auth()->user()->ecole_id : 'PAY';
            $validated['reference_interne'] = $prefix . '-' . strtoupper(uniqid());
        }

        $paiement = Paiement::create($validated);

        return redirect()
            ->route('admin.paiements.index')
            ->with('success', 'Paiement créé avec succès.');
    }

    public function show(Paiement $paiement): View
    {
        $paiement->load(['user', 'ecole']);
        return view('admin.paiements.show', compact('paiement'));
    }

    public function edit(Paiement $paiement): View
    {
        $users = User::select('id', 'name', 'ecole_id');
        
        if (auth()->user()->hasRole('admin_ecole')) {
            $users->where('ecole_id', auth()->user()->ecole_id);
        }
        
        $users = $users->orderBy('name')->get();

        return view('admin.paiements.edit', compact('paiement', 'users'));
    }

    public function update(UpdatePaiementRequest $request, Paiement $paiement): RedirectResponse
    {
        $paiement->update($request->validated());

        return redirect()
            ->route('admin.paiements.index')
            ->with('success', 'Paiement mis à jour avec succès.');
    }

    public function destroy(Paiement $paiement): RedirectResponse
    {
        $paiement->delete();

        return redirect()
            ->route('admin.paiements.index')
            ->with('success', 'Paiement supprimé avec succès.');
    }

    /**
     * Validation de masse des paiements
     */
    public function bulkValidate(BulkValidatePaiementRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $paiementIds = $validated['paiement_ids'];
        $action = $validated['action'];

        try {
            DB::beginTransaction();

            $query = Paiement::whereIn('id', $paiementIds);
            
            // Filtrage multi-tenant strict
            if (auth()->user()->hasRole('admin_ecole')) {
                $query->where('ecole_id', auth()->user()->ecole_id);
            }

            $count = 0;

            switch ($action) {
                case 'valider':
                    $count = $query->where('statut', 'attente')->update([
                        'statut' => 'paye',
                        'date_paiement' => now(),
                        'updated_at' => now()
                    ]);
                    break;

                case 'attente':
                    $count = $query->where('statut', 'paye')->update([
                        'statut' => 'attente',
                        'date_paiement' => null,
                        'updated_at' => now()
                    ]);
                    break;

                case 'supprimer':
                    $count = $query->delete();
                    break;
            }

            DB::commit();

            $actionText = match($action) {
                'valider' => 'validés',
                'attente' => 'remis en attente',
                'supprimer' => 'supprimés',
            };

            return redirect()
                ->route('admin.paiements.index')
                ->with('success', "{$count} paiement(s) {$actionText} avec succès.");

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->route('admin.paiements.index')
                ->with('error', 'Erreur lors de l\'action de masse : ' . $e->getMessage());
        }
    }

    /**
     * Validation rapide individuelle via AJAX
     */
    public function quickValidate(Request $request, Paiement $paiement)
    {
        // Vérification supplémentaire des permissions
        if (!Gate::allows('update', $paiement)) {
            return response()->json([
                'success' => false,
                'message' => 'Action non autorisée'
            ], 403);
        }

        // Vérification multi-tenant stricte
        if (auth()->user()->hasRole('admin_ecole') && $paiement->ecole_id !== auth()->user()->ecole_id) {
            return response()->json([
                'success' => false,
                'message' => 'Paiement non accessible pour cette école'
            ], 403);
        }

        try {
            $newStatut = $paiement->statut === 'attente' ? 'paye' : 'attente';
            
            $paiement->update([
                'statut' => $newStatut,
                'date_paiement' => $newStatut === 'paye' ? now() : null,
            ]);

            return response()->json([
                'success' => true,
                'new_statut' => $newStatut,
                'message' => 'Statut mis à jour avec succès',
                'date_paiement' => $newStatut === 'paye' ? now()->format('d/m/Y') : null
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour : ' . $e->getMessage()
            ], 500);
        }
    }
}
CONTROLLER_EOF

echo "✅ PaiementController mis à jour avec sécurité renforcée"
echo "🔒 Améliorations appliquées :"
echo "   ✓ Import View ajouté"
echo "   ✓ Gate::allows() pour vérifications supplémentaires"
echo "   ✓ Validation multi-tenant stricte"
echo "   ✓ Génération référence avec préfixe école"
echo "   ✓ Gestion d'erreurs détaillée"
echo "   ✓ Date de paiement retournée en JSON"
