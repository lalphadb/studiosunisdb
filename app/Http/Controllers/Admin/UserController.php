<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Ecole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            'verified',
            new Middleware('can:view-users', only: ['index', 'show']),
            new Middleware('can:create-user', only: ['create', 'store']),
            new Middleware('can:edit-user', only: ['edit', 'update']),
            new Middleware('can:delete-user', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        
        $query = User::with(['ecole', 'roles']);
        
        // CORRECTION: Ne pas filtrer par école pour superadmin
        if (!$user->hasRole('super-admin') && $user->ecole_id) {
            $query->where('ecole_id', $user->ecole_id);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('ecole_id') && $user->hasRole('super-admin')) {
            $query->where('ecole_id', $request->ecole_id);
        }
        
        if ($request->filled('role')) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('name', $request->role);
            });
        }
        
        // ORDRE PAR DATE DE CRÉATION (plus récent en premier)
        $users = $query->orderBy('created_at', 'desc')->paginate(15);
        
        $ecoles = collect();
        if ($user->hasRole('super-admin')) {
            $ecoles = Ecole::where('active', true)->orderBy('nom')->get();
        }
        
        $roles = Role::all();
        $metrics = $this->getUserMetrics($user);
        
        return view('admin.users.index', compact('users', 'ecoles', 'roles', 'metrics'));
    }

    public function show(User $user)
    {
        $user->load(['ecole', 'roles']);
        return view('admin.users.show', compact('user'));
    }

    public function create()
    {
        $user = auth()->user();
        
        $ecoles = collect();
        if ($user->hasRole('super-admin')) {
            $ecoles = Ecole::where('active', true)->orderBy('nom')->get();
        } else {
            $ecoles = collect([$user->ecole]);
        }
        
        $roles = Role::all();
        
        return view('admin.users.create', compact('ecoles', 'roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'ecole_id' => 'required|exists:ecoles,id',
            'role' => 'required|exists:roles,name',
            'telephone' => 'nullable|string|max:255',
            'date_naissance' => 'nullable|date',
            'sexe' => 'nullable|in:M,F,Autre',
            'active' => 'boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['email_verified_at'] = now();
        $validated['active'] = $request->boolean('active', true);
        $validated['date_inscription'] = now()->format('Y-m-d');

        $user = User::create($validated);
        
        // Assigner le rôle
        $user->assignRole($validated['role']);

        return redirect()->route('admin.users.index')
            ->with('success', "Utilisateur {$user->name} créé avec succès !");
    }

    public function edit(User $user)
    {
        $authUser = auth()->user();
        
        $ecoles = collect();
        if ($authUser->hasRole('super-admin')) {
            $ecoles = Ecole::where('active', true)->orderBy('nom')->get();
        } else {
            $ecoles = collect([$authUser->ecole]);
        }
        
        $roles = Role::all();
        
        return view('admin.users.edit', compact('user', 'ecoles', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        // TODO: Implémenter la mise à jour
        return redirect()->route('admin.users.show', $user)
            ->with('info', 'Fonctionnalité update en cours de développement');
    }

    public function destroy(User $user)
    {
        return redirect()->route('admin.users.index')
            ->with('info', 'Fonctionnalité delete en cours de développement');
    }

    private function getUserMetrics($user)
    {
        $metrics = [];
        
        try {
            if ($user->hasRole('super-admin')) {
                $metrics['total_users'] = User::count();
                $metrics['admins'] = User::role('admin-ecole')->count() + User::role('super-admin')->count();
                $metrics['instructeurs'] = User::role('instructeur')->count();
                $metrics['membres'] = User::role('membre')->count();
            } else {
                $ecoleUsers = User::where('ecole_id', $user->ecole_id);
                $metrics['total_users'] = $ecoleUsers->count();
                $metrics['admins'] = (clone $ecoleUsers)->whereHas('roles', fn($q) => $q->whereIn('name', ['admin-ecole', 'super-admin']))->count();
                $metrics['instructeurs'] = (clone $ecoleUsers)->role('instructeur')->count();
                $metrics['membres'] = (clone $ecoleUsers)->role('membre')->count();
            }
        } catch (\Exception $e) {
            $metrics = [
                'total_users' => User::count(),
                'admins' => 0,
                'instructeurs' => 0,
                'membres' => 0
            ];
        }
        
        return $metrics;
    }
}
