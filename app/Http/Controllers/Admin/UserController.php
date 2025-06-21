<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Ecole;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Models\Role;

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
        
        // Query de base avec relations
        $usersQuery = User::with(['ecole', 'roles'])
            ->select('users.*'); // Éviter les conflits de colonnes
        
        // Multi-tenant : SuperAdmin voit tout, Admin voit son école seulement
        if (!$user->isSuperAdmin() && $user->ecole_id) {
            $usersQuery->where('users.ecole_id', $user->ecole_id);
        }

        // Filtres de recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $usersQuery->where(function($q) use ($search) {
                $q->where('users.name', 'like', "%{$search}%")
                  ->orWhere('users.email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('ecole_id') && $user->isSuperAdmin()) {
            $usersQuery->where('users.ecole_id', $request->ecole_id);
        }

        if ($request->filled('role')) {
            $usersQuery->whereHas('roles', function($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        if ($request->filled('active')) {
            $usersQuery->where('users.active', $request->boolean('active'));
        }

        // Pagination
        $users = $usersQuery->orderBy('users.name')->paginate(25);

        // Données pour les filtres
        $ecoles = collect();
        $roles = Role::all(['id', 'name']);
        
        if ($user->isSuperAdmin()) {
            $ecoles = Ecole::select('id', 'nom', 'code')->orderBy('nom')->get();
        }

        // Métriques pour le dashboard
        $metrics = [];
        if ($user->isSuperAdmin()) {
            $metrics = [
                'total_users' => User::whereNotNull('ecole_id')->count(),
                'admins' => User::whereHas('roles', fn($q) => $q->where('name', 'admin'))->count(),
                'instructeurs' => User::whereHas('roles', fn($q) => $q->where('name', 'instructeur'))->count(),
                'membres' => User::whereHas('roles', fn($q) => $q->where('name', 'membre'))->count(),
            ];
        } else {
            $ecole = $user->ecole;
            if ($ecole) {
                $metrics = [
                    'users_ecole' => $ecole->users()->count(),
                    'admins' => $ecole->users()->whereHas('roles', fn($q) => $q->where('name', 'admin'))->count(),
                    'instructeurs' => $ecole->users()->whereHas('roles', fn($q) => $q->where('name', 'instructeur'))->count(),
                    'membres' => $ecole->users()->whereHas('roles', fn($q) => $q->where('name', 'membre'))->count(),
                ];
            }
        }

        return view('admin.users.index', compact('users', 'ecoles', 'roles', 'metrics'));
    }

    public function show(User $user)
    {
        $authUser = auth()->user();
        
        // Vérification multi-tenant
        if (!$authUser->isSuperAdmin() && $authUser->ecole_id !== $user->ecole_id) {
            abort(403, 'Accès non autorisé à cet utilisateur.');
        }

        $user->load([
            'ecole',
            'roles',
            'membre_ceintures.ceinture',
            'inscriptions_cours.cours',
            'presences' => fn($q) => $q->latest()->limit(10),
            'paiements' => fn($q) => $q->latest()->limit(5),
        ]);

        return view('admin.users.show', compact('user'));
    }

    public function create()
    {
        $user = auth()->user();
        
        // Écoles disponibles
        $ecoles = collect();
        if ($user->isSuperAdmin()) {
            $ecoles = Ecole::where('active', true)->orderBy('nom')->get(['id', 'nom', 'code']);
        } elseif ($user->ecole_id) {
            $ecoles = Ecole::where('id', $user->ecole_id)->get(['id', 'nom', 'code']);
        }

        // Rôles disponibles selon permissions
        $roles = collect();
        if ($user->isSuperAdmin()) {
            $roles = Role::all(['id', 'name']);
        } elseif ($user->isAdmin()) {
            $roles = Role::whereIn('name', ['instructeur', 'membre'])->get(['id', 'name']);
        }

        return view('admin.users.create', compact('ecoles', 'roles'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:191',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'ecole_id' => 'required|exists:ecoles,id',
            'role' => 'required|exists:roles,name',
            'telephone' => 'nullable|string|max:191',
            'date_naissance' => 'nullable|date|before:today',
            'sexe' => 'nullable|in:M,F,Autre',
            'adresse' => 'nullable|string',
            'ville' => 'nullable|string|max:191',
            'code_postal' => 'nullable|string|max:191',
            'contact_urgence_nom' => 'nullable|string|max:191',
            'contact_urgence_telephone' => 'nullable|string|max:191',
            'active' => 'boolean',
        ]);

        // Vérification multi-tenant
        if (!$user->isSuperAdmin() && $validated['ecole_id'] != $user->ecole_id) {
            abort(403, 'Vous ne pouvez créer des utilisateurs que pour votre école.');
        }

        // Vérification des rôles
        if (!$user->isSuperAdmin() && in_array($validated['role'], ['superadmin', 'admin'])) {
            abort(403, 'Vous ne pouvez pas assigner ce rôle.');
        }

        $validated['password'] = bcrypt($validated['password']);
        $validated['active'] = $request->boolean('active', true);
        $validated['date_inscription'] = now()->toDateString();

        $newUser = User::create($validated);
        $newUser->assignRole($validated['role']);

        return redirect()
            ->route('admin.users.show', $newUser)
            ->with('success', "Utilisateur '{$newUser->name}' créé avec succès.");
    }

    public function edit(User $user)
    {
        $authUser = auth()->user();
        
        // Vérification multi-tenant
        if (!$authUser->isSuperAdmin() && $authUser->ecole_id !== $user->ecole_id) {
            abort(403, 'Vous ne pouvez éditer que les utilisateurs de votre école.');
        }

        // Écoles disponibles
        $ecoles = collect();
        if ($authUser->isSuperAdmin()) {
            $ecoles = Ecole::where('active', true)->orderBy('nom')->get(['id', 'nom', 'code']);
        } else {
            $ecoles = Ecole::where('id', $authUser->ecole_id)->get(['id', 'nom', 'code']);
        }

        // Rôles disponibles
        $roles = collect();
        if ($authUser->isSuperAdmin()) {
            $roles = Role::all(['id', 'name']);
        } elseif ($authUser->isAdmin()) {
            $roles = Role::whereIn('name', ['instructeur', 'membre'])->get(['id', 'name']);
        }

        $user->load('roles');

        return view('admin.users.edit', compact('user', 'ecoles', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $authUser = auth()->user();
        
        // Vérification multi-tenant
        if (!$authUser->isSuperAdmin() && $authUser->ecole_id !== $user->ecole_id) {
            abort(403, 'Vous ne pouvez éditer que les utilisateurs de votre école.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:191',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'ecole_id' => 'required|exists:ecoles,id',
            'role' => 'required|exists:roles,name',
            'telephone' => 'nullable|string|max:191',
            'date_naissance' => 'nullable|date|before:today',
            'sexe' => 'nullable|in:M,F,Autre',
            'adresse' => 'nullable|string',
            'ville' => 'nullable|string|max:191',
            'code_postal' => 'nullable|string|max:191',
            'contact_urgence_nom' => 'nullable|string|max:191',
            'contact_urgence_telephone' => 'nullable|string|max:191',
            'active' => 'boolean',
        ]);

        // Vérifications sécurité
        if (!$authUser->isSuperAdmin() && $validated['ecole_id'] != $authUser->ecole_id) {
            abort(403, 'Vous ne pouvez assigner des utilisateurs qu\'à votre école.');
        }

        if (!$authUser->isSuperAdmin() && in_array($validated['role'], ['superadmin', 'admin'])) {
            abort(403, 'Vous ne pouvez pas assigner ce rôle.');
        }

        // Mise à jour mot de passe si fourni
        if ($request->filled('password')) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['active'] = $request->boolean('active');

        $user->update($validated);
        
        // Mise à jour du rôle
        $user->syncRoles([$validated['role']]);

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', "Utilisateur '{$user->name}' mis à jour avec succès.");
    }

    public function destroy(User $user)
    {
        $authUser = auth()->user();
        
        // Seul SuperAdmin peut supprimer
        if (!$authUser->isSuperAdmin()) {
            abort(403, 'Seul le SuperAdmin peut supprimer des utilisateurs.');
        }

        // Ne pas supprimer soi-même
        if ($user->id === $authUser->id) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $nom = $user->name;
        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', "Utilisateur '{$nom}' supprimé avec succès.");
    }

    public function qrcode(User $user)
    {
        $authUser = auth()->user();
        
        if (!$authUser->isSuperAdmin() && $authUser->ecole_id !== $user->ecole_id) {
            abort(403);
        }

        // TODO: Générer QR code pour présences
        return response()->json(['qrcode' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==']);
    }

    public function export(Request $request)
    {
        // TODO: Export Excel/CSV
        return response()->download('users_export.xlsx');
    }
}
