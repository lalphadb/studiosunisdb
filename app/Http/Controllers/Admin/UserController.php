<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Models\User;
use App\Models\Ecole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

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
        $users = User::with(['ecole', 'roles'])->paginate(15);
        
        // Ajouter la liste des rôles pour le filtre
        $roles = [
            'membre' => 'Membre',
            'instructeur' => 'Instructeur',
            'admin' => 'Admin',
            'admin_ecole' => 'Admin École',
            'superadmin' => 'Superadmin'
        ];
        
        return view('admin.users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $user = auth()->user();
        $ecoles = Ecole::all();
        
        // Logique hiérarchique des rôles basée sur le rôle de l'utilisateur connecté
        $availableRoles = $this->getAvailableRoles($user);
        
        // Récupérer les chefs de famille potentiels (membres sans famille_principale_id)
        $chefsDesFamilles = User::whereNull('famille_principale_id')
                                ->where('active', true)
                                ->orderBy('name')
                                ->get();
        
        return view('admin.users.create', compact('ecoles', 'availableRoles', 'chefsDesFamilles'));
    }

    public function store(UserRequest $request)
    {
        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);
        
        // Retirer le rôle des données validées car on va l'assigner séparément
        $role = $validated['role'];
        unset($validated['role']);
        
        // Vérification que le rôle demandé est autorisé
        $availableRoles = $this->getAvailableRoles(auth()->user());
        if (!in_array($role, array_keys($availableRoles))) {
            abort(403, 'Vous n\'êtes pas autorisé à créer un utilisateur avec ce rôle.');
        }
        
        // Gérer la famille - si un chef de famille est assigné, ce membre ne peut pas avoir ses propres membres
        if ($validated['famille_principale_id']) {
            // Vérifier que le chef de famille choisi n'a pas lui-même un chef de famille
            $chefDeFamille = User::find($validated['famille_principale_id']);
            if ($chefDeFamille && $chefDeFamille->famille_principale_id) {
                return back()->withErrors(['famille_principale_id' => 'Le chef de famille sélectionné fait déjà partie d\'une autre famille.'])->withInput();
            }
        }
        
        $user = User::create($validated);
        $user->assignRole($role);
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }

    public function show(User $user)
    {
        // Charger les relations nécessaires
        $user->load([
            'ecole', 
            'roles', 
            'famillePrincipale', 
            'membresFamille.roles',
            'inscriptions_seminaires.seminaire',
            'presences'
        ]);
        
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $ecoles = Ecole::all();
        $availableRoles = $this->getAvailableRoles(auth()->user());
        
        // Récupérer les chefs de famille potentiels
        $chefsDesFamilles = User::whereNull('famille_principale_id')
                                ->where('id', '!=', $user->id) // Exclure l'utilisateur actuel
                                ->where('active', true)
                                ->orderBy('name')
                                ->get();
        
        return view('admin.users.edit', compact('user', 'ecoles', 'availableRoles', 'chefsDesFamilles'));
    }

    public function update(UserRequest $request, User $user)
    {
        $validated = $request->validated();
        
        // Retirer le rôle des données validées
        $role = $validated['role'];
        unset($validated['role']);
        
        // Vérification que le rôle demandé est autorisé
        $availableRoles = $this->getAvailableRoles(auth()->user());
        if (!in_array($role, array_keys($availableRoles))) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier vers ce rôle.');
        }
        
        // Gérer la logique famille
        if (isset($validated['famille_principale_id'])) {
            // Si on assigne ce membre à une famille
            if ($validated['famille_principale_id']) {
                // Vérifier que le chef de famille choisi n'a pas lui-même un chef de famille
                $chefDeFamille = User::find($validated['famille_principale_id']);
                if ($chefDeFamille && $chefDeFamille->famille_principale_id) {
                    return back()->withErrors(['famille_principale_id' => 'Le chef de famille sélectionné fait déjà partie d\'une autre famille.'])->withInput();
                }
                
                // Si ce membre avait ses propres membres de famille, les détacher
                if ($user->membresFamille->count() > 0) {
                    $user->membresFamille()->update(['famille_principale_id' => null]);
                }
            }
        }
        
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }
        
        $user->update($validated);
        $user->syncRoles([$role]);
        
        return redirect()->route('admin.users.show', $user)
            ->with('success', 'Utilisateur mis à jour avec succès.');
    }

    public function destroy(User $user)
    {
        // Si ce membre a des membres de famille, les détacher
        if ($user->membresFamille->count() > 0) {
            $user->membresFamille()->update(['famille_principale_id' => null]);
        }
        
        $user->delete();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }

    /**
     * Retourne les rôles disponibles selon le rôle de l'utilisateur connecté
     */
    private function getAvailableRoles(User $user): array
    {
        if ($user->hasRole('superadmin')) {
            return [
                'membre' => 'Membre',
                'instructeur' => 'Instructeur', 
                'admin' => 'Admin',
                'admin_ecole' => 'Admin École',
                'superadmin' => 'Superadmin'
            ];
        } elseif ($user->hasRole('admin_ecole')) {
            return [
                'membre' => 'Membre',
                'instructeur' => 'Instructeur',
                'admin' => 'Admin'
            ];
        } elseif ($user->hasRole('admin')) {
            return [
                'membre' => 'Membre',
                'instructeur' => 'Instructeur'
            ];
        }
        
        return [];
    }
}
