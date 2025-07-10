<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Models\Seminaire;
use App\Models\InscriptionSeminaire;
use App\Http\Requests\Admin\SeminaireRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\Eloquent\Builder;

class SeminaireController extends BaseAdminController
{
    public function index(Request $request): View
    {
        try {
            $query = collect([]); // Simuler pour le moment
            
            $this->logAdminAction('view_seminaires_list', 'seminaires');
            
            return $this->adminView('admin.seminaires.index', [
                'seminaires' => $query,
                'filters' => $this->getFilters(),
                'stats' => $this->getSeminairesStats()
            ]);

        } catch (\Exception $e) {
            return $this->handleException($e, 'seminaires_index');
        }
    }

    public function create(): View
    {
        try {
            $this->logAdminAction('access_seminaire_create', 'seminaires');
            
            return $this->adminView('admin.seminaires.create', [
                'ecoles' => $this->getEcolesForUser(),
                'types' => $this->getTypesSeminaire(),
                'niveaux' => $this->getNiveaux()
            ]);

        } catch (\Exception $e) {
            return $this->handleException($e, 'seminaire_create_form');
        }
    }

    public function store(Request $request): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'nom' => 'required|string|max:255',
                'description' => 'nullable|string',
                'date_debut' => 'required|date',
                'date_fin' => 'required|date|after_or_equal:date_debut',
                'prix' => 'required|numeric|min:0',
                'instructeur' => 'nullable|string|max:255',
                'lieu' => 'nullable|string|max:255',
                'type' => 'required|in:technique,kata,competition,arbitrage',
                'niveau' => 'required|in:debutant,intermediaire,avance,tous_niveaux'
            ]);
            
            if (!$this->isSuperAdmin() && !isset($validated['ecole_id'])) {
                $validated['ecole_id'] = $this->getUserEcoleId();
            }
            
            $validated['processed_by_user_id'] = $this->getAuthUser()->id;
            
            $this->logAdminAction('create_seminaire', 'seminaires', null, [
                'data' => $validated
            ]);
            
            return $this->redirectWithSuccess(
                'Séminaire créé avec succès',
                'admin.seminaires.index'
            );
            
        } catch (\Exception $e) {
            return $this->handleException($e, 'seminaire_creation');
        }
    }

    public function show(string $id): View
    {
        try {
            $this->logAdminAction('view_seminaire', 'seminaires', (int)$id);
            
            return $this->adminView('admin.seminaires.show', [
                'seminaire_id' => $id,
                'inscriptions' => []
            ]);

        } catch (\Exception $e) {
            return $this->handleException($e, 'seminaire_show');
        }
    }

    public function edit(string $id): View
    {
        try {
            $this->logAdminAction('access_seminaire_edit', 'seminaires', (int)$id);
            
            return $this->adminView('admin.seminaires.edit', [
                'seminaire_id' => $id,
                'ecoles' => $this->getEcolesForUser(),
                'types' => $this->getTypesSeminaire(),
                'niveaux' => $this->getNiveaux()
            ]);

        } catch (\Exception $e) {
            return $this->handleException($e, 'seminaire_edit_form');
        }
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'nom' => 'required|string|max:255',
                'description' => 'nullable|string',
                'date_debut' => 'required|date',
                'date_fin' => 'required|date|after_or_equal:date_debut',
                'prix' => 'required|numeric|min:0',
                'instructeur' => 'nullable|string|max:255',
                'lieu' => 'nullable|string|max:255',
                'type' => 'required|in:technique,kata,competition,arbitrage',
                'niveau' => 'required|in:debutant,intermediaire,avance,tous_niveaux'
            ]);
            
            $this->logAdminAction('update_seminaire', 'seminaires', (int)$id, [
                'updated' => $validated
            ]);
            
            return $this->redirectWithSuccess(
                'Séminaire mis à jour avec succès',
                'admin.seminaires.index'
            );
            
        } catch (\Exception $e) {
            return $this->handleException($e, 'seminaire_update');
        }
    }

    public function destroy(string $id): RedirectResponse
    {
        try {
            $this->logAdminAction('delete_seminaire', 'seminaires', (int)$id);
            
            return $this->redirectWithSuccess('Séminaire supprimé avec succès');
            
        } catch (\Exception $e) {
            return $this->handleException($e, 'seminaire_deletion');
        }
    }

    public function inscriptions(string $id): View
    {
        try {
            $this->logAdminAction('view_seminaire_inscriptions', 'seminaires', (int)$id);
            
            return $this->adminView('admin.seminaires.inscriptions', [
                'seminaire_id' => $id,
                'inscriptions' => []
            ]);

        } catch (\Exception $e) {
            return $this->handleException($e, 'seminaire_inscriptions');
        }
    }

    protected function applySearchFilter(Builder $query, string $search): Builder
    {
        return $query->where(function($q) use ($search) {
            $q->where('titre', 'like', '%' . $search . '%')
              ->orWhere('description', 'like', '%' . $search . '%')
              ->orWhere('instructeur', 'like', '%' . $search . '%')
              ->orWhere('lieu', 'like', '%' . $search . '%');
        });
    }

    private function getFilters(): array
    {
        return [
            'ecoles' => $this->getEcolesForUser(),
            'types' => $this->getTypesSeminaire(),
            'statuses' => ['planifie', 'ouvert', 'complet', 'termine', 'annule'],
            'niveaux' => $this->getNiveaux()
        ];
    }

    private function getTypesSeminaire(): array
    {
        return [
            'technique' => 'Technique',
            'kata' => 'Kata',
            'competition' => 'Compétition',
            'arbitrage' => 'Arbitrage'
        ];
    }

    private function getNiveaux(): array
    {
        return [
            'debutant' => 'Débutant',
            'intermediaire' => 'Intermédiaire',
            'avance' => 'Avancé',
            'tous_niveaux' => 'Tous niveaux'
        ];
    }

    private function getSeminairesStats(): array
    {
        try {
            // Pour le moment, statistiques simulées
            return [
                'total' => 0,
                'a_venir' => 0,
                'participants' => 0,
                'ce_mois' => 0
            ];
        } catch (\Exception $e) {
            return [
                'total' => 0,
                'a_venir' => 0,
                'participants' => 0,
                'ce_mois' => 0
            ];
        }
    }
}
