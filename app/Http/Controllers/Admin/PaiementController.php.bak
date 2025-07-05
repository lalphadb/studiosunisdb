<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\StorePaiementRequest;
use App\Http\Requests\Admin\UpdatePaiementRequest;
use App\Http\Requests\Admin\BulkValidatePaiementRequest;
use App\Models\Paiement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

/**
 * Contrôleur de gestion des paiements
 * 
 * Gère le cycle complet des paiements avec:
 * - Multi-tenant strict par ecole_id
 * - Validation de masse optimisée
 * - Audit trail complet
 * - Interface AJAX pour actions rapides
 */
class PaiementController extends BaseAdminController
{
    /**
     * Initialise le contrôleur avec permissions
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('permission:view_paiements')->only(['index', 'show']);
        $this->middleware('permission:create_paiements')->only(['create', 'store']);
        $this->middleware('permission:edit_paiements')->only(['edit', 'update']);
        $this->middleware('permission:delete_paiements')->only(['destroy']);
    }

    /**
     * Liste des paiements avec filtres avancés
     */
    public function index(Request $request): View
    {
        try {
            $query = Paiement::with(['user', 'ecole', 'processedBy']);

            // Filtrage multi-tenant strict
            if (auth()->user()->hasRole('admin_ecole')) {
                $query->where('ecole_id', auth()->user()->ecole_id);
            }

            // Recherche globale
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

            // Filtres spécialisés
            $this->applyFilters($query, $request);

            // Tri optimisé : priorité aux paiements en attente
            $paiements = $query->orderByRaw("CASE WHEN statut = 'en_attente' THEN 0 WHEN statut = 'recu' THEN 1 ELSE 2 END")
                              ->orderBy('created_at', 'desc')
                              ->paginate(25);

            Log::info('Consultation index paiements', [
                'user_id' => auth()->id(),
                'ecole_id' => auth()->user()->ecole_id,
                'total_paiements' => $paiements->total(),
                'filters' => $request->only(['search', 'statut', 'type_paiement', 'motif'])
            ]);

            return view('pages.admin.paiements.index', compact('paiements'));

        } catch (\Exception $e) {
            Log::error('Erreur index paiements', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return $this->redirectWithError('pages.admin.dashboard', 'Erreur lors du chargement des paiements');
        }
    }

    /**
     * Formulaire création paiement
     */
    public function create(): View
    {
        $users = $this->getMembresForUser();
        return view('pages.admin.paiements.create', compact('users'));
    }

    /**
     * Enregistrer nouveau paiement
     */
    public function store(StorePaiementRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $validated = $request->validated();
            
            // Auto-assignation ecole_id selon le rôle
            $validated['ecole_id'] = $this->getEcoleIdForUser($validated['user_id']);
            
            // Auto-génération référence si nécessaire
            if (empty($validated['reference_interne'])) {
                $validated['reference_interne'] = $this->generateReference($validated['ecole_id']);
            }

            // Calculs automatiques
            $validated['montant_net'] = $validated['montant'] - ($validated['frais'] ?? 0);
            $validated['processed_by_user_id'] = auth()->id();

            // Gestion des dates selon statut
            $this->setDatesForStatus($validated);

            $paiement = Paiement::create($validated);

            Log::info('Paiement créé', [
                'user_id' => auth()->id(),
                'paiement_id' => $paiement->id,
                'montant' => $paiement->montant,
                'statut' => $paiement->statut
            ]);

            DB::commit();

            return $this->redirectWithSuccess('pages.admin.paiements.index', 'Paiement créé avec succès');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Erreur création paiement', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'data' => $request->validated()
            ]);

            return $this->redirectWithError('pages.admin.paiements.create', 'Erreur lors de la création : ' . $e->getMessage());
        }
    }

    /**
     * Afficher détails paiement
     */
    public function show(Paiement $paiement): View
    {
        Gate::authorize('view', $paiement);
        
        $paiement->load(['user', 'ecole', 'processedBy']);
        return view('pages.admin.paiements.show', compact('paiement'));
    }

    /**
     * Formulaire édition paiement
     */
    public function edit(Paiement $paiement): View
    {
        Gate::authorize('update', $paiement);
        
        $users = $this->getMembresForUser();
        return view('pages.admin.paiements.edit', compact('paiement', 'users'));
    }

    /**
     * Mettre à jour paiement
     */
    public function update(UpdatePaiementRequest $request, Paiement $paiement): RedirectResponse
    {
        Gate::authorize('update', $paiement);

        try {
            DB::beginTransaction();

            $validated = $request->validated();
            $validated['montant_net'] = $validated['montant'] - ($validated['frais'] ?? 0);

            // Gestion transitions de statut
            $this->handleStatusTransition($paiement, $validated);

            $paiement->update($validated);

            Log::info('Paiement mis à jour', [
                'user_id' => auth()->id(),
                'paiement_id' => $paiement->id,
                'old_statut' => $paiement->getOriginal('statut'),
                'new_statut' => $paiement->statut
            ]);

            DB::commit();

            return $this->redirectWithSuccess('pages.admin.paiements.index', 'Paiement mis à jour avec succès');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Erreur mise à jour paiement', [
                'user_id' => auth()->id(),
                'paiement_id' => $paiement->id,
                'error' => $e->getMessage()
            ]);

            return $this->redirectWithError('pages.admin.paiements.edit', 'Erreur lors de la mise à jour');
        }
    }

    /**
     * Supprimer paiement
     */
    public function destroy(Paiement $paiement): RedirectResponse
    {
        Gate::authorize('delete', $paiement);

        try {
            Log::info('Paiement supprimé', [
                'user_id' => auth()->id(),
                'paiement_id' => $paiement->id,
                'reference' => $paiement->reference_interne
            ]);

            $paiement->delete();

            return $this->redirectWithSuccess('pages.admin.paiements.index', 'Paiement supprimé avec succès');

        } catch (\Exception $e) {
            Log::error('Erreur suppression paiement', [
                'user_id' => auth()->id(),
                'paiement_id' => $paiement->id,
                'error' => $e->getMessage()
            ]);

            return $this->redirectWithError('pages.admin.paiements.index', 'Erreur lors de la suppression');
        }
    }

    /**
     * Validation de masse des paiements
     */
    public function bulkValidate(BulkValidatePaiementRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $validated = $request->validated();
            $result = $this->processBulkAction($validated['paiement_ids'], $validated['action']);

            DB::commit();

            Log::info('Action masse paiements', [
                'user_id' => auth()->id(),
                'action' => $validated['action'],
                'count' => $result['count']
            ]);

            return $this->redirectWithSuccess('pages.admin.paiements.index', $result['message']);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Erreur action masse paiements', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            return $this->redirectWithError('pages.admin.paiements.index', 'Erreur lors de l\'action de masse');
        }
    }

    /**
     * Validation rapide AJAX
     */
    public function quickValidate(Request $request, Paiement $paiement): JsonResponse
    {
        Gate::authorize('update', $paiement);

        try {
            $result = $this->cyclePaymentStatus($paiement);

            Log::info('Validation rapide paiement', [
                'user_id' => auth()->id(),
                'paiement_id' => $paiement->id,
                'new_statut' => $result['new_statut']
            ]);

            return $this->successResponse('Statut mis à jour avec succès', $result);

        } catch (\Exception $e) {
            Log::error('Erreur validation rapide', [
                'user_id' => auth()->id(),
                'paiement_id' => $paiement->id,
                'error' => $e->getMessage()
            ]);

            return $this->errorResponse('Erreur lors de la mise à jour', [], 500);
        }
    }

    /**
     * MÉTHODES PRIVÉES - LOGIQUE MÉTIER
     */

    private function applyFilters($query, Request $request): void
    {
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('type_paiement')) {
            $query->where('type_paiement', $request->type_paiement);
        }

        if ($request->filled('motif')) {
            $query->where('motif', $request->motif);
        }
    }

    private function getMembresForUser()
    {
        $query = User::select('id', 'name', 'email', 'ecole_id')->orderBy('name');
        
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

    private function generateReference(int $ecoleId): string
    {
        $prefix = $ecoleId ? 'PAY-ECO' . $ecoleId : 'PAY';
        return $prefix . '-' . strtoupper(uniqid());
    }

    private function setDatesForStatus(array &$validated): void
    {
        switch ($validated['statut']) {
            case 'recu':
                $validated['date_reception'] = now();
                break;
            case 'valide':
                $validated['date_reception'] = now();
                $validated['date_validation'] = now();
                break;
        }
    }

    private function handleStatusTransition(Paiement $paiement, array &$validated): void
    {
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
            }
        }
    }

    private function processBulkAction(array $paiementIds, string $action): array
    {
        $query = Paiement::whereIn('id', $paiementIds);
        
        if (auth()->user()->hasRole('admin_ecole')) {
            $query->where('ecole_id', auth()->user()->ecole_id);
        }

        $count = match($action) {
            'valider' => $query->whereIn('statut', ['en_attente', 'recu'])->update([
                'statut' => 'valide',
                'date_reception' => DB::raw('COALESCE(date_reception, NOW())'),
                'date_validation' => now(),
                'updated_at' => now()
            ]),
            'marquer_recu' => $query->where('statut', 'en_attente')->update([
                'statut' => 'recu',
                'date_reception' => now(),
                'updated_at' => now()
            ]),
            'attente' => $query->whereIn('statut', ['recu', 'valide'])->update([
                'statut' => 'en_attente',
                'updated_at' => now()
            ]),
            'supprimer' => $query->delete(),
            default => throw new \InvalidArgumentException('Action non supportée')
        };

        $actionText = match($action) {
            'valider' => 'validés',
            'marquer_recu' => 'marqués comme reçus',
            'attente' => 'remis en attente',
            'supprimer' => 'supprimés',
        };

        return [
            'count' => $count,
            'message' => "{$count} paiement(s) {$actionText} avec succès"
        ];
    }

    private function cyclePaymentStatus(Paiement $paiement): array
    {
        $newStatut = match($paiement->statut) {
            'en_attente' => 'recu',
            'recu' => 'valide',
            'valide' => 'en_attente',
            default => 'en_attente'
        };

        $updateData = ['statut' => $newStatut];

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

        return [
            'new_statut' => $newStatut,
            'message' => 'Statut mis à jour avec succès'
        ];
    }
}
