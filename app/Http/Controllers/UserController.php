<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $query = User::query()
            ->with(['roles', 'permissions']);

        // Filtres
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        // Tri
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $users = $query->paginate(15)->withQueryString();
        $roles = Role::all();

        return Inertia::render('Utilisateurs/Index', [
            'users' => $users,
            'roles' => $roles,
            'filters' => $request->only(['q', 'role', 'sort', 'direction']),
            'can' => [
                'create' => auth()->user()->can('create', User::class),
                'edit' => auth()->user()->can('update', User::class),
                'delete' => auth()->user()->can('delete', User::class),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', User::class);

        $roles = Role::all();

        return Inertia::render('Utilisateurs/Create', [
            'roles' => $roles,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $this->authorize('create', User::class);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => $request->email_verified ? now() : null,
            
        ]);

        if ($request->filled('roles')) {
            $user->syncRoles($request->roles);
        }

        return redirect()->route('utilisateurs.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $this->authorize('view', $user);

        $user->load(['roles', 'permissions', 'membre']);

        return Inertia::render('Utilisateurs/Show', [
            'user' => $user,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);

        $user->load('roles');
        $roles = Role::all();

        return Inertia::render('Utilisateurs/Edit', [
            'user' => $user,
            'roles' => $roles,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $this->authorize('update', $user);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->has('email_verified')) {
            $data['email_verified_at'] = $request->email_verified ? now() : null;
        }

        $user->update($data);

        if ($request->filled('roles') && auth()->user()->can('manageRoles', $user)) {
            $user->syncRoles($request->roles);
        }

        return redirect()->route('utilisateurs.index')
            ->with('success', 'Utilisateur modifié avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        $user->delete();

        return redirect()->route('utilisateurs.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }

    /**
     * Reset user password
     */
    public function resetPassword(Request $request, User $user)
    {
        $this->authorize('resetPassword', $user);

        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Mot de passe réinitialisé avec succès.');
    }

    /**
     * Manage user roles
     */
    public function manageRoles(Request $request, User $user)
    {
        $this->authorize('manageRoles', $user);

        $request->validate([
            'roles' => ['required', 'array'],
            'roles.*' => ['exists:roles,name'],
        ]);

        $user->syncRoles($request->roles);

        return back()->with('success', 'Rôles mis à jour avec succès.');
    }
}
