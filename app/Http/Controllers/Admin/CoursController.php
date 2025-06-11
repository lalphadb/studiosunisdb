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
        
        // Filtrer par école pour les admins
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
        // Récupérer les écoles selon le rôle
        if (auth()->user()->hasRole('superadmin')) {
            $ecoles = Ecole::orderBy('nom')->get();
        } else {
            // Pour admin d'école, seulement son école
            $ecoles = Ecole::where('id', auth()->user()->ecole_id)->get();
        }

        // Récupérer les instructeurs
        $instructeursQuery = User::role(['admin', 'instructeur']);
        
        if (auth()->user()->hasRole('admin')) {
            $instructeursQuery->where('ecole_id', auth()->user()->ecole_id);
        }
        
        $instructeurs = $instructeursQuery->orderBy('name')->get();

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
            'jour_semaine' => 'nullable|string|in:lundi,mardi,mercredi,jeudi,vendredi,samedi,dimanche',
            'heure_debut' => 'nullable|date_format:H:i',
            'heure_fin' => 'nullable|date_format:H:i|after:heure_debut',
            'prix_mensuel' => 'nullable|numeric|min:0',
            'prix_session' => 'nullable|numeric|min:0',
            'capacite_max' => 'required|integer|min:1',
            'duree_minutes' => 'nullable|integer|min:30|max:180',
            'age_min' => 'nullable|integer|min:3|max:100',
            'age_max' => 'nullable|integer|min:3|max:100|gte:age_min',
            'niveau_requis' => 'nullable|string|max:100',
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
        ]);

        // Vérifier que l'admin ne peut créer que pour son école
        if (auth()->user()->hasRole('admin') && $validated['ecole_id'] != auth()->user()->ecole_id) {
            abort(403, 'Vous ne pouvez créer des cours que pour votre école.');
        }

        // Définir le prix principal
        $validated['prix'] = $validated['prix_mensuel'] ?? 0;
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

        if (auth()->user()->hasRole('superadmin')) {
            $ecoles = Ecole::orderBy('nom')->get();
        } else {
            $ecoles = Ecole::where('id', auth()->user()->ecole_id)->get();
        }

        $instructeursQuery = User::role(['admin', 'instructeur']);
        
        if (auth()->user()->hasRole('admin')) {
            $instructeursQuery->where('ecole_id', auth()->user()->ecole_id);
        }
        
        $instructeurs = $instructeursQuery->orderBy('name')->get();

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
            'instructeur_id' => 'nullable|exists:users,id',
            'type_cours' => 'required|string',
            'jour_semaine' => 'nullable|string|in:lundi,mardi,mercredi,jeudi,vendredi,samedi,dimanche',
            'heure_debut' => 'nullable|date_format:H:i',
            'heure_fin' => 'nullable|date_format:H:i|after:heure_debut',
            'prix_mensuel' => 'nullable|numeric|min:0',
            'prix_session' => 'nullable|numeric|min:0',
            'capacite_max' => 'required|integer|min:1',
            'duree_minutes' => 'nullable|integer|min:30|max:180',
            'age_min' => 'nullable|integer|min:3|max:100',
            'age_max' => 'nullable|integer|min:3|max:100|gte:age_min',
            'niveau_requis' => 'nullable|string|max:100',
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'status' => 'required|in:actif,inactif,complet',
        ]);

        $validated['prix'] = $validated['prix_mensuel'] ?? 0;
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
