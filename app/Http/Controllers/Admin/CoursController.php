<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cours;
use App\Models\Ecole;
use App\Models\User;
use Illuminate\Http\Request;

class CoursController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Admin école : voir seulement ses cours
        if ($user->hasRole('admin') && $user->ecole_id) {
            $cours = Cours::where('ecole_id', $user->ecole_id)
                ->with(['ecole', 'instructeur'])
                ->latest()
                ->paginate(15);
        } else {
            // SuperAdmin : voir tous les cours
            $cours = Cours::with(['ecole', 'instructeur'])
                ->latest()
                ->paginate(15);
        }

        return view('admin.cours.index', compact('cours'));
    }

    public function create()
    {
        $user = auth()->user();
        
        if ($user->hasRole('admin') && $user->ecole_id) {
            $ecoles = Ecole::where('id', $user->ecole_id)->get();
            $instructeurs = User::where('ecole_id', $user->ecole_id)
                ->whereHas('roles', function($q) {
                    $q->whereIn('name', ['admin', 'instructeur']);
                })->get();
        } else {
            $ecoles = Ecole::where('statut', 'actif')->get();
            $instructeurs = User::whereHas('roles', function($q) {
                $q->whereIn('name', ['admin', 'instructeur']);
            })->get();
        }

        return view('admin.cours.create', compact('ecoles', 'instructeurs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'ecole_id' => 'required|exists:ecoles,id',
            'instructeur_id' => 'required|exists:users,id',
            'capacite_max' => 'required|integer|min:1|max:100',
            'prix' => 'required|numeric|min:0',
            'statut' => 'required|in:actif,inactif,complet',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after:date_debut'
        ]);

        Cours::create($validated);

        return redirect()->route('admin.cours.index')
                        ->with('success', 'Cours créé avec succès !');
    }

    public function show(Cours $cours)
    {
        $cours->load(['ecole', 'instructeur']);
        return view('admin.cours.show', compact('cours'));
    }

    public function edit(Cours $cours)
    {
        $user = auth()->user();
        
        if ($user->hasRole('admin') && $user->ecole_id) {
            $ecoles = Ecole::where('id', $user->ecole_id)->get();
            $instructeurs = User::where('ecole_id', $user->ecole_id)
                ->whereHas('roles', function($q) {
                    $q->whereIn('name', ['admin', 'instructeur']);
                })->get();
        } else {
            $ecoles = Ecole::where('statut', 'actif')->get();
            $instructeurs = User::whereHas('roles', function($q) {
                $q->whereIn('name', ['admin', 'instructeur']);
            })->get();
        }

        return view('admin.cours.edit', compact('cours', 'ecoles', 'instructeurs'));
    }

    public function update(Request $request, Cours $cours)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'ecole_id' => 'required|exists:ecoles,id',
            'instructeur_id' => 'required|exists:users,id',
            'capacite_max' => 'required|integer|min:1|max:100',
            'prix' => 'required|numeric|min:0',
            'statut' => 'required|in:actif,inactif,complet',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after:date_debut'
        ]);

        $cours->update($validated);

        return redirect()->route('admin.cours.show', $cours)
                        ->with('success', 'Cours mis à jour avec succès !');
    }

    public function destroy(Cours $cours)
    {
        $cours->delete();
        
        return redirect()->route('admin.cours.index')
                        ->with('success', 'Cours supprimé avec succès !');
    }
}
