<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseAdminController;
use App\Models\User;
use App\Models\Ecole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class AdminUserController extends BaseAdminController
{
    /**
     * Appliquer le scope multi-tenant aux requêtes
     */
    private function scopeToUserEcole($query)
    {
        $user = auth()->user();
        
        // SuperAdmin voit tout
        if ($user->hasRole('superadmin')) {
            return $query;
        }
        
        // Autres rôles : limités à leur école
        return $query->where('ecole_id', $user->ecole_id);
    }

    /**
     * Liste des utilisateurs avec filtres et pagination
     */
    public function index(Request $request)
    {
        try {
            $query = User::with(['ecole', 'roles']);
            
            // Appliquer le scope multi-tenant
            $query = $this->scopeToUserEcole($query);
            
            // Filtres de recherche
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }
            
            if ($request->filled('role')) {
                $query->whereHas('roles', function($q) use ($request) {
                    $q->where('name', $request->role);
                });
            }
            
            if ($request->filled('ecole_id')) {
                $query->where('ecole_id', $request->ecole_id);
            }
            
            if ($request->filled('status')) {
                if ($request->status === 'active') {
                    $query->whereNotNull('email_verified_at');
                } elseif ($request->status === 'inactive') {
                    $query->whereNull('email_verified_at');
                }
            }
            
            // Pagination
            $users = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
            
            // Données pour les filtres
            $filterData = [
                'roles' => Role::pluck('name', 'name'),
                'ecoles' => auth()->user()->hasRole('superadmin') 
                    ? Ecole::where('actif', true)->pluck('nom', 'id')
                    : collect()
            ];
            
            return view('admin.users.index', compact('users', 'filterData'));
            
        } catch (\Exception $e) {
            \Log::error('Erreur users.index: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return view('admin.users.index', [
                'users' => collect()->paginate(15),
                'filterData' => ['roles' => collect(), 'ecoles' => collect()]
            ])->with('error', 'Erreur de chargement des utilisateurs');
        }
    }

    /**
     * Formulaire de création d'utilisateur
     */
    public function create()
    {
        try {
            $user = auth()->user();
            
            // Récupérer les écoles selon les permissions
            if ($user->hasRole('superadmin')) {
                $ecoles = Ecole::where('actif', true)->orderBy('nom')->get();
            } else {
                $ecoles = Ecole::where('id', $user->ecole_id)->get();
            }
            
            // Récupérer tous les rôles
            $roles = Role::orderBy('name')->get();
            
            return view('admin.users.create', compact('ecoles', 'roles'));
            
        } catch (\Exception $e) {
            \Log::error('Erreur users.create: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors du chargement du formulaire');
        }
    }

    /**
     * Enregistrement d'un nouvel utilisateur
     */
    public function store(Request $request)
    {
        try {
            $user = auth()->user();
            
            $rules = [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8|confirmed',
                'role' => 'required|exists:roles,name'
            ];
            
            // Validation école selon les permissions
            if ($user->hasRole('superadmin')) {
                $rules['ecole_id'] = 'required|exists:ecoles,id';
            }
            
            $validated = $request->validate($rules);

            DB::beginTransaction();
            
            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'email_verified_at' => now()
            ];
            
            // Gestion école selon permissions
            if ($user->hasRole('superadmin') && isset($validated['ecole_id'])) {
                $userData['ecole_id'] = $validated['ecole_id'];
            } else {
                $userData['ecole_id'] = $user->ecole_id;
            }
            
            $newUser = User::create($userData);
            $newUser->assignRole($validated['role']);
            
            // Log de l'activité
            $this->logActivity("Utilisateur créé: {$newUser->name} ({$newUser->email})", $newUser);
            
            DB::commit();
            
            return redirect()->route('admin.users.index')
                ->with('success', "Utilisateur {$newUser->name} créé avec succès");
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erreur users.store: ' . $e->getMessage(), [
                'request_data' => $request->except('password')
            ]);
            
            return back()
                ->withInput($request->except('password'))
                ->with('error', 'Erreur lors de la création: ' . $e->getMessage());
        }
    }

    /**
     * Affichage des détails d'un utilisateur
     */
    public function show(User $user)
    {
        try {
            // Vérifier les permissions multi-tenant
            if (!auth()->user()->hasRole('superadmin') && $user->ecole_id !== auth()->user()->ecole_id) {
                abort(403, 'Accès non autorisé à cet utilisateur');
            }
            
            $user->load(['ecole', 'roles', 'permissions']);
            
            // Statistiques utilisateur
            $stats = [
                'total_cours' => $user->cours()->count() ?? 0,
                'total_presences' => $user->presences()->count() ?? 0,
                'total_paiements' => $user->paiements()->count() ?? 0,
                'progression_ceintures' => $user->userCeintures()->where('valide', true)->count() ?? 0,
                'derniere_connexion' => $user->last_login_at ?? 'Jamais',
                'compte_cree' => $user->created_at->format('d/m/Y H:i'),
                'derniere_modification' => $user->updated_at->format('d/m/Y H:i')
            ];
            
            return view('admin.users.show', compact('user', 'stats'));
            
        } catch (\Exception $e) {
            \Log::error('Erreur users.show: ' . $e->getMessage(), ['user_id' => $user->id]);
            return back()->with('error', 'Erreur lors de l\'affichage de l\'utilisateur');
        }
    }

    /**
     * Formulaire d'édition d'utilisateur
     */
    public function edit(User $user)
    {
        try {
            // Vérifier les permissions multi-tenant
            if (!auth()->user()->hasRole('superadmin') && $user->ecole_id !== auth()->user()->ecole_id) {
                abort(403, 'Accès non autorisé à cet utilisateur');
            }
            
            $currentUser = auth()->user();
            
            // Récupérer les écoles selon les permissions
            if ($currentUser->hasRole('superadmin')) {
                $ecoles = Ecole::where('actif', true)->orderBy('nom')->get();
            } else {
                $ecoles = Ecole::where('id', $currentUser->ecole_id)->get();
            }
            
            $roles = Role::orderBy('name')->get();
            $user->load(['roles']);
            
            return view('admin.users.edit', compact('user', 'ecoles', 'roles'));
            
        } catch (\Exception $e) {
            \Log::error('Erreur users.edit: ' . $e->getMessage(), ['user_id' => $user->id]);
            return back()->with('error', 'Erreur lors du chargement du formulaire d\'édition');
        }
    }

    /**
     * Mise à jour d'un utilisateur
     */
    public function update(Request $request, User $user)
    {
        try {
            // Vérifier les permissions multi-tenant
            if (!auth()->user()->hasRole('superadmin') && $user->ecole_id !== auth()->user()->ecole_id) {
                abort(403, 'Accès non autorisé à cet utilisateur');
            }
            
            $currentUser = auth()->user();
            
            $rules = [
                'name' => 'required|string|max:255',
                'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
                'password' => 'nullable|min:8|confirmed',
                'role' => 'required|exists:roles,name'
            ];
            
            // Validation école selon les permissions
            if ($currentUser->hasRole('superadmin')) {
                $rules['ecole_id'] = 'required|exists:ecoles,id';
            }
            
            $validated = $request->validate($rules);

            DB::beginTransaction();
            
            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email']
            ];
            
            // Mise à jour mot de passe si fourni
            if (!empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }
            
            // Gestion école selon permissions
            if ($currentUser->hasRole('superadmin') && isset($validated['ecole_id'])) {
                $userData['ecole_id'] = $validated['ecole_id'];
            }
            
            $user->update($userData);
            $user->syncRoles([$validated['role']]);
            
            // Log de l'activité
            $this->logActivity("Utilisateur modifié: {$user->name} ({$user->email})", $user);
            
            DB::commit();
            
            return redirect()->route('admin.users.index')
                ->with('success', "Utilisateur {$user->name} mis à jour avec succès");
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erreur users.update: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'request_data' => $request->except('password')
            ]);
            
            return back()
                ->withInput($request->except('password'))
                ->with('error', 'Erreur lors de la mise à jour: ' . $e->getMessage());
        }
    }

    /**
     * Suppression d'un utilisateur
     */
    public function destroy(User $user)
    {
        try {
            // Vérifier les permissions multi-tenant
            if (!auth()->user()->hasRole('superadmin') && $user->ecole_id !== auth()->user()->ecole_id) {
                abort(403, 'Accès non autorisé à cet utilisateur');
            }
            
            // Empêcher la suppression de son propre compte
            if ($user->id === auth()->id()) {
                return back()->with('error', 'Impossible de supprimer votre propre compte');
            }
            
            // Empêcher la suppression du super admin principal
            if ($user->hasRole('superadmin') && $user->email === 'lalpha@4lb.ca') {
                return back()->with('error', 'Impossible de supprimer le super administrateur principal');
            }
            
            $userName = $user->name;
            
            // Log avant suppression
            $this->logActivity("Utilisateur supprimé: {$userName} ({$user->email})");
            
            $user->delete();
            
            return redirect()->route('admin.users.index')
                ->with('success', "Utilisateur {$userName} supprimé avec succès");
                
        } catch (\Exception $e) {
            \Log::error('Erreur users.destroy: ' . $e->getMessage(), ['user_id' => $user->id]);
            return back()->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }

    /**
     * Export des utilisateurs
     */
    public function export(Request $request)
    {
        try {
            $query = User::with(['ecole', 'roles']);
            $query = $this->scopeToUserEcole($query);
            
            // Appliquer les mêmes filtres que la liste
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }
            
            $users = $query->get();
            
            return response()->json([
                'success' => true,
                'message' => 'Export utilisateurs préparé',
                'count' => $users->count(),
                'filters' => $request->all(),
                'note' => 'Export Excel/PDF sera implémenté prochainement'
            ]);
            
        } catch (\Exception $e) {
            return $this->errorResponse('Erreur lors de l\'export: ' . $e->getMessage());
        }
    }

    /**
     * Basculer le statut actif/inactif d'un utilisateur
     */
    public function toggleStatus(User $user)
    {
        try {
            // Vérifier les permissions
            if (!auth()->user()->hasRole('superadmin') && $user->ecole_id !== auth()->user()->ecole_id) {
                return $this->errorResponse('Accès non autorisé', 403);
            }
            
            if ($user->id === auth()->id()) {
                return $this->errorResponse('Impossible de modifier votre propre statut');
            }
            
            $newStatus = $user->email_verified_at ? null : now();
            $user->update(['email_verified_at' => $newStatus]);
            
            $status = $newStatus ? 'activé' : 'désactivé';
            
            return $this->successResponse(
                ['status' => $newStatus ? 'active' : 'inactive'],
                "Utilisateur {$status} avec succès"
            );
            
        } catch (\Exception $e) {
            return $this->errorResponse('Erreur lors du changement de statut');
        }
    }

    /**
     * Réinitialiser le mot de passe d'un utilisateur
     */
    public function resetPassword(User $user)
    {
        try {
            // Vérifier les permissions
            if (!auth()->user()->hasRole('superadmin') && $user->ecole_id !== auth()->user()->ecole_id) {
                return $this->errorResponse('Accès non autorisé', 403);
            }
            
            $newPassword = 'password123'; // Temporaire
            $user->update(['password' => Hash::make($newPassword)]);
            
            // Log de l'activité
            $this->logActivity("Mot de passe réinitialisé pour: {$user->name}", $user);
            
            return $this->successResponse(
                ['temporary_password' => $newPassword],
                'Mot de passe réinitialisé avec succès'
            );
            
        } catch (\Exception $e) {
            return $this->errorResponse('Erreur lors de la réinitialisation');
        }
    }
}