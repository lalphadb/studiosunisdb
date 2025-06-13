<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Membre;
use App\Models\Ecole;
use Illuminate\Http\Request;

class MembreController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Admin école : voir seulement ses membres
        if ($user->hasRole('admin') && $user->ecole_id) {
            $membres = Membre::where('ecole_id', $user->ecole_id)
                ->with('ecole')
                ->latest()
                ->paginate(15);
        } else {
            // SuperAdmin : voir tous les membres
            $membres = Membre::with('ecole')
                ->latest()
                ->paginate(15);
        }

        return view('admin.membres.index', compact('membres'));
    }

    public function create()
    {
        $user = auth()->user();
        
        if ($user->hasRole('admin') && $user->ecole_id) {
            $ecoles = Ecole::where('id', $user->ecole_id)->get();
        } else {
            $ecoles = Ecole::where('statut', 'actif')->get();
        }

        return view('admin.membres.create', compact('ecoles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'prenom' => 'required|string|max:100',
            'nom' => 'required|string|max:100',
            'email' => 'nullable|email|unique:membres,email',
            'telephone' => 'nullable|string|max:20',
            'date_naissance' => 'nullable|date',
            'ecole_id' => 'required|exists:ecoles,id',
            'date_inscription' => 'required|date',
            'statut' => 'required|in:actif,inactif,suspendu'
        ]);

        Membre::create($validated);

        return redirect()->route('admin.membres.index')
                        ->with('success', 'Membre créé avec succès !');
    }

    public function show(Membre $membre)
    {
        $membre->load('ecole');
        return view('admin.membres.show', compact('membre'));
    }

    public function edit(Membre $membre)
    {
        $user = auth()->user();
        
        if ($user->hasRole('admin') && $user->ecole_id) {
            $ecoles = Ecole::where('id', $user->ecole_id)->get();
        } else {
            $ecoles = Ecole::where('statut', 'actif')->get();
        }

        return view('admin.membres.edit', compact('membre', 'ecoles'));
    }

    public function update(Request $request, Membre $membre)
    {
        $validated = $request->validate([
            'prenom' => 'required|string|max:100',
            'nom' => 'required|string|max:100',
            'email' => 'nullable|email|unique:membres,email,' . $membre->id,
            'telephone' => 'nullable|string|max:20',
            'date_naissance' => 'nullable|date',
            'ecole_id' => 'required|exists:ecoles,id',
            'date_inscription' => 'required|date',
            'statut' => 'required|in:actif,inactif,suspendu'
        ]);

        $membre->update($validated);

        return redirect()->route('admin.membres.show', $membre)
                        ->with('success', 'Membre mis à jour avec succès !');
    }

    public function destroy(Membre $membre)
    {
        $membre->delete();
        
        return redirect()->route('admin.membres.index')
                        ->with('success', 'Membre supprimé avec succès !');
    }
}
