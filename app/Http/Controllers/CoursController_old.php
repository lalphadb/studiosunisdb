<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\{Cours, User, Membre};

class CoursController extends Controller
{
    /**
     * Affichage de la liste des cours
     */
    public function index()
    {
        $cours = Cours::with(['instructeur', 'membres'])
            ->withCount('membres')
            ->get()
            ->map(function($cours) {
                $cours->places_restantes = $cours->places_max - $cours->membres_count;
                return $cours;
            });

        $instructeurs = User::role(['instructeur', 'admin'])
            ->select('id', 'name')
            ->get();

        $stats = [
            'cours_actifs' => Cours::where('actif', true)->count(),
            'total_inscrits' => Cours::withCount('membres')->get()->sum('membres_count'),
            'taux_occupation' => $this->calculerTauxOccupation(),
            'revenus_cours' => $this->calculerRevenusCoursActuels()
        ];

        return Inertia::render('Cours/Index', [
            'cours' => $cours,
            'instructeurs' => $instructeurs,
            'stats' => $stats
        ]);
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        $instructeurs = User::role(['instructeur', 'admin'])
            ->select('id', 'name')
            ->get();

        return Inertia::render('Cours/Create', [
            'instructeurs' => $instructeurs
        ]);
    }

    /**
     * Sauvegarde d'un nouveau cours
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'instructeur_id' => 'required|exists:users,id',
            'jour_semaine' => 'required|in:lundi,mardi,mercredi,jeudi,vendredi,samedi,dimanche',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
            'places_max' => 'required|integer|min:1|max:50',
            'prix_mensuel' => 'required|numeric|min:0',
            'age_min' => 'nullable|integer|min:3',
            'age_max' => 'nullable|integer|max:100|gte:age_min',
            'niveau_requis' => 'nullable|string',
            'actif' => 'boolean'
        ]);

        $cours = Cours::create($validated);

        return redirect()->route('cours.index')
                        ->with('success', "Cours {$cours->nom} créé avec succès.");
    }

    /**
     * Affichage détaillé d'un cours
     */
    public function show(Cours $cours)
    {
        $cours->load(['instructeur', 'membres.ceintureActuelle', 'presences']);

        return Inertia::render('Cours/Show', [
            'cours' => $cours
        ]);
    }

    /**
     * Formulaire de modification
     */
    public function edit(Cours $cours)
    {
        $instructeurs = User::role(['instructeur', 'admin'])
            ->select('id', 'name')
            ->get();

        return Inertia::render('Cours/Edit', [
            'cours' => $cours,
            'instructeurs' => $instructeurs
        ]);
    }

    /**
     * Mise à jour d'un cours
     */
    public function update(Request $request, Cours $cours)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'instructeur_id' => 'required|exists:users,id',
            'jour_semaine' => 'required|in:lundi,mardi,mercredi,jeudi,vendredi,samedi,dimanche',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
            'places_max' => 'required|integer|min:1|max:50',
            'prix_mensuel' => 'required|numeric|min:0',
            'age_min' => 'nullable|integer|min:3',
            'age_max' => 'nullable|integer|max:100|gte:age_min',
            'niveau_requis' => 'nullable|string',
            'actif' => 'boolean'
        ]);

        $cours->update($validated);

        return redirect()->route('cours.index')
                        ->with('success', "Cours {$cours->nom} mis à jour avec succès.");
    }

    /**
     * Suppression d'un cours
     */
    public function destroy(Cours $cours)
    {
        $nom = $cours->nom;
        $cours->delete();

        return redirect()->route('cours.index')
                        ->with('success', "Cours {$nom} supprimé avec succès.");
    }

    /**
     * Toggle du statut actif/inactif
     */
    public function toggleStatut(Cours $cours)
    {
        $cours->update(['actif' => !$cours->actif]);

        $statut = $cours->actif ? 'activé' : 'désactivé';
        
        return redirect()->back()
                        ->with('success', "Cours {$cours->nom} {$statut} avec succès.");
    }

    /**
     * Calcul du taux d'occupation moyen
     */
    private function calculerTauxOccupation(): int
    {
        $cours = Cours::where('actif', true)->withCount('membres')->get();
        
        if ($cours->isEmpty()) return 0;
        
        $totalOccupation = $cours->sum(function($cours) {
            return $cours->places_max > 0 ? ($cours->membres_count / $cours->places_max) * 100 : 0;
        });
        
        return round($totalOccupation / $cours->count());
    }

    /**
     * Calcul des revenus des cours actuels
     */
    private function calculerRevenusCoursActuels(): float
    {
        return Cours::where('actif', true)
            ->withCount('membres')
            ->get()
            ->sum(function($cours) {
                return $cours->prix_mensuel * $cours->membres_count;
            });
    }
}
