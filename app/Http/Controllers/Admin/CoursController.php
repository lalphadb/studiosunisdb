<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cours;
use App\Models\Ecole;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CoursController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return ['auth', 'verified'];
    }

    public function index()
    {
        $cours = Cours::with(['ecole', 'inscriptions'])
            ->when(auth()->user()->ecole_id, fn($q, $ecole_id) => $q->where('ecole_id', $ecole_id))
            ->paginate(15);
            
        return view('admin.cours.index', compact('cours'));
    }

    public function show(Cours $cours)
    {
        $cours->load(['ecole', 'horaires', 'inscriptions']);
        
        $stats = [
            'total_inscriptions' => $cours->inscriptions()->count(),
            'places_disponibles' => $cours->places_disponibles,
            'revenus_session' => $cours->inscriptions()->count() * ($cours->prix ?? 0),
        ];
        
        return view('admin.cours.show', compact('cours', 'stats'));
    }

    public function create()
    {
        $ecoles = auth()->user()->hasRole('superadmin') 
            ? Ecole::all() 
            : collect([auth()->user()->ecole]);
            
        return view('admin.cours.create', compact('ecoles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ecole_id' => 'required|exists:ecoles,id',
            'nom' => 'required|string|max:191',
            'description' => 'nullable|string',
            'niveau' => 'required|in:debutant,intermediaire,avance,tous_niveaux',
            'capacite_max' => 'required|integer|min:1|max:100',
            'prix' => 'nullable|numeric|min:0',
            'duree_minutes' => 'required|integer|min:30|max:180',
            'instructeur' => 'nullable|string|max:191',
            'active' => 'boolean',
        ]);

        if (!auth()->user()->hasRole('superadmin')) {
            $validated['ecole_id'] = auth()->user()->ecole_id;
        }

        Cours::create($validated);

        return redirect()->route('admin.cours.index')
            ->with('success', 'Cours créé avec succès !');
    }

    public function edit(Cours $cours)
    {
        $ecoles = auth()->user()->hasRole('superadmin') 
            ? Ecole::all() 
            : collect([auth()->user()->ecole]);
            
        return view('admin.cours.edit', compact('cours', 'ecoles'));
    }

    public function update(Request $request, Cours $cours)
    {
        $validated = $request->validate([
            'ecole_id' => 'required|exists:ecoles,id',
            'nom' => 'required|string|max:191',
            'description' => 'nullable|string',
            'niveau' => 'required|in:debutant,intermediaire,avance,tous_niveaux',
            'capacite_max' => 'required|integer|min:1|max:100',
            'prix' => 'nullable|numeric|min:0',
            'duree_minutes' => 'required|integer|min:30|max:180',
            'instructeur' => 'nullable|string|max:191',
            'active' => 'boolean',
        ]);

        if (!auth()->user()->hasRole('superadmin')) {
            $validated['ecole_id'] = auth()->user()->ecole_id;
        }

        $cours->update($validated);

        return redirect()->route('admin.cours.show', $cours)
            ->with('success', 'Cours modifié avec succès !');
    }

    public function destroy(Cours $cours)
    {
        if ($cours->inscriptions()->count() > 0) {
            return redirect()->route('admin.cours.index')
                ->with('error', 'Impossible de supprimer un cours avec des inscriptions actives.');
        }

        $cours->delete();

        return redirect()->route('admin.cours.index')
            ->with('success', 'Cours supprimé avec succès !');
    }
}
