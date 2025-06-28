<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('can:manage-roles', only: ['index', 'edit', 'update'])
        ];
    }

    /**
     * Display a listing of the roles.
     */
    public function index()
    {
        // Multi-tenant filtering
        if (auth()->user()->hasRole('admin_ecole')) {
            abort(403, 'Accès non autorisé aux rôles système');
        }

        $roles = Role::with('permissions')->get();
        
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for editing the specified role.
     */
    public function edit(Role $role)
    {
        if (auth()->user()->hasRole('admin_ecole')) {
            abort(403, 'Accès non autorisé aux rôles système');
        }

        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        
        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Update the specified role in storage.
     */
    public function update(Request $request, Role $role)
    {
        if (auth()->user()->hasRole('admin_ecole')) {
            abort(403, 'Accès non autorisé aux rôles système');
        }

        $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name'
        ]);

        // Sync permissions
        $role->syncPermissions($request->input('permissions', []));

        return redirect()->route('admin.roles.index')
            ->with('success', 'Rôle mis à jour avec succès.');
    }
}
