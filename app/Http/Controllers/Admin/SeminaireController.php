<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seminaire;
use App\Models\Membre;
use App\Models\InscriptionSeminaire;
use App\Models\Ecole;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Carbon\Carbon;

class SeminaireController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            'permission:manage_ceintures', // Utilise la même permission pour l'instant
        ];
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        
        $query = Seminaire::with(['inscriptions']);

        // Filtres
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nom', 'LIKE', "%{$search}%")
                  ->orWhere('intervenant', 'LIKE', "%{$search}%");
        }

        if ($request->filled('type_seminaire')) {
            $query->where('type_seminaire', $request->type_seminaire);
        }

        $seminaires = $query->orderBy('date_debut', 'desc')->paginate(15);
        $ecoles = $user->hasRole('superadmin') ? Ecole::orderBy('nom')->get() : collect([$user->ecole]);

        return view('admin.seminaires.index', compact('seminaires', 'ecoles'));
    }

    public function create(Request $request)
    {
        $user = auth()->user();
        
        // Types de séminaires disponibles
        $typesSeminaires = [
            'technique' => 'Technique',
            'kata' => 'Kata',
            'competition' => 'Compétition',
            'arbitrage' => 'Arbitrage',
            'self_defense' => 'Self-Défense',
            'armes' => 'Armes',
            'meditation' => 'Méditation',
            'histoire' => 'Histoire',
            'autre' => 'Autre'
        ];
        
        // Pré-sélection si membre_id en paramètre (pour inscription directe)
        $membreSelectionne = null;
        if ($request->filled('membre_id')) {
            $membreQuery = Membre::with('ecole');
            if (!$user->hasRole('superadmin')) {
                $membreQuery->where('ecole_id', $user->ecole_id);
            }
            $membreSelectionne = $membreQuery->find($request->membre_id);
        }

        return view('admin.seminaires.create', compact('typesSeminaires', 'membreSelectionne'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'required|string',
            'type_seminaire' => 'required|in:technique,kata,competition,arbitrage,self_defense,armes,meditation,histoire,autre',
            'intervenant' => 'required|string|max:255',
            'date_debut' => 'required|date|after:today',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'lieu' => 'required|string|max:255',
            'capacite_max' => 'required|integer|min:1|max:200',
            'prix' => 'required|numeric|min:0',
            'ouvert_toutes_ecoles' => 'boolean',
            'niveau_cible' => 'nullable|string|max:255',
            'pre_requis' => 'nullable|string',
            'materiel_requis' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['ouvert_toutes_ecoles'] = $request->has('ouvert_toutes_ecoles');
        $data['statut'] = 'actif';

        Seminaire::create($data);

        return redirect()->route('admin.seminaires.index')
            ->with('success', 'Séminaire créé avec succès.');
    }

    public function show(string $id)
    {
        $seminaire = Seminaire::with(['inscriptions.membre.ecole'])->findOrFail($id);

        // Statistiques des inscriptions
        $stats = [
            'total_inscrits' => $seminaire->inscriptions->count(),
            'presents' => $seminaire->inscriptions->where('statut', 'present')->count(),
            'absents' => $seminaire->inscriptions->where('statut', 'absent')->count(),
            'en_attente' => $seminaire->inscriptions->where('statut', 'inscrit')->count(),
            'annules' => $seminaire->inscriptions->where('statut', 'annule')->count(),
        ];

        return view('admin.seminaires.show', compact('seminaire', 'stats'));
    }

    public function edit(string $id)
    {
        $seminaire = Seminaire::findOrFail($id);
        
        $typesSeminaires = [
            'technique' => 'Technique',
            'kata' => 'Kata',
            'competition' => 'Compétition',
            'arbitrage' => 'Arbitrage',
            'self_defense' => 'Self-Défense',
            'armes' => 'Armes',
            'meditation' => 'Méditation',
            'histoire' => 'Histoire',
            'autre' => 'Autre'
        ];

        return view('admin.seminaires.edit', compact('seminaire', 'typesSeminaires'));
    }

    public function update(Request $request, string $id)
    {
        $seminaire = Seminaire::findOrFail($id);

        $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'required|string',
            'type_seminaire' => 'required|in:technique,kata,competition,arbitrage,self_defense,armes,meditation,histoire,autre',
            'intervenant' => 'required|string|max:255',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'lieu' => 'required|string|max:255',
            'capacite_max' => 'required|integer|min:1|max:200',
            'prix' => 'required|numeric|min:0',
            'statut' => 'required|in:actif,complet,annule',
            'ouvert_toutes_ecoles' => 'boolean',
            'niveau_cible' => 'nullable|string|max:255',
            'pre_requis' => 'nullable|string',
            'materiel_requis' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['ouvert_toutes_ecoles'] = $request->has('ouvert_toutes_ecoles');

        $seminaire->update($data);

        return redirect()->route('admin.seminaires.show', $seminaire->id)
            ->with('success', 'Séminaire mis à jour avec succès.');
    }

    public function destroy(string $id)
    {
        $seminaire = Seminaire::findOrFail($id);

        // Seulement le superadmin peut supprimer
        if (!auth()->user()->hasRole('superadmin')) {
            abort(403, 'Seul le superadmin peut supprimer des séminaires.');
        }

        $seminaire->delete();

        return redirect()->route('admin.seminaires.index')
            ->with('success', 'Séminaire supprimé avec succès.');
    }

    /**
     * Inscrire un membre à un séminaire
     */
    public function inscrire(Request $request, $seminaire_id)
    {
        $request->validate([
            'membre_id' => 'required|exists:membres,id',
        ]);

        $seminaire = Seminaire::findOrFail($seminaire_id);
        $membre = Membre::findOrFail($request->membre_id);
        $user = auth()->user();

        // Vérifications
        if (!$user->hasRole('superadmin') && $membre->ecole_id !== $user->ecole_id) {
            abort(403, 'Accès non autorisé à ce membre.');
        }

        if ($seminaire->inscriptions->count() >= $seminaire->capacite_max) {
            return back()->withErrors(['capacite' => 'Le séminaire est complet.']);
        }

        // Vérifier si déjà inscrit
        $dejainscrit = InscriptionSeminaire::where('seminaire_id', $seminaire_id)
            ->where('membre_id', $request->membre_id)
            ->exists();

        if ($dejainscrit) {
            return back()->withErrors(['inscription' => 'Ce membre est déjà inscrit à ce séminaire.']);
        }

        // Créer l'inscription
        InscriptionSeminaire::create([
            'seminaire_id' => $seminaire_id,
            'membre_id' => $request->membre_id,
            'ecole_id' => $membre->ecole_id,
            'date_inscription' => now(),
            'statut' => 'inscrit',
            'montant_paye' => $seminaire->prix ?? 0,
        ]);

        return redirect()->route('admin.seminaires.show', $seminaire_id)
            ->with('success', 'Membre inscrit au séminaire avec succès.');
    }

    /**
     * Marquer présence/absence au séminaire
     */
    public function marquerPresence(Request $request, $seminaire_id)
    {
        $request->validate([
            'inscriptions' => 'required|array',
            'inscriptions.*' => 'in:present,absent'
        ]);

        $seminaire = Seminaire::findOrFail($seminaire_id);

        foreach ($request->inscriptions as $inscription_id => $statut) {
            $inscription = InscriptionSeminaire::where('seminaire_id', $seminaire_id)
                ->where('id', $inscription_id)
                ->first();

            if ($inscription) {
                $inscription->update(['statut' => $statut]);
                
                // Si présent, marquer certificat obtenu
                if ($statut === 'present') {
                    $inscription->update(['certificat_obtenu' => true]);
                }
            }
        }

        return redirect()->route('admin.seminaires.show', $seminaire_id)
            ->with('success', 'Présences mises à jour avec succès.');
    }
}
