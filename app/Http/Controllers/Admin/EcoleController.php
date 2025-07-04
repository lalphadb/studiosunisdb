<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\StoreEcoleRequest;
use App\Http\Requests\Admin\UpdateEcoleRequest;
use App\Models\Ecole;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

/**
 * Contrôleur de gestion des écoles
 * 
 * Implémente le standard Laravel Admin Controllers v1.0
 * - Authorization via Policies uniquement
 * - Validation via FormRequests uniquement
 * - Multi-tenancy géré en Policy
 * - Logging des actions sensibles
 */
class EcoleController extends BaseAdminController
{
    /**
     * Initialise le contrôleur avec middleware can: selon le standard
     */
    public function __construct()
    {
        parent::__construct();
        
        // Standard: Authorization via middleware can: + Policies
        $this->middleware('can:viewAny,App\Models\Ecole')->only(['index']);
        $this->middleware('can:view,ecole')->only(['show']);
        $this->middleware('can:create,App\Models\Ecole')->only(['create', 'store']);
        $this->middleware('can:update,ecole')->only(['edit', 'update']);
        $this->middleware('can:delete,ecole')->only(['destroy']);
    }

    /**
     * Display a listing of schools
     */
    public function index(Request $request): View
    {
        try {
            $query = Ecole::withCount(['users', 'cours', 'sessions', 'paiements']);

            // Recherche
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nom', 'like', "%{$search}%")
                      ->orWhere('ville', 'like', "%{$search}%")
                      ->orWhere('contact_principal', 'like', "%{$search}%");
                });
            }

            // Filtres
            if ($request->filled('actif')) {
                $query->where('actif', $request->boolean('actif'));
            }

            if ($request->filled('province')) {
                $query->where('province', $request->province);
            }

            $ecoles = $query->orderBy('nom')->paginate(20);

            // Logging selon le standard
            Log::info('Consultation index écoles', [
                'user_id' => auth()->id(),
                'total_ecoles' => $ecoles->total(),
                'filters' => $request->only(['search', 'actif', 'province'])
            ]);

            return view('admin.ecoles.index', compact('ecoles'));

        } catch (\Exception $e) {
            Log::error('Erreur index écoles', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            return $this->redirectWithError('admin.dashboard', 'Erreur lors du chargement des écoles');
        }
    }

    /**
     * Show the form for creating a new school
     */
    public function create(): View
    {
        return view('admin.ecoles.create');
    }

    /**
     * Store a newly created school
     */
    public function store(StoreEcoleRequest $request): RedirectResponse
    {
        try {
            // Standard: Validation déjà faite par FormRequest
            $validated = $request->validated();

            // Gestion du logo si présent
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('ecoles/logos', 'public');
                $validated['logo'] = $logoPath;
            }

            $ecole = Ecole::create($validated);

            // Logging action sensible selon le standard
            Log::info('École créée', [
                'user_id' => auth()->id(),
                'ecole_id' => $ecole->id,
                'nom_ecole' => $ecole->nom
            ]);

            return $this->redirectWithSuccess('admin.ecoles.index', 
                'École "' . $ecole->nom . '" créée avec succès');

        } catch (\Exception $e) {
            Log::error('Erreur création école', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            return $this->redirectWithError('admin.ecoles.create', 'Erreur lors de la création');
        }
    }

    /**
     * Display the specified school
     */
    public function show(Ecole $ecole): View
    {
        $ecole->load(['users', 'cours', 'sessions']);
        
        // Statistiques pour la vue
        $stats = [
            'total_membres' => $ecole->users()->count(),
            'membres_actifs' => $ecole->users()->where('statut', 'actif')->count(),
            'total_cours' => $ecole->cours()->count(),
            'cours_actifs' => $ecole->cours()->where('active', true)->count(),
        ];

        return view('admin.ecoles.show', compact('ecole', 'stats'));
    }

    /**
     * Show the form for editing the specified school
     */
    public function edit(Ecole $ecole): View
    {
        return view('admin.ecoles.edit', compact('ecole'));
    }

    /**
     * Update the specified school
     */
    public function update(UpdateEcoleRequest $request, Ecole $ecole): RedirectResponse
    {
        try {
            // Standard: Validation déjà faite par FormRequest
            $validated = $request->validated();

            // Gestion du nouveau logo
            if ($request->hasFile('logo')) {
                if ($ecole->logo && \Storage::disk('public')->exists($ecole->logo)) {
                    \Storage::disk('public')->delete($ecole->logo);
                }
                
                $logoPath = $request->file('logo')->store('ecoles/logos', 'public');
                $validated['logo'] = $logoPath;
            }

            $ecole->update($validated);

            // Logging action sensible selon le standard
            Log::info('École mise à jour', [
                'user_id' => auth()->id(),
                'ecole_id' => $ecole->id,
                'nom_ecole' => $ecole->nom
            ]);

            return $this->redirectWithSuccess('admin.ecoles.index', 
                'École "' . $ecole->nom . '" mise à jour avec succès');

        } catch (\Exception $e) {
            Log::error('Erreur mise à jour école', [
                'user_id' => auth()->id(),
                'ecole_id' => $ecole->id,
                'error' => $e->getMessage()
            ]);

            return $this->redirectWithError('admin.ecoles.edit', 'Erreur lors de la mise à jour');
        }
    }

    /**
     * Remove the specified school
     */
    public function destroy(Ecole $ecole): RedirectResponse
    {
        try {
            // Vérifications métier (pas d'autorisation, c'est fait par Policy)
            if ($ecole->users()->count() > 0) {
                return $this->redirectWithError('admin.ecoles.index', 
                    'Impossible de supprimer une école qui a des membres');
            }

            if ($ecole->cours()->count() > 0) {
                return $this->redirectWithError('admin.ecoles.index', 
                    'Impossible de supprimer une école qui a des cours');
            }

            // Supprimer le logo si présent
            if ($ecole->logo && \Storage::disk('public')->exists($ecole->logo)) {
                \Storage::disk('public')->delete($ecole->logo);
            }

            $nomEcole = $ecole->nom;

            // Logging action sensible AVANT suppression
            Log::warning('École supprimée', [
                'user_id' => auth()->id(),
                'ecole_id' => $ecole->id,
                'nom_ecole' => $nomEcole
            ]);

            $ecole->delete();

            return $this->redirectWithSuccess('admin.ecoles.index', 
                "École \"{$nomEcole}\" supprimée avec succès");

        } catch (\Exception $e) {
            Log::error('Erreur suppression école', [
                'user_id' => auth()->id(),
                'ecole_id' => $ecole->id,
                'error' => $e->getMessage()
            ]);

            return $this->redirectWithError('admin.ecoles.index', 'Erreur lors de la suppression');
        }
    }
}
