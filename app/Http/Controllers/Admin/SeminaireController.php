<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seminaire;
use Illuminate\Http\Request;

class SeminaireController extends Controller
{
    public function index()
    {
        $seminaires = Seminaire::with(['inscriptions'])
            ->when(request('type'), function($query, $type) {
                return $query->where('type_seminaire', $type);
            })
            ->when(request('statut'), function($query, $statut) {
                return $query->where('statut', $statut);
            })
            ->when(request('date_min'), function($query, $date) {
                return $query->where('date_debut', '>=', $date);
            })
            ->when(request('search'), function($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('nom', 'like', "%{$search}%")
                      ->orWhere('intervenant', 'like', "%{$search}%")
                      ->orWhere('lieu', 'like', "%{$search}%");
                });
            })
            ->orderBy('date_debut', 'desc')
            ->paginate(15);
            
        return view('admin.seminaires.index', compact('seminaires'));
    }

    public function create()
    {
        return view('admin.seminaires.create', [
            'typesSeninaire' => [
                'technique' => 'Technique',
                'kata' => 'Kata',
                'competition' => 'Compétition',
                'arbitrage' => 'Arbitrage',
                'self_defense' => 'Self-Défense',
                'armes' => 'Armes traditionnelles',
                'meditation' => 'Méditation',
                'histoire' => 'Histoire du karaté',
                'autre' => 'Autre'
            ]
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'intervenant' => 'required|string|max:255',
            'type_seminaire' => 'required|string',
            'description' => 'nullable|string',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date',
            'lieu' => 'required|string|max:255',
            'niveau_cible' => 'nullable|string|max:255',
            'pre_requis' => 'nullable|string',
            'materiel_requis' => 'nullable|string',
            'capacite_max' => 'required|integer|min:1',
            'prix' => 'required|numeric|min:0',
        ]);
        
        $validated['ouvert_toutes_ecoles'] = $request->has('ouvert_toutes_ecoles');
        $validated['statut'] = 'actif';
        
        Seminaire::create($validated);
        
        return redirect()->route('admin.seminaires.index')
            ->with('success', 'Séminaire créé avec succès.');
    }

    public function show(Seminaire $seminaire)
    {
        $seminaire->load(['inscriptions']);
        return view('admin.seminaires.show', compact('seminaire'));
    }

    public function edit(Seminaire $seminaire)
    {
        return view('admin.seminaires.edit', compact('seminaire'));
    }

    public function update(Request $request, Seminaire $seminaire)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'intervenant' => 'required|string|max:255',
            'type_seminaire' => 'required|string',
            'description' => 'nullable|string',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date',
            'lieu' => 'required|string|max:255',
            'niveau_cible' => 'nullable|string|max:255',
            'pre_requis' => 'nullable|string',
            'materiel_requis' => 'nullable|string',
            'capacite_max' => 'required|integer|min:1',
            'prix' => 'required|numeric|min:0',
            'statut' => 'required|in:actif,complet,annule',
        ]);
        
        $validated['ouvert_toutes_ecoles'] = $request->has('ouvert_toutes_ecoles');
        
        $seminaire->update($validated);
        
        return redirect()->route('admin.seminaires.index')
            ->with('success', 'Séminaire modifié avec succès.');
    }

    public function destroy(Seminaire $seminaire)
    {
        if ($seminaire->inscriptions()->count() > 0) {
            return redirect()->route('admin.seminaires.index')
                ->with('error', 'Impossible de supprimer un séminaire avec des inscriptions.');
        }
        
        $seminaire->delete();
        
        return redirect()->route('admin.seminaires.index')
            ->with('success', 'Séminaire supprimé avec succès.');
    }
}
