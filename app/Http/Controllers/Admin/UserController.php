<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Ecole;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('can:viewAny,App\Models\User', only: ['index']),
            new Middleware('can:create,App\Models\User', only: ['create', 'store']),
            new Middleware('can:update,user', only: ['edit', 'update']),
            new Middleware('can:delete,user', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $query = User::with(['ecole', 'roles'])->orderBy('name');
        
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
        
        if ($request->filled('ecole_id')) {
            $query->where('ecole_id', $request->ecole_id);
        }
        
        if ($request->filled('role')) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('name', $request->role);
            });
        }
        
        $users = $query->paginate(20);
        
        // Données pour filtres
        $ecoles = $this->getEcolesForUser();
        $roles = ['membre', 'instructeur', 'admin_ecole', 'superadmin'];
        
        return view('admin.users.index', compact('users', 'ecoles', 'roles'));
    }

    public function create()
    {
        $ecoles = $this->getEcolesForUser();
        $roles = $this->getAvailableRoles();
        
        return view('admin.users.create', compact('ecoles', 'roles'));
    }

    public function store(UserRequest $request)
    {
        $validated = $request->validated();
        
        // Hash du mot de passe
        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }
        
        // Auto-assigner l'école pour admin_ecole
        if (auth()->user()->hasRole('admin_ecole')) {
            $validated['ecole_id'] = auth()->user()->ecole_id;
        }
        
        $user = User::create($validated);
        
        // Assigner le rôle par défaut si pas spécifié
        if (!$user->roles()->exists()) {
            $user->assignRole('membre');
        }
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur créé avec succès : ' . $user->name);
    }

    public function show(User $user)
    {
        // Vérifier autorisation multi-tenant
        if (auth()->user()->hasRole('admin_ecole')) {
            abort_unless($user->ecole_id === auth()->user()->ecole_id, 403);
        }

        $user->load(['ecole', 'roles', 'userCeintures.ceinture']);
        
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        // Vérifier autorisation multi-tenant
        if (auth()->user()->hasRole('admin_ecole')) {
            abort_unless($user->ecole_id === auth()->user()->ecole_id, 403);
        }

        $ecoles = $this->getEcolesForUser();
        $roles = $this->getAvailableRoles();
        
        return view('admin.users.edit', compact('user', 'ecoles', 'roles'));
    }

    public function update(UserRequest $request, User $user)
    {
        // Vérifier autorisation multi-tenant
        if (auth()->user()->hasRole('admin_ecole')) {
            abort_unless($user->ecole_id === auth()->user()->ecole_id, 403);
        }

        $validated = $request->validated();
        
        // Hash du mot de passe si fourni
        if (isset($validated['password']) && !empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }
        
        $user->update($validated);
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur mis à jour avec succès : ' . $user->name);
    }

    public function destroy(User $user)
    {
        // Vérifier autorisation multi-tenant
        if (auth()->user()->hasRole('admin_ecole')) {
            abort_unless($user->ecole_id === auth()->user()->ecole_id, 403);
        }

        // Empêcher la suppression de son propre compte
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'Vous ne pouvez pas supprimer votre propre compte.']);
        }

        $name = $user->name;
        $user->delete();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur supprimé : ' . $name);
    }

    /**
     * Helpers privés
     */
    private function getEcolesForUser()
    {
        if (auth()->user()->hasRole('superadmin')) {
            return Ecole::orderBy('nom')->get();
        }
        
        if (auth()->user()->hasRole('admin_ecole')) {
            return Ecole::where('id', auth()->user()->ecole_id)->get();
        }
        
        return collect();
    }

    private function getAvailableRoles()
    {
        if (auth()->user()->hasRole('superadmin')) {
            return ['membre', 'instructeur', 'admin_ecole', 'superadmin'];
        }
        
        if (auth()->user()->hasRole('admin_ecole')) {
            return ['membre', 'instructeur'];
        }
        
        return ['membre'];
    }
}
