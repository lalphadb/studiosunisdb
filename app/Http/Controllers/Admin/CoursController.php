<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cours;
use App\Models\Ecole;
use App\Models\User;
use App\Http\Requests\CoursRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CoursController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!Gate::allows('viewAny', Cours::class)) {
            abort(403, 'Accès non autorisé');
        }

        $query = Cours::with(['ecole', 'instructeur']);

        // Filtre automatique par école pour admin
        if (!Gate::allows('manage-system')) {
            $query->where('ecole_id', auth()->user()->ecole_id);
        }

        // Filtres de recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('niveau_requis', 'like', "%{$search}%");
            });
        }

        // Filtre par école (pour superadmin)
        if ($request->filled('ecole_id') && Gate::allows('manage-system')) {
            $query->where('ecole_id', $request->ecole_id);
        }

        // Filtre par statut
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtre par type
        if ($request->filled('type_cours')) {
            $query->where('type_cours', $request->type_cours);
        }

        // Filtre par jour
        if ($request->filled('jour_semaine')) {
            $query->where('jour_semaine', $request->jour_semaine);
        }

        $cours = $query->orderBy('jour_semaine')
                      ->orderBy('heure_debut')
                      ->paginate(20)
                      ->withQueryString();

        // Données pour les filtres
        $ecoles = $this->getEcolesForUser();
        
        return view('admin.cours.index', compact('cours', 'ecoles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Gate::allows('create', Cours::class)) {
            abort(403, 'Accès non autorisé');
        }

        $ecoles = $this->getEcolesForUser();
        $instructeurs = $this->getInstructeursForUser();

        return view('admin.cours.create', compact('ecoles', 'instructeurs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CoursRequest $request)
    {
        if (!Gate::allows('create', Cours::class)) {
            abort(403, 'Accès non autorisé');
        }

        $validated = $request->validated();

        // Forcer l'école pour les admins d'école
        if (!Gate::allows('manage-system')) {
            $validated['ecole_id'] = auth()->user()->ecole_id;
        }

        $cours = Cours::create($validated);

        return redirect()->route('admin.cours.show', $cours)
                        ->with('success', 'Cours créé avec succès !');
    }

    /**
     * Display the specified resource.
     */
    public function show(Cours $cours)
    {
        if (!Gate::allows('view', $cours)) {
            abort(403, 'Accès non autorisé');
        }

        $cours->load(['ecole', 'instructeur', 'inscriptions.membre']);

        return view('admin.cours.show', compact('cours'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cours $cours)
    {
        if (!Gate::allows('update', $cours)) {
            abort(403, 'Accès non autorisé');
        }

        $ecoles = $this->getEcolesForUser();
        $instructeurs = $this->getInstructeursForUser();

        return view('admin.cours.edit', compact('cours', 'ecoles', 'instructeurs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CoursRequest $request, Cours $cours)
    {
        if (!Gate::allows('update', $cours)) {
            abort(403, 'Accès non autorisé');
        }

        $validated = $request->validated();

        // Forcer l'école pour les admins d'école
        if (!Gate::allows('manage-system')) {
            $validated['ecole_id'] = auth()->user()->ecole_id;
        }

        $cours->update($validated);

        return redirect()->route('admin.cours.show', $cours)
                        ->with('success', 'Cours mis à jour avec succès !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cours $cours)
    {
        if (!Gate::allows('delete', $cours)) {
            abort(403, 'Accès non autorisé');
        }

        // Vérifier s'il y a des inscriptions actives
        if ($cours->inscriptions()->where('status', 'active')->count() > 0) {
            return redirect()->route('admin.cours.index')
                           ->with('error', 'Impossible de supprimer un cours avec des inscriptions actives.');
        }

        $cours->delete();

        return redirect()->route('admin.cours.index')
                        ->with('success', 'Cours supprimé avec succès !');
    }

    /**
     * Get écoles available for current user
     */
    private function getEcolesForUser()
    {
        if (Gate::allows('manage-system')) {
            return Ecole::where('statut', 'actif')->orderBy('nom')->get();
        } else {
            return Ecole::where('id', auth()->user()->ecole_id)
                        ->where('statut', 'actif')
                        ->get();
        }
    }

    /**
     * Get instructeurs available for current user
     */
    private function getInstructeursForUser()
    {
        $query = User::role(['instructeur', 'admin', 'superadmin']);

        if (!Gate::allows('manage-system')) {
            $query->where('ecole_id', auth()->user()->ecole_id);
        }

        return $query->orderBy('name')->get();
    }
}
