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
            new Middleware('can:bulkUpdate,App\Models\Paiement', only: ['bulkValidate', 'quickBulkValidate']),
        ];
    }

    public function index(Request $request): View
    {
        $query = Paiement::with(['user', 'ecole', 'processedBy']);

        // Filtrage multi-tenant
        if (auth()->user()->hasRole('admin_ecole')) {
            $query->where('ecole_id', auth()->user()->ecole_id);
        }

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('reference_interne', 'like', "%{$search}%")
                  ->orWhere('reference_interac', 'like', "%{$search}%")
                  ->orWhere('montant', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('nom_expediteur', 'like', "%{$search}%")
                  ->orWhere('email_expediteur', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filtrage par statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        // Filtrage par type de paiement
        if ($request->filled('type_paiement')) {
            $query->where('type_paiement', $request->type_paiement);
        }

        // Filtrage par motif
        if ($request->filled('motif')) {
            $query->where('motif', $request->motif);
        }

        // Tri par défaut : les paiements en attente en premier
        $paiements = $query->orderByRaw("CASE WHEN statut = 'en_attente' THEN 0 WHEN statut = 'recu' THEN 1 ELSE 2 END")
                          ->orderBy('created_at', 'desc')
                          ->paginate(25); // Réduit à 25 pour améliorer la performance

        return view('admin.paiements.index', compact('paiements'));
    }

    public function create(): View
    {
        $users = User::select('id', 'name', 'email', 'ecole_id');
        
        if (auth()->user()->hasRole('admin_ecole')) {
            $users->where('ecole_id', auth()->user()->ecole_id);
        }
        
        $users = $users->orderBy('name')->get();

        return view('admin.paiements.create', compact('users'));
    }

    public function store(StorePaiementRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        
        // Auto-assignation ecole_id pour admin_ecole ou depuis le user sélectionné
        if (auth()->user()->hasRole('admin_ecole')) {
            $validated['ecole_id'] = auth()->user()->ecole_id;
        } else {
            // Pour superadmin, récupérer l'école du user sélectionné
            $user = User::findOrFail($validated['user_id']);
            $validated['ecole_id'] = $user->ecole_id;
        }

        // Auto-génération référence interne si vide
        if (empty($validated['reference_interne'])) {
            $prefix = $validated['ecole_id'] ? 'PAY-ECO' . $validated['ecole_id'] : 'PAY';
            $validated['reference_interne'] = $prefix . '-' . strtoupper(uniqid());
        }

        // Calculer montant_net
        $validated['montant_net'] = $validated['montant'] - ($validated['frais'] ?? 0);

        // Assigner l'utilisateur qui traite le paiement
        $validated['processed_by_user_id'] = auth()->id();

        // Gérer les dates selon le statut
        if ($validated['statut'] === 'recu') {
            $validated['date_reception'] = now();
        } elseif ($validated['statut'] === 'valide') {
            $validated['date_reception'] = now();
            $validated['date_validation'] = now();
        }

        $paiement = Paiement::create($validated);

        return redirect()
            ->route('admin.paiements.index')
            ->with('success', 'Paiement créé avec succès.');
    }

    public function show(Paiement $paiement): View
    {
        $paiement->load(['user', 'ecole', 'processedBy']);
        return view('admin.paiements.show', compact('paiement'));
    }

    public function edit(Paiement $paiement): View
    {
        $users = User::select('id', 'name', 'email', 'ecole_id');
        
        if (auth()->user()->hasRole('admin_ecole')) {
            $users->where('ecole_id', auth()->user()->ecole_id);
        }
        
        $users = $users->orderBy('name')->get();

        return view('admin.paiements.edit', compact('paiement', 'users'));
    }

    public function update(UpdatePaiementRequest $request, Paiement $paiement): RedirectResponse
    {
        $validated = $request->validated();

        // Calculer montant_net
        $validated['montant_net'] = $validated['montant'] - ($validated['frais'] ?? 0);

        // Gérer les transitions de statut
        $oldStatut = $paiement->statut;
        $newStatut = $validated['statut'];

        if ($oldStatut !== $newStatut) {
            switch ($newStatut) {
                case 'recu':
                    if (!$paiement->date_reception) {
                        $validated['date_reception'] = now();
                    }
                    break;
                    
                case 'valide':
                    if (!$paiement->date_reception) {
                        $validated['date_reception'] = now();
                    }
                    if (!$paiement->date_validation) {
                        $validated['date_validation'] = now();
                    }
                    break;
                    
                case 'en_attente':
                    // Retourner en attente - on garde les dates mais on peut les nettoyer si nécessaire
                    break;
            }
        }

        $paiement->update($validated);

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
                    $count = $query->whereIn('statut', ['en_attente', 'recu'])->update([
                        'statut' => 'valide',
                        'date_reception' => DB::raw('COALESCE(date_reception, NOW())'),
                        'date_validation' => now(),
                        'updated_at' => now()
                    ]);
                    break;

                case 'marquer_recu':
                    $count = $query->where('statut', 'en_attente')->update([
                        'statut' => 'recu',
                        'date_reception' => now(),
                        'updated_at' => now()
                    ]);
                    break;

                case 'attente':
                    $count = $query->whereIn('statut', ['recu', 'valide'])->update([
                        'statut' => 'en_attente',
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
                'marquer_recu' => 'marqués comme reçus',
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
     * Validation rapide individuelle via AJAX (legacy - pour compatibilité)
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
            // Cycle de statut : en_attente -> recu -> valide -> en_attente
            $newStatut = match($paiement->statut) {
                'en_attente' => 'recu',
                'recu' => 'valide',
                'valide' => 'en_attente',
                default => 'en_attente'
            };

            $updateData = ['statut' => $newStatut];

            // Gérer les dates selon le nouveau statut
            switch ($newStatut) {
                case 'recu':
                    $updateData['date_reception'] = now();
                    break;
                case 'valide':
                    if (!$paiement->date_reception) {
                        $updateData['date_reception'] = now();
                    }
                    $updateData['date_validation'] = now();
                    break;
            }
            
            $paiement->update($updateData);

            return response()->json([
                'success' => true,
                'new_statut' => $newStatut,
                'message' => 'Statut mis à jour avec succès',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validation rapide groupée via AJAX (optimisation performance)
     */
    public function quickBulkValidate(Request $request)
    {
        $request->validate([
            'paiement_ids' => ['required', 'array', 'min:1', 'max:50'],
            'paiement_ids.*' => ['required', 'integer', 'exists:paiements,id'],
        ]);

        try {
            DB::beginTransaction();

            $paiementIds = $request->paiement_ids;
            $query = Paiement::whereIn('id', $paiementIds);

            // Filtrage multi-tenant strict
            if (auth()->user()->hasRole('admin_ecole')) {
                $query->where('ecole_id', auth()->user()->ecole_id);
            }

            $paiements = $query->get();
            $results = [];

            foreach ($paiements as $paiement) {
                // Vérification des permissions individuelles
                if (!Gate::allows('update', $paiement)) {
                    $results[$paiement->id] = [
                        'success' => false,
                        'message' => 'Action non autorisée'
                    ];
                    continue;
                }

                // Cycle de statut : en_attente -> recu -> valide -> en_attente
                $newStatut = match($paiement->statut) {
                    'en_attente' => 'recu',
                    'recu' => 'valide',
                    'valide' => 'en_attente',
                    default => 'en_attente'
                };

                $updateData = ['statut' => $newStatut];

                // Gérer les dates selon le nouveau statut
                switch ($newStatut) {
                    case 'recu':
                        $updateData['date_reception'] = now();
                        break;
                    case 'valide':
                        if (!$paiement->date_reception) {
                            $updateData['date_reception'] = now();
                        }
                        $updateData['date_validation'] = now();
                        break;
                }

                $paiement->update($updateData);

                $results[$paiement->id] = [
                    'success' => true,
                    'new_statut' => $newStatut,
                    'message' => 'Statut mis à jour avec succès'
                ];
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'results' => $results,
                'message' => 'Validation groupée effectuée avec succès'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la validation groupée : ' . $e->getMessage()
            ], 500);
        }
    }
}
