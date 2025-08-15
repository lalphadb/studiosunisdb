<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);
        $query = User::query()->with('ecole','roles');

        if ($request->user()->hasRole('admin_ecole')) {
            $query->where('ecole_id', $request->user()->ecole_id);
        }

        if ($s = $request->input('search')) {
            $query->where(function($q) use ($s){
                $q->where('name','like',"%$s%")
                  ->orWhere('email','like',"%$s%");
            });
        }
        if ($role = $request->input('role')) {
            $query->whereHas('roles', fn($q)=>$q->where('name',$role));
        }
        if ($statut = $request->input('statut')) {
            $query->where('statut', $statut);
        }

        $users = $query->orderBy('name')->paginate(15)->withQueryString();

        return Inertia::render('Users/Index', [
            'users' => $users,
            'filters' => $request->only(['search','role','statut']),
            'canCreate' => $request->user()->can('create', User::class),
            'roles' => ['admin_ecole','instructeur','membre'],
            'isSuper' => $request->user()->hasRole('superadmin'),
            'schools' => $request->user()->hasRole('superadmin') ? School::select('id','nom')->get() : [],
        ]);
    }

    public function store(StoreUserRequest $request)
    {
        $this->authorize('create', User::class);
        $data = $request->validated();

        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->statut = $data['statut'];
        $user->ecole_id = $request->user()->hasRole('superadmin')
            ? ($data['ecole_id'] ?? $request->user()->ecole_id)
            : $request->user()->ecole_id;

        $user->save();
        $user->syncRoles([$data['role']]);

        return back()->with('success','Utilisateur créé.');
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $this->authorize('update', $user);
        $data = $request->validated();

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->statut = $data['statut'];

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->syncRoles([$data['role']]);

        if ($request->user()->hasRole('superadmin') && $request->filled('ecole_id')) {
            $user->ecole_id = $data['ecole_id'];
        }

        $user->save();

        return back()->with('success','Utilisateur mis à jour.');
    }

    public function destroy(Request $request, User $user)
    {
        $this->authorize('delete', $user);
        $user->delete();
        return back()->with('success','Utilisateur supprimé.');
    }
}
