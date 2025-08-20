<?php

namespace App\Http\Controllers;

use App\Models\Cours;
use App\Models\User;
use App\Models\Membre;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CoursController extends Controller
{
    /**
     * Display a listing of the courses.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        // Récupération des cours avec relations
        $cours = Cours::with(['instructeur', 'membres'])
            ->withCount('membres as inscrits_count')
            ->orderBy('jour_semaine')
            ->orderBy('heure_debut')
            ->get()
            ->map(function ($cours) {
                // Ajouter des données formatées
                $cours->jour_semaine_display = $this->getJourSemaineDisplay($cours->jour_semaine);
                $cours->heure_debut_format = Carbon::parse($cours->heure_debut)->format('H:i');
                $cours->heure_fin_format = Carbon::parse($cours->heure_fin)->format('H:i');
                return $cours;
            });

        // Récupération des instructeurs
        $instructeurs = User::role('instructeur')
            ->select('id', 'name', 'email')
            ->orderBy('name')
            ->get();

        // Calcul des statistiques
        $stats = [
            'totalCours' => $cours->count(),
            'coursActifs' => $cours->where('actif', true)->count(),
            'totalInstructeurs' => $instructeurs->count(),
            'seancesParSemaine' => $this->calculateSeancesParSemaine($cours),
        ];

        return Inertia::render('Cours/Index', [
            'cours' => $cours,
            'instructeurs' => $instructeurs,
            'stats' => $stats,
            'canCreate' => Auth::user()->can('cours.create'),
            'canEdit' => Auth::user()->can('cours.edit'),
            'canDelete' => Auth::user()->can('cours.delete'),
        ]);
    }

    /**
     * Show the form for creating a new course.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        $this->authorize('create', Cours::class);

        $instructeurs = User::role('instructeur')
            ->select('id', 'name', 'email')
            ->orderBy('name')
            ->get();

        return Inertia::render('Cours/Create', [
            'instructeurs' => $instructeurs,
            'niveaux' => ['debutant', 'intermediaire', 'avance', 'competition'],
            'joursDisponibles' => $this->getJoursDisponibles(),
        ]);
    }

    /**
     * Store a newly created course in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->authorize('create', Cours::class);

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'instructeur_id' => 'required|exists:users,id',
            'niveau' => 'required|in:debutant,intermediaire,avance,competition',
            'age_min' => 'required|integer|min:3|max:99',
            'age_max' => 'required|integer|min:3|max:99|gte:age_min',
            'places_max' => 'required|integer|min:1|max:50',
            'jour_semaine' => 'required|in:lundi,mardi,mercredi,jeudi,vendredi,samedi,dimanche',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after:date_debut',
            'tarif_mensuel' => 'required|numeric|min:0|max:500',
            'actif' => 'boolean',
        ]);

        // Vérification des conflits horaires
        $conflict = $this->checkScheduleConflict(
            $validated['jour_semaine'],
            $validated['heure_debut'],
            $validated['heure_fin'],
            $validated['instructeur_id']
        );

        if ($conflict) {
            return back()->withErrors([
                'horaire' => 'Un conflit horaire existe avec un autre cours.'
            ])->withInput();
        }

        $cours = Cours::create($validated);

        return redirect()->route('cours.index')
            ->with('success', 'Cours créé avec succès.');
    }

    /**
     * Display the specified course.
     *
     * @param  \App\Models\Cours  $cours
     * @return \Inertia\Response
     */
    public function show(Cours $cours)
    {
        $cours->load(['instructeur', 'membres.user']);

        // Statistiques du cours
        $stats = [
            'totalInscrits' => $cours->membres()->count(),
            'placesDisponibles' => $cours->places_max - $cours->membres()->count(),
            'tauxRemplissage' => ($cours->membres()->count() / $cours->places_max) * 100,
            'presencesMoyenne' => $this->calculatePresenceMoyenne($cours),
        ];

        // Historique des présences (dernières 4 semaines)
        $presencesHistory = $this->getPresencesHistory($cours, 4);

        return Inertia::render('Cours/Show', [
            'cours' => $cours,
            'stats' => $stats,
            'presencesHistory' => $presencesHistory,
            'canEdit' => Auth::user()->can('edit', $cours),
            'canDelete' => Auth::user()->can('delete', $cours),
        ]);
    }

    /**
     * Show the form for editing the specified course.
     *
     * @param  \App\Models\Cours  $cours
     * @return \Inertia\Response
     */
    public function edit(Cours $cours)
    {
        $this->authorize('update', $cours);

        $instructeurs = User::role('instructeur')
            ->select('id', 'name', 'email')
            ->orderBy('name')
            ->get();

        return Inertia::render('Cours/Edit', [
            'cours' => $cours,
            'instructeurs' => $instructeurs,
            'niveaux' => ['debutant', 'intermediaire', 'avance', 'competition'],
            'joursDisponibles' => $this->getJoursDisponibles(),
        ]);
    }

    /**
     * Update the specified course in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cours  $cours
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Cours $cours)
    {
        $this->authorize('update', $cours);

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'instructeur_id' => 'required|exists:users,id',
            'niveau' => 'required|in:debutant,intermediaire,avance,competition',
            'age_min' => 'required|integer|min:3|max:99',
            'age_max' => 'required|integer|min:3|max:99|gte:age_min',
            'places_max' => 'required|integer|min:1|max:50',
            'jour_semaine' => 'required|in:lundi,mardi,mercredi,jeudi,vendredi,samedi,dimanche',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after:date_debut',
            'tarif_mensuel' => 'required|numeric|min:0|max:500',
            'actif' => 'boolean',
        ]);

        // Vérification des conflits horaires (en excluant le cours actuel)
        $conflict = $this->checkScheduleConflict(
            $validated['jour_semaine'],
            $validated['heure_debut'],
            $validated['heure_fin'],
            $validated['instructeur_id'],
            $cours->id
        );

        if ($conflict) {
            return back()->withErrors([
                'horaire' => 'Un conflit horaire existe avec un autre cours.'
            ])->withInput();
        }

        $cours->update($validated);

        return redirect()->route('cours.show', $cours)
            ->with('success', 'Cours mis à jour avec succès.');
    }

    /**
     * Remove the specified course from storage.
     *
     * @param  \App\Models\Cours  $cours
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Cours $cours)
    {
        $this->authorize('delete', $cours);

        // Vérifier s'il y a des inscriptions actives
        if ($cours->membres()->count() > 0) {
            return back()->withErrors([
                'delete' => 'Impossible de supprimer un cours avec des membres inscrits.'
            ]);
        }

        $cours->delete();

        return redirect()->route('cours.index')
            ->with('success', 'Cours supprimé avec succès.');
    }

    /**
     * Duplicate an existing course.
     *
     * @param  \App\Models\Cours  $cours
     * @return \Illuminate\Http\RedirectResponse
     */
    public function duplicate(Cours $cours)
    {
        $this->authorize('create', Cours::class);

        $newCours = $cours->replicate();
        $newCours->nom = $cours->nom . ' (Copie)';
        $newCours->created_at = now();
        $newCours->updated_at = now();
        $newCours->save();

        return redirect()->route('cours.edit', $newCours)
            ->with('success', 'Cours dupliqué avec succès. Vous pouvez maintenant le modifier.');
    }

    /**
     * Display the planning calendar view.
     *
     * @return \Inertia\Response
     */
    public function planning()
    {
        $cours = Cours::with(['instructeur', 'membres'])
            ->where('actif', true)
            ->orderBy('jour_semaine')
            ->orderBy('heure_debut')
            ->get();

        $planning = $this->formatPlanningData($cours);

        return Inertia::render('Cours/Planning', [
            'planning' => $planning,
            'instructeurs' => User::role('instructeur')->get(),
        ]);
    }

    /**
     * Export courses data.
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function export()
    {
        $this->authorize('export', Cours::class);

        $cours = Cours::with(['instructeur', 'membres'])
            ->orderBy('jour_semaine')
            ->orderBy('heure_debut')
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="cours_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($cours) {
            $file = fopen('php://output', 'w');
            
            // En-têtes CSV
            fputcsv($file, [
                'ID', 'Nom', 'Description', 'Instructeur', 'Niveau',
                'Âge Min', 'Âge Max', 'Places Max', 'Inscrits',
                'Jour', 'Début', 'Fin', 'Tarif Mensuel', 'Statut'
            ]);

            // Données
            foreach ($cours as $c) {
                fputcsv($file, [
                    $c->id,
                    $c->nom,
                    $c->description,
                    $c->instructeur->name,
                    $c->niveau,
                    $c->age_min,
                    $c->age_max,
                    $c->places_max,
                    $c->membres->count(),
                    $c->jour_semaine,
                    $c->heure_debut,
                    $c->heure_fin,
                    $c->tarif_mensuel,
                    $c->actif ? 'Actif' : 'Inactif',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Helper: Check for schedule conflicts.
     *
     * @param string $jour
     * @param string $heureDebut
     * @param string $heureFin
     * @param int $instructeurId
     * @param int|null $excludeCoursId
     * @return bool
     */
    private function checkScheduleConflict($jour, $heureDebut, $heureFin, $instructeurId, $excludeCoursId = null)
    {
        $query = Cours::where('jour_semaine', $jour)
            ->where('instructeur_id', $instructeurId)
            ->where('actif', true)
            ->where(function($q) use ($heureDebut, $heureFin) {
                $q->whereBetween('heure_debut', [$heureDebut, $heureFin])
                  ->orWhereBetween('heure_fin', [$heureDebut, $heureFin])
                  ->orWhere(function($q2) use ($heureDebut, $heureFin) {
                      $q2->where('heure_debut', '<=', $heureDebut)
                         ->where('heure_fin', '>=', $heureFin);
                  });
            });

        if ($excludeCoursId) {
            $query->where('id', '!=', $excludeCoursId);
        }

        return $query->exists();
    }

    /**
     * Helper: Calculate average presence for a course.
     *
     * @param \App\Models\Cours $cours
     * @return float
     */
    private function calculatePresenceMoyenne($cours)
    {
        $presences = DB::table('presences')
            ->where('cours_id', $cours->id)
            ->where('statut', 'present')
            ->count();

        $totalSessions = DB::table('presences')
            ->where('cours_id', $cours->id)
            ->distinct('date_cours')
            ->count('date_cours');

        if ($totalSessions == 0) return 0;

        $membresInscrits = $cours->membres()->count();
        if ($membresInscrits == 0) return 0;

        return round(($presences / ($totalSessions * $membresInscrits)) * 100, 2);
    }

    /**
     * Helper: Get presence history for a course.
     *
     * @param \App\Models\Cours $cours
     * @param int $weeks
     * @return array
     */
    private function getPresencesHistory($cours, $weeks = 4)
    {
        $history = [];
        $startDate = Carbon::now()->subWeeks($weeks);

        for ($i = 0; $i < $weeks; $i++) {
            $weekStart = $startDate->copy()->addWeeks($i)->startOfWeek();
            $weekEnd = $weekStart->copy()->endOfWeek();

            $presences = DB::table('presences')
                ->where('cours_id', $cours->id)
                ->whereBetween('date_cours', [$weekStart, $weekEnd])
                ->where('statut', 'present')
                ->count();

            $history[] = [
                'semaine' => $weekStart->format('d/m'),
                'presences' => $presences,
            ];
        }

        return $history;
    }

    /**
     * Helper: Calculate sessions per week.
     *
     * @param \Illuminate\Support\Collection $cours
     * @return int
     */
    private function calculateSeancesParSemaine($cours)
    {
        return $cours->where('actif', true)->count();
    }

    /**
     * Helper: Format planning data for calendar view.
     *
     * @param \Illuminate\Support\Collection $cours
     * @return array
     */
    private function formatPlanningData($cours)
    {
        $planning = [];
        $jours = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'];

        foreach ($jours as $jour) {
            $planning[$jour] = $cours->filter(function($c) use ($jour) {
                return $c->jour_semaine === $jour;
            })->values();
        }

        return $planning;
    }

    /**
     * Helper: Get display name for day of week.
     *
     * @param string $jour
     * @return string
     */
    private function getJourSemaineDisplay($jour)
    {
        $jours = [
            'lundi' => 'Lundi',
            'mardi' => 'Mardi',
            'mercredi' => 'Mercredi',
            'jeudi' => 'Jeudi',
            'vendredi' => 'Vendredi',
            'samedi' => 'Samedi',
            'dimanche' => 'Dimanche',
        ];

        return $jours[$jour] ?? $jour;
    }

    /**
     * Helper: Get available days.
     *
     * @return array
     */
    private function getJoursDisponibles()
    {
        return [
            ['value' => 'lundi', 'label' => 'Lundi'],
            ['value' => 'mardi', 'label' => 'Mardi'],
            ['value' => 'mercredi', 'label' => 'Mercredi'],
            ['value' => 'jeudi', 'label' => 'Jeudi'],
            ['value' => 'vendredi', 'label' => 'Vendredi'],
            ['value' => 'samedi', 'label' => 'Samedi'],
            ['value' => 'dimanche', 'label' => 'Dimanche'],
        ];
    }
}