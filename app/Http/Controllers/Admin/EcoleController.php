<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ecole;
use Illuminate\Http\Request;

class EcoleController extends BaseAdminController
{
    public function index()
    {
        $ecoles = Ecole::paginate(15);
        return view('admin.ecoles.index', compact('ecoles'));
    }

    public function create()
    {
        return view('admin.ecoles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'adresse' => 'required|string',
            'ville' => 'required|string',
            'telephone' => 'required|string',
            'email' => 'required|email'
        ]);

        Ecole::create($request->all());

        return redirect()->route('admin.ecoles.index')
            ->with('success', 'École créée avec succès !');
    }

    public function show(Ecole $ecole)
    {
        return view('admin.ecoles.show', compact('ecole'));
    }

    public function edit(Ecole $ecole)
    {
        return view('admin.ecoles.edit', compact('ecole'));
    }

    public function update(Request $request, Ecole $ecole)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'adresse' => 'required|string',
            'ville' => 'required|string',
            'telephone' => 'required|string',
            'email' => 'required|email'
        ]);

        $ecole->update($request->all());

        return redirect()->route('admin.ecoles.index')
            ->with('success', 'École mise à jour !');
    }

    public function destroy(Ecole $ecole)
    {
        $ecole->delete();
        return redirect()->route('admin.ecoles.index')
            ->with('success', 'École supprimée !');
    }
}
