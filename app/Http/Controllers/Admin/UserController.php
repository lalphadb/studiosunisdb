<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Ecole;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends BaseAdminController
{
    public function index()
    {
        $users = User::where('ecole_id', auth()->user()->ecole_id)
            ->with('roles')
            ->paginate(15);
            
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|exists:roles,name'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'ecole_id' => auth()->user()->ecole_id,
            'email_verified_at' => now(),
            'actif' => true
        ]);

        $user->assignRole($request->role);

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur créé avec succès !');
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|exists:roles,name'
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->password) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        $user->syncRoles([$request->role]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur mis à jour !');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur supprimé !');
    }
}
