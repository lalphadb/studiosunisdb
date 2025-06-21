<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cours;
use App\Models\Ecole;
use App\Models\User as Utilisateur;
use Illuminate\Http\Request;

class CoursController extends Controller
{
    public function index()
    {
        $cours = Cours::with(['ecole'])->paginate(15);
        
        return view('admin.cours.index', compact('cours'));
    }

    public function create()
    {
        $ecoles = Ecole::all();
        return view('admin.cours.create', compact('ecoles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'niveau' => 'required|in:debutant,intermediaire,avance,tous_niveaux',
            'ecole_id' => 'required|exists:ecoles,id',
            'capacite_max' => 'required|integer|min:1',
            'prix' => 'nullable|numeric|min:0',
            'duree_minutes' => 'required|integer|min:30',
        ]);

        Cours::create($validated);

        return redirect()->route('admin.cours.index')
                        ->with('success', 'Cours créé avec succès.');
    }

    public function show(Cours $cours)
    {
        return view('admin.cours.show', compact('cours'));
    }

    public function edit(Cours $cours)
    {
        $ecoles = Ecole::all();
        return view('admin.cours.edit', compact('cours', 'ecoles'));
    }

    public function update(Request $request, Cours $cours)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'niveau' => 'required|in:debutant,intermediaire,avance,tous_niveaux',
            'ecole_id' => 'required|exists:ecoles,id',
            'capacite_max' => 'required|integer|min:1',
            'prix' => 'nullable|numeric|min:0',
            'duree_minutes' => 'required|integer|min:30',
        ]);

        $cours->update($validated);

        return redirect()->route('admin.cours.index')
                        ->with('success', 'Cours modifié avec succès.');
    }

    public function destroy(Cours $cours)
    {
        $cours->delete();

        return redirect()->route('admin.cours.index')
                        ->with('success', 'Cours supprimé avec succès.');
    }
}
