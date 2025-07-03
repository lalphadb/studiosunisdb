<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;  // ← CORRECTION ICI
use App\Models\User;
use App\Models\Ecole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        $query = User::with(['ecole', 'roles']);
        
        // SUPERADMIN voit TOUS les utilisateurs
        if (auth()->user()->hasRole('superadmin')) {
            // Pas de filtre - voir tous les utilisateurs de toutes les écoles
        } else {
            // Admin école ne voit que les utilisateurs de SON école
            $query->where('ecole_id', auth()->user()->ecole_id);
        }
        
        // Filtres de recherche
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('ecole_id')) {
            $query->where('ecole_id', $request->get('ecole_id'));
        }
        
        if ($request->filled('role')) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('name', $request->get('role'));
            });
        }
        
        $users = $query->latest()->paginate(15);
        
        // Données pour les filtres
        $ecoles = auth()->user()->hasRole('superadmin') 
            ? Ecole::orderBy('nom')->get() 
            : collect([auth()->user()->ecole]);
            
        $roles = Role::orderBy('name')->get();
        
        return view('admin.users.index', compact('users', 'ecoles', 'roles'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        $ecoles = auth()->user()->hasRole('superadmin') 
            ? Ecole::orderBy('nom')->get() 
            : collect([auth()->user()->ecole]);
            
        $roles = Role::orderBy('name')->get();
        
        return view('admin.users.create', compact('ecoles', 'roles'));
    }

    /**
     * Store a newly created user
     */
    public function store(UserRequest $request)
    {
        $validated = $request->validated();
        
        // Vérification des permissions pour l'école
        if (!auth()->user()->hasRole('superadmin')) {
            $validated['ecole_id'] = auth()->user()->ecole_id;
        }
        
        // Hash du mot de passe
        $validated['password'] = Hash::make($validated['password']);
        $validated['active'] = $request->has('active');
        
        $user = User::create($validated);
        
        // Assignation du rôle
        if (isset($validated['role'])) {
            $user->assignRole($validated['role']);
        }
        
        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        // Vérification des permissions
        if (!auth()->user()->hasRole('superadmin') && $user->ecole_id !== auth()->user()->ecole_id) {
            abort(403, 'Accès non autorisé à cet utilisateur.');
        }
        
        $user->load(['ecole', 'roles', 'userCeintures.ceinture', 'inscriptionsCours', 'inscriptionsSeminaires']);
        
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        // Vérification des permissions
        if (!auth()->user()->hasRole('superadmin') && $user->ecole_id !== auth()->user()->ecole_id) {
            abort(403, 'Accès non autorisé à cet utilisateur.');
        }
        
        $ecoles = auth()->user()->hasRole('superadmin') 
            ? Ecole::orderBy('nom')->get() 
            : collect([auth()->user()->ecole]);
            
        $roles = Role::orderBy('name')->get();
        
        return view('admin.users.edit', compact('user', 'ecoles', 'roles'));
    }

    /**
     * Update the specified user
     */
    public function update(UserRequest $request, User $user)
    {
        // Vérification des permissions
        if (!auth()->user()->hasRole('superadmin') && $user->ecole_id !== auth()->user()->ecole_id) {
            abort(403, 'Accès non autorisé à cet utilisateur.');
        }
        
        $validated = $request->validated();
        
        // Vérification des permissions pour l'école
        if (!auth()->user()->hasRole('superadmin')) {
            $validated['ecole_id'] = auth()->user()->ecole_id;
        }
        
        // Hash du mot de passe si fourni
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }
        
        $validated['active'] = $request->has('active');
        
        $user->update($validated);
        
        // Mise à jour du rôle
        if (isset($validated['role'])) {
            $user->syncRoles([$validated['role']]);
        }
        
        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Utilisateur modifié avec succès.');
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user)
    {
        // Vérification des permissions
        if (!auth()->user()->hasRole('superadmin') && $user->ecole_id !== auth()->user()->ecole_id) {
            abort(403, 'Accès non autorisé à cet utilisateur.');
        }
        
        // Empêcher la suppression de son propre compte
        if ($user->id === auth()->id()) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }
        
        $user->delete();
        
        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }
    
    /**
     * Export users (for GDPR compliance)
     */
    public function export()
    {
        $query = User::with(['ecole', 'roles']);
        
        // Filtrage selon les permissions
        if (!auth()->user()->hasRole('superadmin')) {
            $query->where('ecole_id', auth()->user()->ecole_id);
        }
        
        $users = $query->get();
        
        // Logique d'export (Excel, PDF, etc.)
        return response()->json([
            'message' => 'Export en cours de développement',
            'users_count' => $users->count()
        ]);
    }

    /**
     * Generate QR Code for user
     */
    public function qrcode(User $user)
    {
        // Vérification des permissions
        if (!auth()->user()->hasRole('superadmin') && $user->ecole_id !== auth()->user()->ecole_id) {
            abort(403, 'Accès non autorisé à cet utilisateur.');
        }
        
        // Génération QR Code (à implémenter selon vos besoins)
        return response()->json([
            'message' => 'QR Code pour ' . $user->name,
            'user_id' => $user->id
        ]);
    }
}
