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
            'verified',
            new Middleware('role:superadmin|admin_ecole', only: ['index', 'show']),
            new Middleware('role:superadmin|admin_ecole', only: ['edit', 'update']),
        ];
    }

    public function index(Request $request)
    {
        $query = User::with(['roles', 'ecole'])->orderBy('name');
        
        // Multi-tenant: Admin d'école voit ses membres
        if (auth()->user()->hasRole('admin_ecole')) {
            $query->where('ecole_id', auth()->user()->ecole_id);
        }
        
        // Filtres
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->filled('role')) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('name', $request->role);
            });
        }
        
        $users = $query->paginate(20);
        $roles = $this->getAvailableRoles();
        
        return view('admin.roles.index', compact('users', 'roles'));
    }

    public function edit(User $user)
    {
        // Vérifier autorisation multi-tenant
        if (auth()->user()->hasRole('admin_ecole')) {
            abort_unless($user->ecole_id === auth()->user()->ecole_id, 403);
        }

        $roles = $this->getAvailableRoles();
        
        return view('admin.roles.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        // Vérifier autorisation multi-tenant
        if (auth()->user()->hasRole('admin_ecole')) {
            abort_unless($user->ecole_id === auth()->user()->ecole_id, 403);
        }

        $request->validate([
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,name'
        ]);

        // Empêcher un admin_ecole de modifier un superadmin
        if (auth()->user()->hasRole('admin_ecole') && $user->hasRole('superadmin')) {
            return back()->withErrors(['error' => 'Vous ne pouvez pas modifier les rôles d\'un SuperAdmin.']);
        }

        $user->syncRoles($request->roles);
        
        return redirect()->route('admin.roles.index')
            ->with('success', 'Rôles mis à jour avec succès pour : ' . $user->name);
    }

    /**
     * Helper privé
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
