<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Models\Presence;
use App\Models\User;
use App\Models\Cours;
use App\Http\Requests\Admin\PresenceRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\Eloquent\Builder;

class PresenceController extends BaseAdminController
{
    public function index(Request $request): View
    {
        try {
            $filters = [
                'date_debut' => $request->get('date_debut'),
                'date_fin' => $request->get('date_fin'),
                'cours_id' => $request->get('cours_id'),
                'user_id' => $request->get('user_id')
            ];

            $this->logAdminAction('view_presences_list', 'presences');
            
            return $this->adminView('admin.presences.index', [
                'presences' => [],
                'filters' => $this->getFilters()
            ]);

        } catch (\Exception $e) {
            return $this->handleException($e, 'presences_index');
        }
    }

    public function create(): View
    {
        try {
            $this->logAdminAction('access_presence_create', 'presences');
            
            return $this->adminView('admin.presences.create', [
                'users' => $this->getUsersForEcole(),
                'cours' => $this->getCoursForEcole(),
                'ecoles' => $this->getEcolesForUser()
            ]);

        } catch (\Exception $e) {
            return $this->handleException($e, 'presence_create_form');
        }
    }

    public function store(Request $request): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'cours_id' => 'required|exists:cours,id',
                'date_presence' => 'required|date',
                'statut' => 'required|in:present,absent,excuse',
                'commentaire' => 'nullable|string|max:500'
            ]);
            
            if (!$this->isSuperAdmin() && !isset($validated['ecole_id'])) {
                $validated['ecole_id'] = $this->getUserEcoleId();
            }
            
            $this->logAdminAction('create_presence', 'presences', null, [
                'data' => $validated
            ]);
            
            return $this->redirectWithSuccess(
                'Présence enregistrée avec succès',
                'admin.presences.index'
            );
            
        } catch (\Exception $e) {
            return $this->handleException($e, 'presence_creation');
        }
    }

    public function destroy(string $id): RedirectResponse
    {
        try {
            $this->logAdminAction('delete_presence', 'presences', (int)$id);
            
            return $this->redirectWithSuccess('Présence supprimée avec succès');
            
        } catch (\Exception $e) {
            return $this->handleException($e, 'presence_deletion');
        }
    }

    protected function applySearchFilter(Builder $query, string $search): Builder
    {
        return $query->where(function($q) use ($search) {
            $q->whereHas('user', function($userQuery) use ($search) {
                $userQuery->where('name', 'like', '%' . $search . '%');
            })
            ->orWhereHas('cours', function($coursQuery) use ($search) {
                $coursQuery->where('nom', 'like', '%' . $search . '%');
            })
            ->orWhere('date_cours', 'like', '%' . $search . '%');
        });
    }

    /**
     * Marquer les présences en masse pour un cours
     */
    public function markAttendance(Request $request): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'cours_id' => 'required|exists:cours,id',
                'date_presence' => 'required|date',
                'presences' => 'required|array',
                'presences.*.user_id' => 'required|exists:users,id',
                'presences.*.statut' => 'required|in:present,absent,excuse'
            ]);

            $this->logAdminAction('mark_attendance_bulk', 'presences', null, [
                'cours_id' => $validated['cours_id'],
                'date' => $validated['date_presence'],
                'count' => count($validated['presences'])
            ]);

            return $this->redirectWithSuccess(
                'Présences marquées avec succès',
                'admin.presences.index'
            );

        } catch (\Exception $e) {
            return $this->handleException($e, 'mark_attendance');
        }
    }

    private function getFilters(): array
    {
        return [
            'ecoles' => $this->getEcolesForUser(),
            'cours' => $this->getCoursForEcole(),
            'statuses' => ['present', 'absent', 'excuse']
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

    private function getCoursForEcole()
    {
        $query = Cours::query()->where('statut', 'actif');
        
        if (!$this->isSuperAdmin()) {
            $query->where('ecole_id', $this->getUserEcoleId());
        }
        
        return $query->orderBy('nom')->get();
    }
}
