<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use App\Models\Ecole;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

/**
 * Contrôleur de gestion des utilisateurs
 * 
 * Implémente le standard Laravel Admin Controllers v2.0
 */
class UserController extends BaseAdminController
{
    /**
     * Initialise le contrôleur avec middleware can: selon le standard
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->middleware('can:viewAny,App\Models\User')->only(['index']);
        $this->middleware('can:view,user')->only(['show']);
        $this->middleware('can:create,App\Models\User')->only(['create', 'store']);
        $this->middleware('can:update,user')->only(['edit', 'update']);
        $this->middleware('can:delete,user')->only(['destroy']);
    }

    /**
     * Display a listing of users
     */
    public function index(Request $request): View
    {
        return $this->executeWithExceptionHandling(function() use ($request) {
            $query = User::with(['ecole', 'roles']);
            
            // Recherche
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }
            
            // Filtres
            if ($request->filled('ecole_id')) {
                $query->where('ecole_id', $request->ecole_id);
            }
            
            if ($request->filled('role')) {
                $query->whereHas('roles', function($q) use ($request) {
                    $q->where('name', $request->role);
                });
            }
            
            $users = $this->paginateWithParams($query->latest(), $request);
            
            // Données pour les filtres
            $ecoles = auth()->user()->hasRole('superadmin') 
                ? Ecole::orderBy('nom')->get() 
                : collect([auth()->user()->ecole]);
                
            $roles = Role::orderBy('name')->get();
            
            $this->logBusinessAction('Consultation index utilisateurs', 'info', [
                'total_users' => $users->total(),
                'filters' => $request->only(['search', 'ecole_id', 'role'])
            ]);
            
            return view('pages.admin.users.index', compact('users', 'ecoles', 'roles'));
            
        }, 'consultation utilisateurs');
    }

    /**
     * Show the form for creating a new user
     */
    public function create(): View
    {
        $ecoles = auth()->user()->hasRole('superadmin') 
            ? Ecole::orderBy('nom')->get() 
            : collect([auth()->user()->ecole]);
            
        $roles = Role::orderBy('name')->get();
        
        return view('pages.admin.users.create', compact('ecoles', 'roles'));
    }

    /**
     * Store a newly created user
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        return $this->executeWithExceptionHandling(function() use ($request) {
            $validated = $request->validated();
            
            // Vérification des permissions pour l'école
            if (!auth()->user()->hasRole('superadmin')) {
                $validated['ecole_id'] = auth()->user()->ecole_id;
            }
            
            // Hash du mot de passe
            $validated['password'] = Hash::make($validated['password']);
            $validated['active'] = $request->boolean('active', true);
            
            $user = User::create($validated);
            
            // Assignation du rôle
            if (isset($validated['role'])) {
                $user->assignRole($validated['role']);
            }
            
            $this->logCreate('Utilisateur', $user->id, [
                'name' => $user->name,
                'email' => $user->email,
                'ecole_id' => $user->ecole_id,
                'role' => $validated['role'] ?? null
            ]);
            
            return $this->redirectWithSuccess(
                'pages.admin.users.index',
                $this->trans('admin.success.created', ['item' => "Utilisateur \"{$user->name}\""])
            );
            
        }, 'création utilisateur', ['form_data' => $request->validated()]);
    }

    /**
     * Display the specified user
     */
    public function show(User $user): View
    {
        $user->load(['ecole', 'roles', 'userCeintures.ceinture', 'inscriptionsCours', 'inscriptionsSeminaires']);
        
        return view('pages.admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user): View
    {
        $ecoles = auth()->user()->hasRole('superadmin') 
            ? Ecole::orderBy('nom')->get() 
            : collect([auth()->user()->ecole]);
            
        $roles = Role::orderBy('name')->get();
        
        return view('pages.admin.users.edit', compact('user', 'ecoles', 'roles'));
    }

    /**
     * Update the specified user
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        return $this->executeWithExceptionHandling(function() use ($request, $user) {
            $validated = $request->validated();
            $oldData = $user->toArray();
            
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
            
            $validated['active'] = $request->boolean('active', false);
            
            $user->update($validated);
            
            // Mise à jour du rôle
            if (isset($validated['role'])) {
                $user->syncRoles([$validated['role']]);
            }
            
            $this->logUpdate('Utilisateur', $user->id, $oldData, $validated);
            
            return $this->redirectWithSuccess(
                'pages.admin.users.index',
                $this->trans('admin.success.updated', ['item' => "Utilisateur \"{$user->name}\""])
            );
            
        }, 'modification utilisateur', ['user_id' => $user->id]);
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user): RedirectResponse
    {
        return $this->executeWithExceptionHandling(function() use ($user) {
            // Empêcher la suppression de son propre compte
            if ($user->id === auth()->id()) {
                return $this->redirectWithError(
                    'pages.admin.users.index',
                    'Vous ne pouvez pas supprimer votre propre compte.'
                );
            }
            
            $userData = [
                'name' => $user->name,
                'email' => $user->email,
                'ecole_id' => $user->ecole_id
            ];
            
            $this->logDelete('Utilisateur', $user->id, $userData);
            
            $user->delete();
            
            return $this->redirectWithSuccess(
                'pages.admin.users.index',
                $this->trans('admin.success.deleted', ['item' => "Utilisateur \"{$userData['name']}\""])
            );
            
        }, 'suppression utilisateur', ['user_id' => $user->id]);
    }
    
    /**
     * Export users
     */
    public function export(Request $request)
    {
        return $this->executeWithExceptionHandling(function() use ($request) {
            $query = User::with(['ecole', 'roles']);
            
            // Filtrage selon les permissions multi-tenant
            if (!auth()->user()->hasRole('superadmin')) {
                $query->where('ecole_id', auth()->user()->ecole_id);
            }
            
            // Appliquer les mêmes filtres que l'index
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }
            
            return $this->exportToExcel(
                $query,
                ['Nom', 'Email', 'École', 'Rôle', 'Statut', 'Date création'],
                function($user) {
                    return [
                        $user->name,
                        $user->email,
                        $user->ecole?->nom ?? 'N/A',
                        $user->roles->pluck('name')->implode(', '),
                        $user->active ? 'Actif' : 'Inactif',
                        $user->created_at->format('d/m/Y H:i')
                    ];
                },
                'utilisateurs_' . date('Y-m-d') . '.xlsx',
                'Liste des Utilisateurs'
            );
            
        }, 'export utilisateurs');
    }

    /**
     * Generate QR Code for user
     */
    public function qrcode(User $user)
    {
        return $this->executeWithExceptionHandling(function() use ($user) {
            // Génération QR Code (à implémenter selon vos besoins)
            $qrData = [
                'user_id' => $user->id,
                'name' => $user->name,
                'ecole' => $user->ecole?->nom,
                'generated_at' => now()->toISOString()
            ];
            
            $this->logBusinessAction('QR Code généré', 'info', [
                'user_id' => $user->id,
                'qr_data' => $qrData
            ]);
            
            return $this->apiResponse($qrData, 'QR Code généré pour ' . $user->name);
            
        }, 'génération QR code', ['user_id' => $user->id]);
    }
}
