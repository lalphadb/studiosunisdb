<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seminaire;
use App\Models\Ecole;
use Illuminate\Http\Request;

class SeminaireController extends Controller
{
    public function index(Request $request)
    {
        $query = Seminaire::with(['inscriptions']);
        
        // Filtres adaptés à la vraie structure
        if ($request->filled('type')) {
            $query->where('type_seminaire', $request->type);
        }
        
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        
        $seminaires = $query->orderBy('date_debut', 'desc')->paginate(15);
        
        // Statistiques
        $totalParticipants = $seminaires->sum(function($seminaire) {
            return $seminaire->inscriptions->count();
        });
        
        $seminairesMois = Seminaire::whereYear('date_debut', now()->year)
                                 ->whereMonth('date_debut', now()->month)
                                 ->count();
        
        $ecoles = Ecole::all();
        
        return view('admin.seminaires.index', compact(
            'seminaires', 
            'ecoles', 
            'totalParticipants', 
            'seminairesMois'
        ));
    }

    public function create()
    {
        $ecoles = Ecole::all();
        return view('admin.seminaires.create', compact('ecoles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',                    // ✅ Champ réel
            'type_seminaire' => 'required|string',                 // ✅ Champ réel
            'date_debut' => 'required|date|after:now',
            'date_fin' => 'required|date|after:date_debut',
            'lieu' => 'nullable|string|max:255',
            'intervenant' => 'nullable|string|max:255',            // ✅ Champ réel
            'capacite_max' => 'nullable|integer|min:1',            // ✅ Champ réel
            'prix' => 'nullable|numeric|min:0',                    // ✅ Champ réel
            'description' => 'nullable|string',
            'statut' => 'required|in:actif,complet,annule',        // ✅ Valeurs réelles
            'ouvert_toutes_ecoles' => 'required|boolean'           // ✅ Champ réel
        ]);

        // Mapper les champs du formulaire vers la DB
        $data = [
            'nom' => $request->titre ?? $request->nom,
            'intervenant' => $request->instructeur ?? $request->intervenant,
            'type_seminaire' => $request->type ?? $request->type_seminaire,
            'prix' => $request->cout ?? $request->prix,
            'capacite_max' => $request->max_participants ?? $request->capacite_max,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'lieu' => $request->lieu,
            'description' => $request->description,
            'statut' => $request->statut,
            'ouvert_toutes_ecoles' => true, // Par défaut ouvert à tous
        ];

        Seminaire::create($data);

        return redirect()->route('admin.seminaires.index')
                        ->with('success', 'Séminaire créé avec succès');
    }

    public function show(Seminaire $seminaire)
    {
        $seminaire->load(['inscriptions.membre.ecole']);
        return view('admin.seminaires.show', compact('seminaire'));
    }

    public function edit(Seminaire $seminaire)
    {
        $ecoles = Ecole::all();
        return view('admin.seminaires.edit', compact('seminaire', 'ecoles'));
    }

    public function update(Request $request, Seminaire $seminaire)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'type_seminaire' => 'required|string',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'lieu' => 'nullable|string|max:255',
            'intervenant' => 'nullable|string|max:255',
            'capacite_max' => 'nullable|integer|min:1',
            'prix' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'statut' => 'required|in:actif,complet,annule'
        ]);

        // Mapper les champs
        $data = [
            'nom' => $request->titre ?? $request->nom,
            'intervenant' => $request->instructeur ?? $request->intervenant,
            'type_seminaire' => $request->type ?? $request->type_seminaire,
            'prix' => $request->cout ?? $request->prix,
            'capacite_max' => $request->max_participants ?? $request->capacite_max,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'lieu' => $request->lieu,
            'description' => $request->description,
            'statut' => $request->statut,
        ];

        $seminaire->update($data);

        return redirect()->route('admin.seminaires.show', $seminaire)
                        ->with('success', 'Séminaire mis à jour avec succès');
    }

    public function destroy(Seminaire $seminaire)
    {
        if ($seminaire->inscriptions()->count() > 0) {
            return redirect()->back()
                           ->with('error', 'Impossible de supprimer un séminaire avec des participants inscrits');
        }
        
        $seminaire->delete();
        
        return redirect()->route('admin.seminaires.index')
                        ->with('success', 'Séminaire supprimé avec succès');
    }
}
