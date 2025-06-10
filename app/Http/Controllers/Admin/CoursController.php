<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cours;
use App\Models\Ecole;
use App\Models\User;
use App\Models\Membre;
use Illuminate\Http\Request;

class CoursController extends Controller
{
    public function index(Request $request)
    {
        $coursQuery = Cours::with(['ecole', 'instructeur']);
        
        if (auth()->user()->hasRole('admin')) {
            $coursQuery->where('ecole_id', auth()->user()->ecole_id);
        }
        
        if ($request->type_cours) {
            $coursQuery->where('type_cours', $request->type_cours);
        }
        
        if ($request->status) {
            $coursQuery->where('status', $request->status);
        }
        
        if ($request->search) {
            $coursQuery->where(function ($q) use ($request) {
                $q->where('nom', 'like', "%{$request->search}%");
            });
        }
        
        $cours = $coursQuery->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        $statsQuery = Cours::query();
        if (auth()->user()->hasRole('admin')) {
            $statsQuery->where('ecole_id', auth()->user()->ecole_id);
        }
        
        $stats = [
            'total_cours' => $statsQuery->count(),
            'cours_actifs' => (clone $statsQuery)->where('status', 'actif')->count(),
            'cours_complets' => (clone $statsQuery)->where('status', 'complet')->count(),
        ];

        return view('admin.cours.index', compact('cours', 'stats'));
    }

    public function create()
    {
        $ecoles = auth()->user()->hasRole('superadmin') 
            ? Ecole::all() 
            : Ecole::where('id', auth()->user()->ecole_id)->get();

        $instructeurs = User::role(['admin', 'instructeur'])
            ->when(auth()->user()->hasRole('admin'), function ($query) {
                return $query->where('ecole_id', auth()->user()->ecole_id);
            })
            ->get();

        return view('admin.cours.create', compact('ecoles', 'instructeurs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'ecole_id' => 'required|exists:ecoles,id',
            'instructeur_id' => 'nullable|exists:users,id',
            'type_cours' => 'required|string',
            'prix_mensuel' => 'required|numeric|min:0',
            'capacite_max' => 'required|integer|min:1',
        ]);

        if (auth()->user()->hasRole('admin') && $validated['ecole_id'] != auth()->user()->ecole_id) {
            abort(403);
        }

        $validated['prix'] = $validated['prix_mensuel'];
        $validated['status'] = 'actif';

        $cours = Cours::create($validated);

        return redirect()->route('admin.cours.index')->with('success', 'Cours créé avec succès.');
    }

    public function show(Cours $cours)
    {
        if (auth()->user()->hasRole('admin') && $cours->ecole_id !== auth()->user()->ecole_id) {
            abort(403);
        }

        $cours->load(['ecole', 'instructeur']);

        $stats = [
            'nombre_inscrits' => 0,
            'places_disponibles' => $cours->capacite_max,
            'taux_occupation' => 0,
        ];

        return view('admin.cours.show', compact('cours', 'stats'));
    }

    public function edit(Cours $cours)
    {
        if (auth()->user()->hasRole('admin') && $cours->ecole_id !== auth()->user()->ecole_id) {
            abort(403);
        }

        $ecoles = auth()->user()->hasRole('superadmin') 
            ? Ecole::all() 
            : Ecole::where('id', auth()->user()->ecole_id)->get();

        $instructeurs = User::role(['admin', 'instructeur'])
            ->when(auth()->user()->hasRole('admin'), function ($query) {
                return $query->where('ecole_id', auth()->user()->ecole_id);
            })
            ->get();

        return view('admin.cours.edit', compact('cours', 'ecoles', 'instructeurs'));
    }

    public function update(Request $request, Cours $cours)
    {
        if (auth()->user()->hasRole('admin') && $cours->ecole_id !== auth()->user()->ecole_id) {
            abort(403);
        }

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'ecole_id' => 'required|exists:ecoles,id',
            'type_cours' => 'required|string',
            'prix_mensuel' => 'required|numeric|min:0',
            'capacite_max' => 'required|integer|min:1',
            'status' => 'required|in:actif,inactif,complet',
        ]);

        $validated['prix'] = $validated['prix_mensuel'];
        $cours->update($validated);

        return redirect()->route('admin.cours.index')->with('success', 'Cours mis à jour.');
    }

    public function destroy(Cours $cours)
    {
        if (auth()->user()->hasRole('admin') && $cours->ecole_id !== auth()->user()->ecole_id) {
            abort(403);
        }

        $cours->delete();
        return redirect()->route('admin.cours.index')->with('success', 'Cours supprimé.');
    }
}
