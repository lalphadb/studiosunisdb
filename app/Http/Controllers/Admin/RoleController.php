<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseAdminController;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends BaseAdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('permission:admin.dashboard')->only(['index', 'edit', 'update']);
    }

    public function index(): View
    {
        try {
            // Seuls les superadmins peuvent gérer les rôles
            if (!auth()->user()->hasRole('superadmin')) {
                abort(403, 'Accès non autorisé aux rôles système');
            }

            $roles = Role::with('permissions')->get();
            
            $this->logBusinessAction('Consultation rôles', 'info');
            
            return view('admin.roles.index', compact('roles'));
            
        } catch (\Exception $e) {
            $this->logBusinessAction('Erreur consultation rôles', 'error', [
                'error' => $e->getMessage()
            ]);
            
            session()->flash('error', 'Erreur lors du chargement des rôles.');
            return view('admin.roles.index', ['roles' => collect([])]);
        }
    }

    public function edit(Role $role): View
    {
        if (!auth()->user()->hasRole('superadmin')) {
            abort(403, 'Accès non autorisé aux rôles système');
        }

        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        
        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        try {
            if (!auth()->user()->hasRole('superadmin')) {
                abort(403, 'Accès non autorisé aux rôles système');
            }

            $request->validate([
                'permissions' => 'array',
                'permissions.*' => 'exists:permissions,name'
            ]);

            $role->syncPermissions($request->input('permissions', []));

            $this->logBusinessAction('Mise à jour rôle', 'info', [
                'role_id' => $role->id,
                'role_name' => $role->name,
                'permissions' => $request->input('permissions', [])
            ]);

            return $this->redirectWithSuccess(
                'admin.roles.index',
                'Rôle mis à jour avec succès.'
            );
            
        } catch (\Exception $e) {
            $this->logBusinessAction('Erreur mise à jour rôle', 'error', [
                'role_id' => $role->id,
                'error' => $e->getMessage()
            ]);
            
            return $this->backWithError('Erreur lors de la mise à jour du rôle.');
        }
    }
}
