<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ecole;
use Illuminate\Http\Request;

class EcoleController extends Controller
{
    public function index()
    {
        $ecoles = Ecole::paginate(20);
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
            'adresse' => 'nullable|string',
            'ville' => 'nullable|string|max:100',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
        ]);

        Ecole::create($validated);
        return redirect()->route('admin.ecoles.index')->with('success', 'École créée avec succès.');
    }

    public function show(Ecole $ecole)
    {
        $ecole->load(['membres', 'cours']);
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
            'adresse' => 'nullable|string',
            'ville' => 'nullable|string|max:100',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
        ]);

        $ecole->update($validated);
        return redirect()->route('admin.ecoles.show', $ecole)->with('success', 'École mise à jour.');
    }

    public function destroy(Ecole $ecole)
    {
        $ecole->delete();
        return redirect()->route('admin.ecoles.index')->with('success', 'École supprimée.');
    }
}
