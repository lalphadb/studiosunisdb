<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Models\Role;

class RoleController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('role:superadmin|admin_ecole', only: ['index', 'show']),
            new Middleware('role:superadmin|admin_ecole', only: ['edit', 'update']),
        ];
    }

    /**
     * Liste des utilisateurs avec leurs rôles
     */
    public function index(Request $request)
    {
        $query = User::with(['roles', 'ecole'])->orderBy('name');
        
        // Multi-tenant: Admin d'école voit ses membres
        if (auth()->user()->hasRole('admin_ecole')) {
            $query->where('ecole_id', auth()->user()->ecole_id);
        }
        
        // Filtres
        if ($request->filled('role')) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('name', $request->role);
            });
        }
        
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        $users = $query->paginate(20);
        
        // Rôles disponibles selon l'utilisateur connecté
        $availableRoles = $this->getAvailableRoles();
        
        return view('admin.roles.index', compact('users', 'availableRoles'));
    }

    /**
     * Modifier les rôles d'un utilisateur
     */
    public function edit(User $user)
    {
        // Vérifier autorisation multi-tenant
        if (auth()->user()->hasRole('admin_ecole')) {
            abort_unless($user->ecole_id === auth()->user()->ecole_id, 403);
        }

        $availableRoles = $this->getAvailableRoles();
        
        return view('admin.roles.edit', compact('user', 'availableRoles'));
    }

    /**
     * Mettre à jour les rôles
     */
    public function update(Request $request, User $user)
    {
        // Vérifier autorisation multi-tenant
        if (auth()->user()->hasRole('admin_ecole')) {
            abort_unless($user->ecole_id === auth()->user()->ecole_id, 403);
        }

        $request->validate([
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,name',
        ]);

        // Vérifier que l'admin école ne peut pas créer de superadmin
        if (auth()->user()->hasRole('admin_ecole') && in_array('superadmin', $request->roles)) {
            return back()->withErrors(['roles' => 'Vous ne pouvez pas attribuer le rôle superadmin.']);
        }

        // Synchroniser les rôles
        $user->syncRoles($request->roles);
        
        return redirect()->route('admin.roles.index')
            ->with('success', 'Rôles mis à jour pour ' . $user->name);
    }

    /**
     * Rôles disponibles selon l'utilisateur connecté
     */
    private function getAvailableRoles()
    {
        if (auth()->user()->hasRole('superadmin')) {
            return ['membre', 'instructeur', 'admin_ecole', 'superadmin'];
        }
        
        if (auth()->user()->hasRole('admin_ecole')) {
            return ['membre', 'instructeur', 'admin_ecole'];
        }
        
        return ['membre'];
    }
}
