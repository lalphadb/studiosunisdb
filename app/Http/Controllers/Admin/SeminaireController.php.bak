<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\StoreSeminaireRequest;
use App\Http\Requests\Admin\UpdateSeminaireRequest;
use App\Models\Seminaire;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Contrôleur de gestion des séminaires
 * 
 * Migré vers BaseAdminController avec standards Laravel 12
 */
class SeminaireController extends BaseAdminController
{
    /**
     * Initialise le contrôleur avec permissions
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->middleware('can:viewAny,App\Models\Seminaire')->only(['index']);
        $this->middleware('can:view,seminaire')->only(['show']);
        $this->middleware('can:create,App\Models\Seminaire')->only(['create', 'store']);
        $this->middleware('can:update,seminaire')->only(['edit', 'update']);
        $this->middleware('can:delete,seminaire')->only(['destroy']);
    }

    /**
     * Display a listing of seminaires
     */
    public function index(Request $request): View
    {
        return $this->executeWithExceptionHandling(function() use ($request) {
            $query = Seminaire::with('ecole');

            // Multi-tenant filter
            if (!auth()->user()->hasRole('superadmin')) {
                $query->where('ecole_id', auth()->user()->ecole_id);
            }

            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where('nom', 'like', "%{$search}%");
            }

            $seminaires = $this->paginateWithParams($query->orderBy('date_debut', 'desc'), $request);

            $this->logBusinessAction('Consultation index séminaires', 'info', [
                'total_seminaires' => $seminaires->total(),
                'filters' => $request->only(['search'])
            ]);

            return view('admin.seminaires.index', compact('seminaires'));
            
        }, 'consultation séminaires');
    }

    /**
     * Show the form for creating a new seminaire
     */
    public function create(): View
    {
        return view('admin.seminaires.create');
    }

    /**
     * Store a newly created seminaire
     */
    public function store(StoreSeminaireRequest $request): RedirectResponse
    {
        return $this->executeWithExceptionHandling(function() use ($request) {
            $validated = $request->validated();
            $validated['ecole_id'] = auth()->user()->ecole_id;

            $seminaire = Seminaire::create($validated);

            $this->logCreate('Séminaire', $seminaire->id, $validated);

            return $this->redirectWithSuccess(
                'admin.seminaires.index',
                $this->trans('admin.success.created', ['item' => "Séminaire \"{$seminaire->nom}\""])
            );
            
        }, 'création séminaire', ['form_data' => $request->validated()]);
    }

    /**
     * Display the specified seminaire
     */
    public function show(Seminaire $seminaire): View
    {
        return view('admin.seminaires.show', compact('seminaire'));
    }

    /**
     * Show the form for editing the specified seminaire
     */
    public function edit(Seminaire $seminaire): View
    {
        return view('admin.seminaires.edit', compact('seminaire'));
    }

    /**
     * Update the specified seminaire
     */
    public function update(UpdateSeminaireRequest $request, Seminaire $seminaire): RedirectResponse
    {
        return $this->executeWithExceptionHandling(function() use ($request, $seminaire) {
            $validated = $request->validated();
            $oldData = $seminaire->toArray();

            $seminaire->update($validated);

            $this->logUpdate('Séminaire', $seminaire->id, $oldData, $validated);

            return $this->redirectWithSuccess(
                'admin.seminaires.index',
                $this->trans('admin.success.updated', ['item' => "Séminaire \"{$seminaire->nom}\""])
            );
            
        }, 'modification séminaire', ['seminaire_id' => $seminaire->id]);
    }

    /**
     * Remove the specified seminaire
     */
    public function destroy(Seminaire $seminaire): RedirectResponse
    {
        return $this->executeWithExceptionHandling(function() use ($seminaire) {
            $seminaireData = [
                'nom' => $seminaire->nom,
                'ecole_id' => $seminaire->ecole_id
            ];

            $this->logDelete('Séminaire', $seminaire->id, $seminaireData);

            $seminaire->delete();

            return $this->redirectWithSuccess(
                'admin.seminaires.index',
                $this->trans('admin.success.deleted', ['item' => "Séminaire \"{$seminaireData['nom']}\""])
            );
            
        }, 'suppression séminaire', ['seminaire_id' => $seminaire->id]);
    }
}
