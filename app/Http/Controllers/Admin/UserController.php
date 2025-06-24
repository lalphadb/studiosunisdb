<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Models\Ecole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public static function middleware(): array
    {
        return [
            'can:viewAny,App\Models\User' => ['only' => ['index']],
            'can:view,user' => ['only' => ['show']],
            'can:create,App\Models\User' => ['only' => ['create', 'store']],
            'can:update,user' => ['only' => ['edit', 'update']],
            'can:delete,user' => ['only' => ['destroy']],
        ];
    }

    public function index()
    {
        $users = User::with(['ecole', 'roles'])
            ->when(!Auth::user()->hasRole('super-admin'), function ($query) {
                $query->where('ecole_id', Auth::user()->ecole_id);
            })
            ->paginate(15);

        $roles = $this->getRoleLabels();

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $ecoles = $this->getAvailableEcoles();
        $availableRoles = $this->getAvailableRoles();
        $chefsDesFamilles = $this->getChefsDesFamilles();

        return view('admin.users.create', compact('ecoles', 'availableRoles', 'chefsDesFamilles'));
    }

    public function store(UserRequest $request)
    {
        $validated = $request->validated();
        $role = $validated['role'];
        unset($validated['role']);

        // Hasher le mot de passe
        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        // Gérer la case active
        $validated['active'] = $request->has('active');

        $user = User::create($validated);
        $user->assignRole($role);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }

    public function show(User $user)
    {
        $user->load(['ecole', 'roles']);
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $ecoles = $this->getAvailableEcoles();
        $roles = $this->getAvailableRoles();
        $chefsDesFamilles = $this->getChefsDesFamilles();

        return view('admin.users.edit', compact('user', 'ecoles', 'roles', 'chefsDesFamilles'));
    }

    public function update(UserRequest $request, User $user)
    {
        $validated = $request->validated();
        $role = $validated['role'];
        unset($validated['role']);

        // Hasher le mot de passe seulement s'il est fourni
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Gérer la case active
        $validated['active'] = $request->has('active');

        $user->update($validated);
        $user->syncRoles([$role]);

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', 'Utilisateur mis à jour avec succès.');
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }

    private function getAvailableEcoles()
    {
        $user = Auth::user();
        
        if ($user->hasRole('super-admin')) {
            return Ecole::orderBy('nom')->get();
        } elseif ($user->hasRole('admin-ecole')) {
            return Ecole::where('id', $user->ecole_id)->get();
        }
        
        return collect();
    }

    private function getAvailableRoles(): array
    {
        $user = Auth::user();
        
        if ($user->hasRole('super-admin')) {
            return [
                'utilisateur' => 'Utilisateur',
                'instructeur' => 'Instructeur',
                'admin' => 'Admin',
                'admin-ecole' => 'Admin École',
                'super-admin' => 'Super Admin'
            ];
        } elseif ($user->hasRole('admin-ecole')) {
            return [
                'utilisateur' => 'Utilisateur',
                'instructeur' => 'Instructeur',
                'admin' => 'Admin'
            ];
        } elseif ($user->hasRole('admin')) {
            return [
                'utilisateur' => 'Utilisateur',
                'instructeur' => 'Instructeur'
            ];
        }
        
        return [];
    }

    private function getRoleLabels(): array
    {
        return [
            'utilisateur' => 'Utilisateur',
            'instructeur' => 'Instructeur',
            'admin' => 'Admin',
            'admin-ecole' => 'Admin École',
            'super-admin' => 'Super Admin'
        ];
    }

    private function getChefsDesFamilles()
    {
        return User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['admin', 'admin-ecole', 'super-admin']);
        })
        ->orWhereNotNull('famille_principale_id')
        ->orderBy('name')
        ->get();
    }
}
