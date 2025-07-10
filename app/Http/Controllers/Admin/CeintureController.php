<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CeintureRequest;
use App\Models\Ceinture;
use App\Models\User;
use App\Models\UserCeinture;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Database\Eloquent\Builder;

class CeintureController extends BaseAdminController
{
    public function index(Request $request): View
    {
        try {
            $query = UserCeinture::with(['user', 'ceinture', 'ecole'])
                        ->orderBy('date_attribution', 'desc');

            if ($request->filled('ecole_id')) {
                $query->where('ecole_id', $request->ecole_id);
            }

            if ($request->filled('ceinture_id')) {
                $query->where('ceinture_id', $request->ceinture_id);
            }

            if ($request->filled('valide')) {
                $query->where('valide', $request->boolean('valide'));
            }

            $userCeintures = $this->paginate($query, $request);

            $this->logAdminAction('view_ceintures_list', 'ceintures', null, [
                'total_attributions' => $userCeintures->total()
            ]);

            return $this->adminView('admin.ceintures.index', [
                'userCeintures' => $userCeintures,
                'ecoles' => $this->getEcolesForUser(),
                'ceintures' => Ceinture::orderBy('ordre')->get(),
                'filters' => $request->all()
            ]);

        } catch (\Exception $e) {
            return $this->handleException($e, 'ceintures_index');
        }
    }

    public function create(): View
    {
        try {
            $this->logAdminAction('access_ceinture_create', 'ceintures');

            return $this->adminView('admin.ceintures.create', [
                'ecoles' => $this->getEcolesForUser(),
                'users' => $this->getUsersForEcole(),
                'ceintures' => Ceinture::orderBy('ordre')->get()
            ]);

        } catch (\Exception $e) {
            return $this->handleException($e, 'ceinture_create_form');
        }
    }

    public function createMasse(): View
    {
        try {
            $this->logAdminAction('access_ceinture_create_masse', 'ceintures');

            return $this->adminView('admin.ceintures.create-masse', [
                'ecoles' => $this->getEcolesForUser(),
                'users' => $this->getUsersForEcole(),
                'ceintures' => Ceinture::orderBy('ordre')->get()
            ]);

        } catch (\Exception $e) {
            return $this->handleException($e, 'ceinture_create_masse_form');
        }
    }

    public function store(Request $request): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'ceinture_id' => 'required|exists:ceintures,id',
                'date_attribution' => 'required|date',
                'examinateur' => 'nullable|string|max:255',
                'lieu_examen' => 'nullable|string|max:255',
                'valide' => 'boolean'
            ]);

            if (!$this->isSuperAdmin() && !isset($validated['ecole_id'])) {
                $validated['ecole_id'] = $this->getUserEcoleId();
            }

            $userCeinture = UserCeinture::create($validated);

            // Mettre à jour la ceinture actuelle de l'utilisateur
            if ($validated['valide'] ?? false) {
                $user = User::find($validated['user_id']);
                $user->update(['ceinture_actuelle_id' => $validated['ceinture_id']]);
            }

            $this->logAdminAction('create_ceinture_attribution', 'ceintures', $userCeinture->id, [
                'user_id' => $validated['user_id'],
                'ceinture_id' => $validated['ceinture_id'],
                'valide' => $validated['valide'] ?? false
            ]);

            return $this->redirectWithSuccess(
                'Attribution de ceinture créée avec succès',
                'admin.ceintures.index'
            );

        } catch (\Exception $e) {
            return $this->handleException($e, 'ceinture_attribution');
        }
    }

    public function storeMasse(Request $request): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'user_ids' => 'required|array',
                'user_ids.*' => 'exists:users,id',
                'ceinture_id' => 'required|exists:ceintures,id',
                'date_attribution' => 'required|date',
                'examinateur' => 'nullable|string|max:255',
                'lieu_examen' => 'nullable|string|max:255',
                'valide' => 'boolean'
            ]);

            $count = 0;
            $ecoleId = $this->getUserEcoleId();

            foreach ($validated['user_ids'] as $userId) {
                UserCeinture::create([
                    'user_id' => $userId,
                    'ceinture_id' => $validated['ceinture_id'],
                    'ecole_id' => $ecoleId,
                    'date_attribution' => $validated['date_attribution'],
                    'date_obtention' => $validated['date_attribution'],
                    'examinateur' => $validated['examinateur'],
                    'lieu_examen' => $validated['lieu_examen'],
                    'valide' => $validated['valide'] ?? false
                ]);

                // Mettre à jour ceinture actuelle si validé
                if ($validated['valide'] ?? false) {
                    User::find($userId)->update([
                        'ceinture_actuelle_id' => $validated['ceinture_id']
                    ]);
                }

                $count++;
            }

            $this->logAdminAction('create_ceinture_attribution_masse', 'ceintures', null, [
                'count' => $count,
                'ceinture_id' => $validated['ceinture_id'],
                'user_ids' => $validated['user_ids']
            ]);

            return $this->redirectWithSuccess(
                "Attribution en masse créée pour {$count} utilisateurs",
                'admin.ceintures.index'
            );

        } catch (\Exception $e) {
            return $this->handleException($e, 'ceinture_attribution_masse');
        }
    }

    /**
     * Signature correcte selon BaseAdminController
     */
    protected function applySearchFilter(Builder $query, string $search): Builder
    {
        return $query->whereHas('user', function($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%')
              ->orWhere('email', 'like', '%' . $search . '%');
        });
    }

    private function getUsersForEcole()
    {
        if ($this->isSuperAdmin()) {
            return User::orderBy('name')->get();
        }

        return User::where('ecole_id', $this->getUserEcoleId())
                  ->orderBy('name')
                  ->get();
    }
}
