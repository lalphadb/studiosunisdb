<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ecole;
use Illuminate\Http\Request;

class EcoleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
        $this->middleware('role:super-admin');
    }

    public function index()
    {
        $ecoles = Ecole::withCount(['users', 'cours'])
            ->orderBy('nom')
            ->paginate(20);

        return view('admin.ecoles.index', compact('ecoles'));
    }

    public function create()
    {
        return view('admin.ecoles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:ecoles',
            'adresse' => 'nullable|string|max:255',
            'ville' => 'nullable|string|max:100',
            'code_postal' => 'nullable|string|max:10',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'actif' => 'boolean',
        ]);

        $ecole = Ecole::create($validated);
        
        return redirect()->route('admin.ecoles.index')
            ->with('success', 'École créée avec succès.');
    }

    public function show(Ecole $ecole)
    {
        $ecole->load(['users' => function ($query) {
            $query->with('roles')->orderBy('nom');
        }, 'cours']);
        
        return view('admin.ecoles.show', compact('ecole'));
    }

    public function edit(Ecole $ecole)
    {
        return view('admin.ecoles.edit', compact('ecole'));
    }

    public function update(Request $request, Ecole $ecole)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:ecoles,code,' . $ecole->id,
            'adresse' => 'nullable|string|max:255',
            'ville' => 'nullable|string|max:100',
            'code_postal' => 'nullable|string|max:10',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'actif' => 'boolean',
        ]);

        $ecole->update($validated);
        
        return redirect()->route('admin.ecoles.index')
            ->with('success', 'École mise à jour avec succès.');
    }

    public function destroy(Ecole $ecole)
    {
        if ($ecole->users()->exists() || $ecole->cours()->exists()) {
            return back()->with('error', 'Impossible de supprimer une école avec des données associées.');
        }

        $ecole->delete();
        
        return redirect()->route('admin.ecoles.index')
            ->with('success', 'École supprimée avec succès.');
    }
}
