<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ecole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class EcoleController extends Controller
{
    public function index(Request $request)
    {
        if (!Gate::allows('viewAny', Ecole::class)) {
            abort(403, 'Accès non autorisé aux écoles');
        }

        $query = Ecole::query();

        // IMPORTANT: Admin école ne voit QUE son école
        if (auth()->user()->hasRole('admin')) {
            $query->where('id', auth()->user()->ecole_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nom', 'like', "%{$search}%")
                  ->orWhere('ville', 'like', "%{$search}%");
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $ecoles = $query->orderBy('nom')->paginate(15)->withQueryString();
        return view('admin.ecoles.index', compact('ecoles'));
    }

    public function create()
    {
        if (!Gate::allows('create', Ecole::class)) {
            abort(403, 'Seuls les SuperAdmin peuvent créer des écoles');
        }
        
        return view('admin.ecoles.create');
    }

    public function store(Request $request)
    {
        if (!Gate::allows('create', Ecole::class)) {
            abort(403, 'Seuls les SuperAdmin peuvent créer des écoles');
        }

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'adresse' => 'nullable|string',
            'ville' => 'required|string|max:100',
            'province' => 'nullable|string|max:50',
            'code_postal' => 'nullable|string|max:10',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'site_web' => 'nullable|url|max:255',
            'directeur' => 'nullable|string|max:255',
            'capacite_max' => 'nullable|integer|min:10|max:500',
            'statut' => 'required|in:actif,inactif',
            'description' => 'nullable|string',
        ]);

        $ecole = Ecole::create($validated);

        return redirect()->route('admin.ecoles.show', $ecole)
                        ->with('success', 'École créée avec succès !');
    }

    public function show(Ecole $ecole)
    {
        if (!Gate::allows('view', $ecole)) {
            abort(403, 'Vous ne pouvez voir que votre école');
        }
        
        $ecole->load('membres');
        return view('admin.ecoles.show', compact('ecole'));
    }

    public function edit(Ecole $ecole)
    {
        if (!Gate::allows('update', $ecole)) {
            abort(403, 'Vous ne pouvez modifier que votre école');
        }
        
        return view('admin.ecoles.edit', compact('ecole'));
    }

    public function update(Request $request, Ecole $ecole)
    {
        if (!Gate::allows('update', $ecole)) {
            abort(403, 'Vous ne pouvez modifier que votre école');
        }

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'adresse' => 'nullable|string',
            'ville' => 'required|string|max:100',
            'province' => 'nullable|string|max:50',
            'code_postal' => 'nullable|string|max:10',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'site_web' => 'nullable|url|max:255',
            'directeur' => 'nullable|string|max:255',
            'capacite_max' => 'nullable|integer|min:10|max:500',
            'statut' => 'required|in:actif,inactif',
            'description' => 'nullable|string',
        ]);

        $ecole->update($validated);

        return redirect()->route('admin.ecoles.show', $ecole)
                        ->with('success', 'École mise à jour avec succès !');
    }

    public function destroy(Ecole $ecole)
    {
        if (!Gate::allows('delete', $ecole)) {
            abort(403, 'Seuls les SuperAdmin peuvent supprimer des écoles');
        }

        if ($ecole->membres()->count() > 0) {
            return redirect()->route('admin.ecoles.index')
                           ->with('error', 'Impossible de supprimer une école qui a des membres.');
        }

        $ecole->delete();

        return redirect()->route('admin.ecoles.index')
                        ->with('success', 'École supprimée avec succès !');
    }
}
