<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
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
        $query = User::with(['ecole', 'roles'])
            ->when(auth()->user()->ecole_id, function ($q, $ecole_id) {
                return $q->where('ecole_id', $ecole_id);
            });

        // Filtres
        if ($request->filled('ecole_id')) {
            $query->where('ecole_id', $request->ecole_id);
        }

        if ($request->filled('role')) {
            $query->whereHas('roles', fn($q) => $q->where('name', $request->role));
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('active')) {
            $query->where('active', $request->boolean('active'));
        }

        $users = $query->paginate(15)->withQueryString();

        // Données pour filtres
        $ecoles = auth()->user()->ecole_id 
            ? collect([auth()->user()->ecole])
            : Ecole::active()->get();
            
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'ecoles', 'roles'));
    }

    public function show(User $user)
    {
        $this->authorize('view', $user);
        
        $user->load([
            'ecole',
            'roles',
            'membre_ceintures.ceinture',
            'inscriptions_cours.cours',
            'presences' => fn($q) => $q->latest()->limit(10),
            'paiements' => fn($q) => $q->latest()->limit(5)
        ]);

        $stats = [
            'total_presences' => $user->presences()->where('present', true)->count(),
            'cours_inscrits' => $user->inscriptions_cours()->count(),
            'derniere_presence' => $user->presences()->where('present', true)->latest('date_cours')->first()?->date_cours,
            'paiements_total' => $user->paiements()->where('statut', 'valide')->sum('montant'),
        ];

        return view('admin.users.show', compact('user', 'stats'));
    }

    public function create()
    {
        $ecoles = auth()->user()->ecole_id 
            ? collect([auth()->user()->ecole])
            : Ecole::active()->get();
            
        $roles = Role::whereNotIn('name', ['superadmin'])->get();

        return view('admin.users.create', compact('ecoles', 'roles'));
    }

    public function store(UserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = bcrypt($data['password']);
        
        // Multi-tenant: forcer ecole_id si admin d'école
        if (auth()->user()->ecole_id) {
            $data['ecole_id'] = auth()->user()->ecole_id;
        }

        $user = User::create($data);
        
        // Assigner rôle par défaut
        $role = $request->input('role', 'membre');
        $user->assignRole($role);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->log('Utilisateur créé');

        return redirect()
            ->route('admin.users.index')
            ->with('success', "Utilisateur {$user->name} créé avec succès.");
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);
        
        $ecoles = auth()->user()->ecole_id 
            ? collect([auth()->user()->ecole])
            : Ecole::active()->get();
            
        $roles = Role::whereNotIn('name', ['superadmin'])->get();

        return view('admin.users.edit', compact('user', 'ecoles', 'roles'));
    }

    public function update(UserRequest $request, User $user)
    {
        $this->authorize('update', $user);
        
        $data = $request->validated();
        
        // Ne pas écraser le mot de passe si vide
        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = bcrypt($data['password']);
        }

        // Multi-tenant: protection ecole_id
        if (auth()->user()->ecole_id && $user->ecole_id !== auth()->user()->ecole_id) {
            abort(403, 'Accès refusé: utilisateur d\'une autre école');
        }

        $user->update($data);

        // Mettre à jour rôle si changé
        if ($request->filled('role')) {
            $user->syncRoles([$request->role]);
        }

        activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->log('Utilisateur modifié');

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', "Utilisateur {$user->name} modifié avec succès.");
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        
        // Protection: ne pas supprimer soi-même
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        // Protection: ne pas supprimer superadmin
        if ($user->hasRole('superadmin')) {
            return back()->with('error', 'Impossible de supprimer un super administrateur.');
        }

        $name = $user->name;
        
        activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->log('Utilisateur supprimé');
            
        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', "Utilisateur {$name} supprimé avec succès.");
    }

    public function qrcode(User $user)
    {
        $this->authorize('view', $user);
        
        // Générer QR code pour présences
        $qrData = [
            'user_id' => $user->id,
            'ecole_id' => $user->ecole_id,
            'timestamp' => now()->timestamp,
            'hash' => hash('sha256', $user->id . $user->email . config('app.key'))
        ];

        return response()->json($qrData);
    }

    public function export(Request $request)
    {
        $this->authorize('export-users');
        
        $query = User::with(['ecole', 'roles'])
            ->when(auth()->user()->ecole_id, fn($q, $ecole_id) => $q->where('ecole_id', $ecole_id));

        // Mêmes filtres que index
        if ($request->filled('ecole_id')) {
            $query->where('ecole_id', $request->ecole_id);
        }

        $users = $query->get();

        // TODO: Implémenter export Excel avec Maatwebsite
        return response()->download(
            storage_path('app/exports/users_' . now()->format('Y-m-d') . '.xlsx')
        );
    }
}
