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
    // Liste des utilisateurs
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);
        $authUser = auth()->user();

        $query = User::query()->with('roles')
            ->when(!$authUser->hasRole('superadmin'), fn($q) => $q->where('ecole_id', $authUser->ecole_id));

        if ($request->filled('q')) {
            $search = trim($request->q);
            $query->where(function($sub) use ($search) {
                $sub->where('name','like',"%{$search}%")
                    ->orWhere('email','like',"%{$search}%");
            });
        }
        if ($request->filled('role')) {
            $role = $request->role;
            $query->whereHas('roles', fn($r) => $r->where('name',$role));
        }

        $allowedSort = ['name','email','created_at'];
        $sortField = in_array($request->get('sort'), $allowedSort) ? $request->get('sort') : 'created_at';
        $sortDirection = $request->get('direction') === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sortField, $sortDirection);

        $users = $query->paginate(15)->withQueryString();
        $roles = Role::query()
            ->when(!$authUser->hasRole('superadmin'), fn($q) => $q->where('name','!=','superadmin'))
            ->pluck('name');

        return Inertia::render('Utilisateurs/Index', [
            'users' => $users,
            'roles' => $roles,
            'filters' => $request->only(['q','role','sort','direction']),
            'can' => [
                'create' => $authUser->can('create', User::class),
                'manageRoles' => $authUser->can('manageRoles', $authUser),
            ],
        ]);
    }

    // Formulaire création
    public function create()
    {
        $this->authorize('create', User::class);
        $authUser = auth()->user();
        $roles = Role::query()
            ->when(!$authUser->hasRole('superadmin'), fn($q) => $q->where('name','!=','superadmin'))
            ->pluck('name');
        return Inertia::render('Utilisateurs/Create', compact('roles'));
    }

    // Enregistrement
    public function store(StoreUserRequest $request)
    {
        $this->authorize('create', User::class);
        $authUser = auth()->user();
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => $request->boolean('email_verified') ? now() : null,
            'ecole_id' => $authUser->ecole_id,
        ]);
        $rolesInput = collect($request->input('roles', []))
            ->reject(fn($r) => $r === 'superadmin' && !$authUser->hasRole('superadmin'))
            ->all();
        if ($rolesInput) $user->syncRoles($rolesInput);
        return redirect()->route('utilisateurs.index')->with('success','Utilisateur créé avec succès.');
    }

    // Affichage
    public function show(User $user)
    {
        $this->authorize('view', $user);
        $user->load(['roles','permissions','membre']);
        return Inertia::render('Utilisateurs/Show', compact('user'));
    }

    // Formulaire édition
    public function edit(User $user)
    {
        $this->authorize('update', $user);
        $authUser = auth()->user();
        $user->load('roles');
        $roles = Role::query()
            ->when(!$authUser->hasRole('superadmin'), fn($q) => $q->where('name','!=','superadmin'))
            ->pluck('name');
        return Inertia::render('Utilisateurs/Edit', compact('user','roles'));
    }

    // Mise à jour
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
            $data['email_verified_at'] = $request->boolean('email_verified') ? now() : null;
        }
        $user->update($data);
        if ($request->filled('roles') && auth()->user()->can('manageRoles', $user)) {
            $rolesInput = collect($request->roles)
                ->reject(fn($r) => $r === 'superadmin' && !auth()->user()->hasRole('superadmin'))
                ->all();
            $user->syncRoles($rolesInput);
        }
        return redirect()->route('utilisateurs.index')->with('success','Utilisateur modifié avec succès.');
    }

    // Désactivation / suppression
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        if ($user->isFillable('active') && \Schema::hasColumn('users','active')) {
            $user->update(['active' => false]);
        } else {
            $user->delete();
        }
        return redirect()->route('utilisateurs.index')->with('success','Utilisateur désactivé.');
    }

    // Reset password
    public function resetPassword(Request $request, User $user)
    {
        $this->authorize('resetPassword', $user);
        $request->validate(['password' => ['required','string','min:8','confirmed']]);
        $user->update(['password' => Hash::make($request->password)]);
        return back()->with('success','Mot de passe réinitialisé avec succès.');
    }

    // Gestion des rôles
    public function manageRoles(Request $request, User $user)
    {
        $this->authorize('manageRoles', $user);
        $request->validate([
            'roles' => ['required','array'],
            'roles.*' => ['exists:roles,name'],
        ]);
        $rolesInput = collect($request->roles)
            ->reject(fn($r) => $r === 'superadmin' && !auth()->user()->hasRole('superadmin'))
            ->all();
        $user->syncRoles($rolesInput);
        return back()->with('success','Rôles mis à jour avec succès.');
    }
}
