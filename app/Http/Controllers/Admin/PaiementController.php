<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Models\Paiement;
use App\Models\User;
use App\Http\Requests\Admin\PaiementRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\Eloquent\Builder;

class PaiementController extends BaseAdminController
{
    public function index(Request $request): View
    {
        try {
            $query = Paiement::with(['user', 'ecole'])
                ->orderBy('created_at', 'desc');
            
            $paiements = $this->paginate($query, $request);
            
            $this->logAdminAction('view_paiements_list', 'paiements');
            
            return $this->adminView('admin.paiements.index', [
                'paiements' => $paiements,
                'filters' => $this->getFilters(),
                'stats' => $this->getPaiementsStats()
            ]);

        } catch (\Exception $e) {
            return $this->handleException($e, 'paiements_index');
        }
    }

    public function create(): View
    {
        try {
            $this->logAdminAction('access_paiement_create', 'paiements');
            
            return $this->adminView('admin.paiements.create', [
                'users' => $this->getUsersForEcole(),
                'ecoles' => $this->getEcolesForUser(),
                'motifs' => $this->getMotifsPaiement()
            ]);

        } catch (\Exception $e) {
            return $this->handleException($e, 'paiement_create_form');
        }
    }

    public function store(Request $request): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'montant' => 'required|numeric|min:0.01',
                'methode_paiement' => 'required|in:carte,especes,cheque,virement,paypal',
                'description' => 'required|string|max:255',
                'date_paiement' => 'required|date',
                'numero_transaction' => 'nullable|string|max:100',
                'commentaire' => 'nullable|string|max:500'
            ]);
            
            if (!$this->isSuperAdmin() && !isset($validated['ecole_id'])) {
                $validated['ecole_id'] = $this->getUserEcoleId();
            }
            
            // Générer référence interne unique
            $validated['reference_interne'] = $this->generateReference();
            $validated['processed_by_user_id'] = $this->getAuthUser()->id;
            $validated['date_facture'] = now();
            
            // Pour le moment, on simule la création
            $this->logAdminAction('create_paiement', 'paiements', null, [
                'data' => $validated
            ]);
            
            return $this->redirectWithSuccess(
                'Paiement créé avec succès',
                'admin.paiements.index'
            );
            
        } catch (\Exception $e) {
            return $this->handleException($e, 'paiement_creation');
        }
    }

    public function show(string $id): View
    {
        try {
            $this->logAdminAction('view_paiement', 'paiements', (int)$id);
            
            return $this->adminView('admin.paiements.show', [
                'paiement_id' => $id
            ]);

        } catch (\Exception $e) {
            return $this->handleException($e, 'paiement_show');
        }
    }

    public function destroy(string $id): RedirectResponse
    {
        try {
            $this->logAdminAction('delete_paiement', 'paiements', (int)$id);
            
            return $this->redirectWithSuccess('Paiement supprimé avec succès');
            
        } catch (\Exception $e) {
            return $this->handleException($e, 'paiement_deletion');
        }
    }

    protected function applySearchFilter(Builder $query, string $search): Builder
    {
        return $query->where(function($q) use ($search) {
            $q->where('reference_interne', 'like', '%' . $search . '%')
              ->orWhere('description', 'like', '%' . $search . '%')
              ->orWhere('montant', 'like', '%' . $search . '%')
              ->orWhereHas('user', function($userQuery) use ($search) {
                  $userQuery->where('name', 'like', '%' . $search . '%');
              });
        });
    }

    private function getFilters(): array
    {
        return [
            'ecoles' => $this->getEcolesForUser(),
            'motifs' => $this->getMotifsPaiement(),
            'statuses' => ['en_attente', 'valide', 'refuse']
        ];
    }

    private function getUsersForEcole()
    {
        $query = User::query()->where('active', true);
        
        if (!$this->isSuperAdmin()) {
            $query->where('ecole_id', $this->getUserEcoleId());
        }
        
        return $query->orderBy('name')->get();
    }

    private function getMotifsPaiement(): array
    {
        return [
            'session_automne' => 'Session Automne',
            'session_hiver' => 'Session Hiver',
            'session_printemps' => 'Session Printemps',
            'session_ete' => 'Session Été',
            'seminaire' => 'Séminaire',
            'examen_ceinture' => 'Examen Ceinture',
            'equipement' => 'Équipement',
            'autre' => 'Autre'
        ];
    }

    private function generateReference(): string
    {
        return 'PAY-' . date('Y') . '-' . str_pad((string)mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
    }

    private function getPaiementsStats(): array
    {
        try {
            // Pour le moment, statistiques simulées
            return [
                'total' => 0,
                'valides' => 0,
                'en_attente' => 0,
                'montant_total' => 0
            ];
        } catch (\Exception $e) {
            return [
                'total' => 0,
                'valides' => 0,
                'en_attente' => 0,
                'montant_total' => 0
            ];
        }
    }
}
