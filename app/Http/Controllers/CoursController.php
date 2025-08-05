<?php

namespace App\Http\Controllers;

use App\Models\Cours;
use App\Models\CoursHoraire;
use App\Models\SessionCours;
use App\Models\Membre;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;
use Carbon\Carbon;

/**
 * Contrôleur Cours Ultra-Professionnel Laravel 11
 * Gestion complète des cours avec horaires et sessions
 */
class CoursController extends Controller
{
    /**
     * Afficher la liste des cours avec filtres et statistiques
     */
    public function index(Request $request): Response
    {
        $query = Cours::query();

        // Filtres
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nom', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('saison')) {
            $query->where('saison', $request->saison);
        }

        if ($request->filled('niveau')) {
            $query->where('niveau', $request->niveau);
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        // Tri par défaut
        $query->orderBy('saison', 'desc')
              ->orderBy('nom', 'asc');

        $cours = $query->paginate(15)->appends($request->query());

        // Statistiques
        $stats = $this->calculateCoursStats();

        return Inertia::render('Cours/IndexNew', [
            'cours' => $cours->items(),
            'pagination' => [
                'current_page' => $cours->currentPage(),
                'last_page' => $cours->lastPage(),
                'per_page' => $cours->perPage(),
                'total' => $cours->total(),
            ],
            'stats' => $stats,
            'filters' => $request->only(['search', 'saison', 'niveau', 'statut'])
        ]);
    }

    /**
     * Créer un nouveau cours
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'niveau' => 'required|in:debutant,intermediaire,avance,expert,mixte',
            'saison' => 'required|in:automne,hiver,printemps,ete',
            'age_minimum' => 'nullable|integer|min:3|max:100',
            'capacite_max' => 'required|integer|min:1|max:50',
            'tarif_mensuel' => 'required|numeric|min:0|max:1000',
            'tarif_seance' => 'nullable|numeric|min:0|max:200',
            'tarif_carte' => 'nullable|numeric|min:0|max:2000',
            'horaires' => 'nullable|array',
            'horaires.*.jour' => 'required|in:lundi,mardi,mercredi,jeudi,vendredi,samedi,dimanche',
            'horaires.*.heure_debut' => 'required|date_format:H:i',
            'horaires.*.heure_fin' => 'required|date_format:H:i|after:horaires.*.heure_debut'
        ]);

        $cours = Cours::create([
            'nom' => $validated['nom'],
            'description' => $validated['description'],
            'niveau' => $validated['niveau'],
            'saison' => $validated['saison'],
            'age_minimum' => $validated['age_minimum'],
            'capacite_max' => $validated['capacite_max'],
            'tarif_mensuel' => $validated['tarif_mensuel'],
            'tarif_seance' => $validated['tarif_seance'],
            'tarif_carte' => $validated['tarif_carte'],
            'statut' => 'actif',
            'visible_inscription' => true
        ]);

        // Créer les horaires
        if (!empty($validated['horaires'])) {
            foreach ($validated['horaires'] as $horaire) {
                if (class_exists('App\Models\CoursHoraire')) {
                    CoursHoraire::create([
                        'cours_id' => $cours->id,
                        'jour' => $horaire['jour'],
                        'heure_debut' => $horaire['heure_debut'],
                        'heure_fin' => $horaire['heure_fin']
                    ]);
                }
            }
        }

        return redirect()->route('cours.index')
                         ->with('success', "✅ Cours \"{$cours->nom}\" créé avec succès!");
    }

    /**
     * Afficher un cours spécifique
     */
    public function show(Cours $cours): Response
    {
        return Inertia::render('Cours/Show', [
            'cours' => $cours,
            'stats' => [
                'inscriptions_actives' => 0,
                'revenus_mensuels' => $cours->tarif_mensuel ?? 0,
                'taux_presence' => 0,
                'prochaine_session' => null
            ]
        ]);
    }

    /**
     * Modifier un cours
     */
    public function update(Request $request, Cours $cours): RedirectResponse
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'niveau' => 'required|in:debutant,intermediaire,avance,expert,mixte',
            'saison' => 'required|in:automne,hiver,printemps,ete',
            'age_minimum' => 'nullable|integer|min:3|max:100',
            'capacite_max' => 'required|integer|min:1|max:50',
            'tarif_mensuel' => 'required|numeric|min:0|max:1000',
            'tarif_seance' => 'nullable|numeric|min:0|max:200',
            'tarif_carte' => 'nullable|numeric|min:0|max:2000',
            'statut' => 'required|in:actif,inactif,complet,annule',
            'visible_inscription' => 'boolean'
        ]);

        $cours->update($validated);

        return redirect()->route('cours.index')
                         ->with('success', "✅ Cours \"{$cours->nom}\" modifié avec succès!");
    }

    /**
     * Supprimer un cours
     */
    public function destroy(Cours $cours): RedirectResponse
    {
        $nom = $cours->nom;

        // Supprimer en cascade (soft delete)
        $cours->delete();

        return redirect()->route('cours.index')
                         ->with('success', "🗑️ Cours \"{$nom}\" supprimé avec succès.");
    }

    /**
     * Gestion des horaires d'un cours (API)
     */
    public function horaires(Cours $cours)
    {
        $horaires = [];
        $instructeurs = [];

        if (class_exists('App\Models\CoursHoraire')) {
            $horaires = CoursHoraire::where('cours_id', $cours->id)->get();
        }

        $instructeurs = Membre::where('role', 'instructeur')->get(['id', 'nom', 'prenom']);

        return response()->json([
            'horaires' => $horaires,
            'instructeurs' => $instructeurs
        ]);
    }

    /**
     * Ajouter un horaire à un cours
     */
    public function storeHoraire(Request $request, Cours $cours): RedirectResponse
    {
        if (!class_exists('App\Models\CoursHoraire')) {
            return back()->withErrors(['horaire' => 'Modèle CoursHoraire non disponible.']);
        }

        $validated = $request->validate([
            'jour' => 'required|in:lundi,mardi,mercredi,jeudi,vendredi,samedi,dimanche',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
            'salle' => 'nullable|string|max:100',
            'instructeur_id' => 'nullable|exists:membres,id'
        ]);

        CoursHoraire::create(array_merge($validated, ['cours_id' => $cours->id]));

        return back()->with('success', '⏰ Horaire ajouté avec succès!');
    }

    /**
     * Modifier un horaire
     */
    public function updateHoraire(Request $request, Cours $cours, $horaireId): RedirectResponse
    {
        if (!class_exists('App\Models\CoursHoraire')) {
            return back()->withErrors(['horaire' => 'Modèle CoursHoraire non disponible.']);
        }

        $validated = $request->validate([
            'jour' => 'required|in:lundi,mardi,mercredi,jeudi,vendredi,samedi,dimanche',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
            'salle' => 'nullable|string|max:100',
            'instructeur_id' => 'nullable|exists:membres,id'
        ]);

        $horaire = CoursHoraire::findOrFail($horaireId);
        $horaire->update($validated);

        return back()->with('success', '⏰ Horaire modifié avec succès!');
    }

    /**
     * Supprimer un horaire
     */
    public function destroyHoraire(Cours $cours, $horaireId): RedirectResponse
    {
        if (!class_exists('App\Models\CoursHoraire')) {
            return back()->withErrors(['horaire' => 'Modèle CoursHoraire non disponible.']);
        }

        $horaire = CoursHoraire::findOrFail($horaireId);
        $horaire->delete();

        return back()->with('success', '🗑️ Horaire supprimé avec succès!');
    }

    /**
     * Générer les sessions d'une saison
     */
    public function genererSessionsSaison(Request $request, Cours $cours): RedirectResponse
    {
        $validated = $request->validate([
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'exclure_conges' => 'boolean'
        ]);

        // Placeholder pour la génération de sessions
        $sessionsCreees = 0;

        return back()->with('success', "📅 {$sessionsCreees} sessions générées pour la saison {$cours->saison}!");
    }

    /**
     * Calculer les statistiques des cours
     */
    private function calculateCoursStats(): array
    {
        return [
            'total_cours' => Cours::count(),
            'cours_actifs' => Cours::where('statut', 'actif')->count(),
            'total_inscriptions' => 0,
            'sessions_semaine' => 0,
            'revenus_mensuels' => 0,
            'cours_par_saison' => Cours::select('saison')
                ->selectRaw('count(*) as total')
                ->groupBy('saison')
                ->pluck('total', 'saison')
                ->toArray()
        ];
    }
}
