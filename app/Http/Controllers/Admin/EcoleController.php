<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ecole;
use Illuminate\Http\Request;

class EcoleController extends Controller
{
    public function index()
    {
        $ecoles = Ecole::withCount('membres')
            ->orderBy('nom')
            ->paginate(10);

        return view('admin.ecoles.index', compact('ecoles'));
    }

    public function show(Ecole $ecole)
    {
        $ecole->load('membres');
        
        $stats = [
            'membres_actifs' => $ecole->membres()->where('statut', 'actif')->count(),
            'cours_actifs' => 0,
            'revenus_mois' => 0,
            'taux_presence' => 85
        ];

        return view('admin.ecoles.show', compact('ecole', 'stats'));
    }

    public function edit(Ecole $ecole)
    {
        return view('admin.ecoles.edit', compact('ecole'));
    }

    public function update(Request $request, Ecole $ecole)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'adresse' => 'required|string|max:500',
            'ville' => 'required|string|max:100',
            'province' => 'required|string|max:50',
            'code_postal' => 'required|string|max:10',
            'telephone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'directeur' => 'required|string|max:255',
            'capacite_max' => 'required|integer|min:10|max:500',
            'statut' => 'required|in:actif,inactif',
        ]);

        $ecole->update($validated);

        return redirect()->route('admin.ecoles.show', $ecole)
                        ->with('success', 'École mise à jour avec succès !');
    }
}
