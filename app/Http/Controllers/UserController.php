<?php

namespace App\Http\Controllers;

use App\Models\Ceinture;
use App\Models\Ecole;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(User::class, 'user');
    }

    public function index(Request $request)
    {
        $authUser = Auth::user();
        if (! $authUser) {
            abort(401, 'Non authentifié');
        }

        $query = User::query()
            ->with(['roles', 'ecole', 'ceintureActuelle'])
            ->where('ecole_id', $authUser->ecole_id);

        // Filtres
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('prenom', 'like', "%{$search}%")
                    ->orWhere('nom', 'like', "%{$search}%")
                    ->orWhere('telephone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->input('role'));
            });
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->input('statut'));
        }

        if ($request->filled('active')) {
            $query->where('active', $request->input('active'));
        }

        if ($request->filled('ceinture_id')) {
            $query->where('ceinture_actuelle_id', $request->input('ceinture_id'));
        }

        // Tri
        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $users = $query->paginate(15)->withQueryString();

        // Statistiques
        $stats = [
            'total' => User::where('ecole_id', $authUser->ecole_id)->count(),
            'actifs' => User::where('ecole_id', $authUser->ecole_id)->where('active', true)->where('statut', 'actif')->count(),
            'membres' => User::where('ecole_id', $authUser->ecole_id)
                ->whereHas('roles', function ($q) {
                    $q->where('name', 'membre');
                })->count(),
            'admins' => User::where('ecole_id', $authUser->ecole_id)
                ->whereHas('roles', function ($q) {
                    $q->whereIn('name', ['superadmin', 'admin_ecole', 'instructeur']);
                })->count(),
        ];

        return Inertia::render('Users/Index', [
            'users' => $users,
            'filters' => $request->only(['search', 'role', 'statut', 'active', 'sort', 'direction', 'ceinture_id']),
            'roles' => Role::where('guard_name', 'web')->pluck('name'),
            'ceintures' => Ceinture::orderBy('order')->get(['id', 'name', 'color_hex']),
            'stats' => $stats,
            'can' => [
                'create' => $authUser->can('create', User::class),
                'update' => $authUser->can('update', User::class),
                'delete' => $authUser->can('delete', User::class),
                'export' => $authUser->can('viewAny', User::class),
            ],
        ]);
    }

    public function create()
    {
        $authUser = Auth::user();
        if (! $authUser) {
            abort(401, 'Non authentifié');
        }

        return Inertia::render('Users/Form', [
            'user' => null,
            'roles' => Role::where('guard_name', 'web')
                ->when(! $authUser->hasRole('superadmin'), function ($query) {
                    $query->where('name', '!=', 'superadmin');
                })
                ->pluck('name'),
            'ecoles' => $authUser->hasRole('superadmin')
                ? Ecole::all()
                : [$authUser->ecole],
            'ceintures' => Ceinture::orderBy('order')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $authUser = Auth::user();
        if (! $authUser) {
            abort(401, 'Non authentifié');
        }

        $validated = $request->validate([
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->where(function ($query) use ($authUser) {
                    return $query->where('ecole_id', $authUser->ecole_id);
                }),
            ],
            'password' => 'required|string|min:8|confirmed',
            'telephone' => 'nullable|string|max:255',
            'date_naissance' => 'nullable|date',
            'sexe' => 'nullable|in:M,F,Autre',
            'adresse' => 'nullable|string',
            'ville' => 'nullable|string|max:255',
            'code_postal' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'contact_urgence_nom' => 'nullable|string|max:255',
            'contact_urgence_telephone' => 'nullable|string|max:255',
            'contact_urgence_relation' => 'nullable|string|max:255',
            'role' => 'required|exists:roles,name',
            'ecole_id' => $authUser->hasRole('superadmin')
                ? 'required|exists:ecoles,id'
                : 'nullable',
            'ceinture_actuelle_id' => 'nullable|exists:ceintures,id',
            'notes_medicales' => 'nullable|string',
            'allergies' => 'nullable|array',
            'medicaments' => 'nullable|array',
            'consentement_photos' => 'boolean',
            'consentement_communications' => 'boolean',
        ]);

        // Empêcher l'attribution du rôle superadmin par un non-superadmin
        if (! $authUser->hasRole('superadmin') && $validated['role'] === 'superadmin') {
            abort(403, 'Vous ne pouvez pas créer un superadmin.');
        }

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $validated['prenom'].' '.$validated['nom'],
                'prenom' => $validated['prenom'],
                'nom' => $validated['nom'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'telephone' => $validated['telephone'] ?? null,
                'date_naissance' => $validated['date_naissance'] ?? null,
                'sexe' => $validated['sexe'] ?? 'Autre',
                'adresse' => $validated['adresse'] ?? null,
                'ville' => $validated['ville'] ?? null,
                'code_postal' => $validated['code_postal'] ?? null,
                'province' => $validated['province'] ?? 'QC',
                'contact_urgence_nom' => $validated['contact_urgence_nom'] ?? null,
                'contact_urgence_telephone' => $validated['contact_urgence_telephone'] ?? null,
                'contact_urgence_relation' => $validated['contact_urgence_relation'] ?? null,
                'ecole_id' => $validated['ecole_id'] ?? $authUser->ecole_id,
                'active' => true,
                'statut' => 'actif',
                'ceinture_actuelle_id' => $validated['ceinture_actuelle_id'] ?? null,
                'date_inscription' => now(),
                'notes_medicales' => $validated['notes_medicales'] ?? null,
                'allergies' => $validated['allergies'] ?? null,
                'medicaments' => $validated['medicaments'] ?? null,
                'consentement_photos' => $validated['consentement_photos'] ?? false,
                'consentement_communications' => $validated['consentement_communications'] ?? true,
                'date_consentement' => ($validated['consentement_photos'] || $validated['consentement_communications']) ? now() : null,
            ]);

            $user->assignRole($validated['role']);

            DB::commit();

            return redirect()->route('users.index')
                ->with('success', 'Utilisateur créé avec succès.');
        } catch (\Exception $e) {
            DB::rollback();

            return back()->withErrors(['error' => 'Erreur lors de la création: '.$e->getMessage()]);
        }
    }

    public function show(User $user)
    {
        $authUser = Auth::user();
        if (! $authUser) {
            abort(401, 'Non authentifié');
        }

        $user->load(['roles', 'ecole', 'ceintureActuelle', 'family']);

        return Inertia::render('Users/Show', [
            'user' => $user,
            'can' => [
                'edit' => $authUser->can('update', $user),
                'delete' => $authUser->can('delete', $user),
            ],
        ]);
    }

    public function edit(User $user)
    {
        $authUser = Auth::user();
        if (! $authUser) {
            abort(401, 'Non authentifié');
        }

        return Inertia::render('Users/Form', [
            'user' => $user->load(['roles', 'ceintureActuelle']),
            'roles' => Role::where('guard_name', 'web')
                ->when(! $authUser->hasRole('superadmin'), function ($query) {
                    $query->where('name', '!=', 'superadmin');
                })
                ->pluck('name'),
            'ecoles' => $authUser->hasRole('superadmin')
                ? Ecole::all()
                : [$authUser->ecole],
            'ceintures' => Ceinture::orderBy('order')->get(),
        ]);
    }

    public function update(Request $request, User $user)
    {
        $authUser = Auth::user();
        if (! $authUser) {
            abort(401, 'Non authentifié');
        }

        // Empêcher la modification de son propre compte (protection)
        if ($user->id === $authUser->id && $request->has('active') && ! $request->input('active')) {
            return back()->withErrors(['error' => 'Vous ne pouvez pas désactiver votre propre compte.']);
        }

        $validated = $request->validate([
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id)->where(function ($query) use ($user) {
                    return $query->where('ecole_id', $user->ecole_id);
                }),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'telephone' => 'nullable|string|max:255',
            'date_naissance' => 'nullable|date',
            'sexe' => 'nullable|in:M,F,Autre',
            'adresse' => 'nullable|string',
            'ville' => 'nullable|string|max:255',
            'code_postal' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'contact_urgence_nom' => 'nullable|string|max:255',
            'contact_urgence_telephone' => 'nullable|string|max:255',
            'contact_urgence_relation' => 'nullable|string|max:255',
            'role' => 'required|exists:roles,name',
            'active' => 'boolean',
            'statut' => 'in:actif,inactif,suspendu',
            'ceinture_actuelle_id' => 'nullable|exists:ceintures,id',
            'notes_medicales' => 'nullable|string',
            'allergies' => 'nullable|array',
            'medicaments' => 'nullable|array',
            'consentement_photos' => 'boolean',
            'consentement_communications' => 'boolean',
        ]);

        // Empêcher la modification du rôle superadmin par un non-superadmin
        if (! $authUser->hasRole('superadmin') &&
            ($validated['role'] === 'superadmin' || $user->hasRole('superadmin'))) {
            abort(403, 'Vous ne pouvez pas modifier un superadmin.');
        }

        DB::beginTransaction();
        try {
            $updateData = [
                'name' => $validated['prenom'].' '.$validated['nom'],
                'prenom' => $validated['prenom'],
                'nom' => $validated['nom'],
                'email' => $validated['email'],
                'telephone' => $validated['telephone'],
                'date_naissance' => $validated['date_naissance'],
                'sexe' => $validated['sexe'] ?? 'Autre',
                'adresse' => $validated['adresse'],
                'ville' => $validated['ville'],
                'code_postal' => $validated['code_postal'],
                'province' => $validated['province'] ?? 'QC',
                'contact_urgence_nom' => $validated['contact_urgence_nom'],
                'contact_urgence_telephone' => $validated['contact_urgence_telephone'],
                'contact_urgence_relation' => $validated['contact_urgence_relation'],
                'active' => $validated['active'] ?? true,
                'statut' => $validated['statut'] ?? 'actif',
                'ceinture_actuelle_id' => $validated['ceinture_actuelle_id'],
                'notes_medicales' => $validated['notes_medicales'],
                'allergies' => $validated['allergies'],
                'medicaments' => $validated['medicaments'],
                'consentement_photos' => $validated['consentement_photos'] ?? false,
                'consentement_communications' => $validated['consentement_communications'] ?? true,
            ];

            if (! empty($validated['password'])) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            if ($validated['consentement_photos'] || $validated['consentement_communications']) {
                $updateData['date_consentement'] = now();
            }

            $user->update($updateData);

            // Mise à jour du rôle
            $user->syncRoles([$validated['role']]);

            DB::commit();

            return redirect()->route('users.index')
                ->with('success', 'Utilisateur mis à jour avec succès.');
        } catch (\Exception $e) {
            DB::rollback();

            return back()->withErrors(['error' => 'Erreur lors de la mise à jour: '.$e->getMessage()]);
        }
    }

    public function destroy(User $user)
    {
        $authUser = Auth::user();
        if (! $authUser) {
            abort(401, 'Non authentifié');
        }

        // Empêcher la suppression de son propre compte
        if ($user->id === $authUser->id) {
            return back()->withErrors(['error' => 'Vous ne pouvez pas supprimer votre propre compte.']);
        }

        // Empêcher la suppression d'un superadmin par un non-superadmin
        if (! $authUser->hasRole('superadmin') && $user->hasRole('superadmin')) {
            abort(403, 'Vous ne pouvez pas supprimer un superadmin.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }

    public function toggleActive(User $user)
    {
        $authUser = Auth::user();
        if (! $authUser) {
            abort(401, 'Non authentifié');
        }

        $this->authorize('update', $user);

        // Empêcher la désactivation de son propre compte
        if ($user->id === $authUser->id) {
            return back()->withErrors(['error' => 'Vous ne pouvez pas désactiver votre propre compte.']);
        }

        $user->update(['active' => ! $user->active]);

        return back()->with('success',
            $user->active ? 'Utilisateur activé.' : 'Utilisateur désactivé.'
        );
    }

    public function toggleStatus(User $user)
    {
        return $this->toggleActive($user);
    }

    public function resetPassword(Request $request, User $user)
    {
        $authUser = Auth::user();
        if (! $authUser) {
            abort(401, 'Non authentifié');
        }

        $this->authorize('update', $user);

        $validated = $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Mot de passe réinitialisé avec succès.');
    }

    /**
     * Export des utilisateurs
     */
    public function export(Request $request, $format = 'xlsx')
    {
        $authUser = Auth::user();
        if (! $authUser) {
            abort(401, 'Non authentifié');
        }

        $this->authorize('viewAny', User::class);

        // Construction de la requête avec les mêmes filtres que index()
        $query = User::query()
            ->with(['roles', 'ecole', 'ceintureActuelle'])
            ->where('ecole_id', $authUser->ecole_id);

        // Appliquer les filtres
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('prenom', 'like', "%{$search}%")
                    ->orWhere('nom', 'like', "%{$search}%")
                    ->orWhere('telephone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->input('role'));
            });
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->input('statut'));
        }

        // Tri
        $sortField = $request->input('sort', 'nom');
        $sortDirection = $request->input('direction', 'asc');
        $query->orderBy($sortField, $sortDirection);

        $users = $query->get();

        // Export simplifié sans classe dédiée pour l'instant
        if ($format === 'csv') {
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="utilisateurs_'.date('Y-m-d').'.csv"',
            ];

            $callback = function () use ($users) {
                $file = fopen('php://output', 'w');

                // En-têtes
                fputcsv($file, ['ID', 'Nom', 'Prénom', 'Email', 'Téléphone', 'Rôle', 'Statut', 'Date inscription']);

                // Données
                foreach ($users as $user) {
                    fputcsv($file, [
                        $user->id,
                        $user->nom,
                        $user->prenom,
                        $user->email,
                        $user->telephone,
                        $user->roles->first()?->name ?? '-',
                        $user->statut,
                        $user->created_at->format('Y-m-d'),
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        // Pour Excel, on retourne un CSV avec l'extension xlsx pour simplifier
        // (nécessiterait Laravel Excel pour un vrai export Excel)
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="utilisateurs_'.date('Y-m-d').'.xlsx"',
        ];

        // Pour l'instant, on retourne du CSV même pour xlsx
        $callback = function () use ($users) {
            $file = fopen('php://output', 'w');

            // En-têtes
            fputcsv($file, ['ID', 'Nom', 'Prénom', 'Email', 'Téléphone', 'Rôle', 'Statut', 'Ceinture', 'Date inscription']);

            // Données
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->nom,
                    $user->prenom,
                    $user->email,
                    $user->telephone,
                    $user->roles->first()?->name ?? '-',
                    $user->statut,
                    $user->ceintureActuelle?->name ?? '-',
                    $user->created_at->format('Y-m-d'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
