<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ecole;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class EcoleController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            'verified',
            new Middleware('can:view-ecoles', only: ['index', 'show']),
            new Middleware('can:create-ecole', only: ['create', 'store']),
            new Middleware('can:edit-ecole', only: ['edit', 'update']),
            new Middleware('can:delete-ecole', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Multi-tenant: SuperAdmin voit tout, Admin voit son école seulement
        $ecolesQuery = Ecole::withCount(['users', 'cours', 'paiements'])
            ->with(['users' => function($q) {
                $q->whereHas('roles', fn($r) => $r->where('name', 'admin'));
            }]);

        // SuperAdmin voit toutes les écoles
        if (!$user->isSuperAdmin() && $user->ecole_id) {
            $ecolesQuery->where('id', $user->ecole_id);
        }

        $ecoles = $ecolesQuery->orderBy('nom')->get();

        // Métriques globales pour SuperAdmin
        $metrics = [];
        if ($user->isSuperAdmin()) {
            $metrics = [
                'total_ecoles' => Ecole::where('active', true)->count(),
                'total_users' => User::whereNotNull('ecole_id')->count(),
                'total_admins' => User::whereHas('roles', fn($q) => $q->where('name', 'admin'))->count(),
                'ecoles_actives' => Ecole::where('active', true)->count(),
            ];
        } else {
            // Métriques pour Admin d'école
            $ecole = $user->ecole;
            $metrics = [
                'users_ecole' => $ecole->users()->count(),
                'cours_actifs' => $ecole->cours()->where('active', true)->count(),
                'instructeurs' => $ecole->users()->whereHas('roles', fn($q) => $q->where('name', 'instructeur'))->count(),
                'membres_actifs' => $ecole->users()->where('active', true)->whereHas('roles', fn($q) => $q->where('name', 'membre'))->count(),
            ];
        }

        return view('admin.ecoles.index', compact('ecoles', 'metrics'));
    }

    public function show(Ecole $ecole)
    {
        $user = auth()->user();
        
        // Vérification multi-tenant
        if (!$user->isSuperAdmin() && $user->ecole_id !== $ecole->id) {
            abort(403, 'Accès non autorisé à cette école.');
        }

        $ecole->load([
            'users.roles',
            'cours' => fn($q) => $q->where('active', true)->withCount('inscriptions'),
            'seminaires' => fn($q) => $q->where('date_debut', '>=', now())->withCount('inscriptions'),
        ]);

        // Statistiques détaillées
        $stats = [
            'total_users' => $ecole->users()->count(),
            'admins' => $ecole->users()->whereHas('roles', fn($q) => $q->where('name', 'admin'))->count(),
            'instructeurs' => $ecole->users()->whereHas('roles', fn($q) => $q->where('name', 'instructeur'))->count(),
            'membres' => $ecole->users()->whereHas('roles', fn($q) => $q->where('name', 'membre'))->count(),
            'cours_actifs' => $ecole->cours()->where('active', true)->count(),
            'seminaires_prevus' => $ecole->seminaires()->where('date_debut', '>=', now())->count(),
            'paiements_mois' => $ecole->paiements()->whereMonth('created_at', now()->month)->sum('montant'),
        ];

        return view('admin.ecoles.show', compact('ecole', 'stats'));
    }

    public function create()
    {
        // Seulement SuperAdmin peut créer des écoles
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Seul le SuperAdmin peut créer des écoles.');
        }

        return view('admin.ecoles.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Seul le SuperAdmin peut créer des écoles.');
        }

        $validated = $request->validate([
            'nom' => 'required|string|max:191|unique:ecoles,nom',
            'code' => 'required|string|max:10|unique:ecoles,code',
            'adresse' => 'required|string|max:191',
            'ville' => 'required|string|max:191',
            'province' => 'string|max:191',
            'code_postal' => 'required|string|max:191',
            'telephone' => 'nullable|string|max:191',
            'email' => 'nullable|email|max:191',
            'site_web' => 'nullable|url|max:191',
            'description' => 'nullable|string',
            'active' => 'boolean',
        ]);

        $validated['province'] = $validated['province'] ?? 'QC';
        $validated['active'] = $request->boolean('active', true);
        $validated['code'] = strtoupper($validated['code']);

        $ecole = Ecole::create($validated);

        return redirect()
            ->route('admin.ecoles.show', $ecole)
            ->with('success', "École '{$ecole->nom}' créée avec succès.");
    }

    public function edit(Ecole $ecole)
    {
        $user = auth()->user();
        
        // SuperAdmin peut éditer toutes les écoles, Admin seulement la sienne
        if (!$user->isSuperAdmin() && $user->ecole_id !== $ecole->id) {
            abort(403, 'Vous ne pouvez éditer que votre école.');
        }

        return view('admin.ecoles.edit', compact('ecole'));
    }

    public function update(Request $request, Ecole $ecole)
    {
        $user = auth()->user();
        
        if (!$user->isSuperAdmin() && $user->ecole_id !== $ecole->id) {
            abort(403, 'Vous ne pouvez éditer que votre école.');
        }

        $validated = $request->validate([
            'nom' => 'required|string|max:191|unique:ecoles,nom,' . $ecole->id,
            'code' => 'required|string|max:10|unique:ecoles,code,' . $ecole->id,
            'adresse' => 'required|string|max:191',
            'ville' => 'required|string|max:191',
            'province' => 'string|max:191',
            'code_postal' => 'required|string|max:191',
            'telephone' => 'nullable|string|max:191',
            'email' => 'nullable|email|max:191',
            'site_web' => 'nullable|url|max:191',
            'description' => 'nullable|string',
            'active' => 'boolean',
        ]);

        // Seul SuperAdmin peut désactiver une école
        if (!$user->isSuperAdmin()) {
            unset($validated['active']);
        } else {
            $validated['active'] = $request->boolean('active');
        }

        $validated['code'] = strtoupper($validated['code']);

        $ecole->update($validated);

        return redirect()
            ->route('admin.ecoles.show', $ecole)
            ->with('success', "École '{$ecole->nom}' mise à jour avec succès.");
    }

    public function destroy(Ecole $ecole)
    {
        // Seulement SuperAdmin peut supprimer
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Seul le SuperAdmin peut supprimer des écoles.');
        }

        // Vérifier s'il y a des utilisateurs
        if ($ecole->users()->count() > 0) {
            return redirect()
                ->route('admin.ecoles.index')
                ->with('error', "Impossible de supprimer l'école '{$ecole->nom}' car elle contient des utilisateurs.");
        }

        $nom = $ecole->nom;
        $ecole->delete();

        return redirect()
            ->route('admin.ecoles.index')
            ->with('success', "École '{$nom}' supprimée avec succès.");
    }
}
