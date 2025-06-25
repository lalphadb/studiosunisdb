<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('can:viewAny,App\Models\User', only: ['index']),
            new Middleware('can:view,user', only: ['show']),
            new Middleware('can:create,App\Models\User', only: ['create', 'store']),
            new Middleware('can:update,user', only: ['edit', 'update']),
            new Middleware('can:delete,user', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $query = User::with(['ecole', 'roles']);
        
        // Multi-tenant: Admin d'école voit seulement ses users
        if (auth()->user()->hasRole('admin_ecole')) {
            $query->where('ecole_id', auth()->user()->ecole_id);
        }
        
        // Filtrage par école pour SuperAdmin
        if ($request->filled('ecole_id')) {
            $query->where('ecole_id', $request->ecole_id);
        }
        
        // Recherche
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        
        $users = $query->paginate(15);
        
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $ecoles = $this->getAvailableEcoles();
        $roles = $this->getAvailableRoles();
        
        return view('admin.users.create', compact('ecoles', 'roles'));
    }

    public function store(UserRequest $request)
    {
        $validated = $request->validated();
        $role = $validated['role'];
        unset($validated['role']);
        
        // Hash password
        if (isset($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        }
        
        $user = User::create($validated);
        $user->assignRole($role);
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }

    public function show(User $user)
    {
        $user->load(['ecole', 'roles', 'membre_ceintures.ceinture']);
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $ecoles = $this->getAvailableEcoles();
        $roles = $this->getAvailableRoles();
        
        return view('admin.users.edit', compact('user', 'ecoles', 'roles'));
    }

    public function update(UserRequest $request, User $user)
    {
        $validated = $request->validated();
        $role = $validated['role'] ?? null;
        unset($validated['role']);
        
        // Hash password si fourni
        if (isset($validated['password']) && !empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }
        
        $user->update($validated);
        
        if ($role) {
            $user->syncRoles([$role]);
        }
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur modifié avec succès.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }

    private function getAvailableEcoles()
    {
        if (auth()->user()->hasRole('superadmin')) {
            return \App\Models\Ecole::active()->get();
        } elseif (auth()->user()->hasRole('admin_ecole')) {
            return \App\Models\Ecole::where('id', auth()->user()->ecole_id)->get();
        }
        
        return collect();
    }

    private function getAvailableRoles()
    {
        $user = auth()->user();
        
        if ($user->hasRole('superadmin')) {
            return [
                'user' => 'Utilisateur',
                'instructeur' => 'Instructeur',
                'admin' => 'Admin',
                'admin_ecole' => 'Admin École',
                'superadmin' => 'Super Admin'
            ];
        } elseif ($user->hasRole('admin_ecole')) {
            return [
                'user' => 'Utilisateur',
                'instructeur' => 'Instructeur',
                'admin' => 'Admin'
            ];
        } elseif ($user->hasRole('admin')) {
            return [
                'user' => 'Utilisateur',
                'instructeur' => 'Instructeur'
            ];
        }
        
        return [];
    }
}
