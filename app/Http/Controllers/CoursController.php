<?php

namespace App\Http\Controllers;

use App\Models\Cours;
use App\Models\User;
use App\Models\Membre;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCoursRequest;
use App\Http\Requests\UpdateCoursRequest;
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
        // Vérification auth explicite avec message détaillé
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Vous devez être connecté pour accéder aux cours.');
        }

        $user = auth()->user();
        
        // Vérification permissions avec redirection si problème
        if (!$user->can('viewAny', Cours::class)) {
            // Si pas de rôles du tout, problème de session
            if (!$user->hasAnyRole(['superadmin','admin_ecole','instructeur','membre'])) {
                return redirect()->route('login')
                    ->with('error', 'Session expirée. Veuillez vous reconnecter.');
            }
            
            // Sinon, vraiment pas de permissions
            abort(403, 'Accès refusé aux cours. Rôles requis: admin_ecole, instructeur ou membre.');
        }
        // Récupération optimisée des cours avec relations (éviter N+1)
        $cours = Cours::with(['instructeur', 'ecole'])
            ->withCount('membresActifs as membres_actifs_count') // Utiliser relation existante
            ->orderBy('jour_semaine')
            ->orderBy('heure_debut')
            ->get()
            ->map(function ($cours) {
                // Ajouter des données formatées
                $cours->jour_semaine_display = $this->getJourSemaineDisplay($cours->jour_semaine);
                $cours->heure_debut_format = Carbon::parse($cours->heure_debut)->format('H:i');
                $cours->heure_fin_format = Carbon::parse($cours->heure_fin)->format('H:i');
                $cours->instructeur_nom = $cours->instructeur ? $cours->instructeur->name : 'Non assigné';
                // Renommer pour éviter conflit avec relation
                $cours->inscrits_count = $cours->membres_actifs_count;
                return $cours;
            });

        // Récupération optimisée des instructeurs
        $instructeurs = User::withoutGlobalScopes()
            ->role('instructeur')
            ->select('id', 'name', 'email')
            ->orderBy('name')
            ->get();

        // Calcul des statistiques (une seule fois)
        $stats = [
            'totalCours' => $cours->count(),
            'coursActifs' => $cours->where('actif', true)->count(),
            'totalInstructeurs' => $instructeurs->count(),
            'seancesParSemaine' => $cours->where('actif', true)->count(),
        ];

        return Inertia::render('Cours/Index', [
            'cours' => $cours->values(), // Reset des clés pour JSON propre
            'instructeurs' => $instructeurs,
            'stats' => $stats,
            'canCreate' => auth()->check() ? Auth::user()->can('create', Cours::class) : false,
            // Permissions globales pour l'interface
            'canEdit' => auth()->check() ? Auth::user()->hasAnyRole(['superadmin','admin_ecole']) : false,
            'canDelete' => auth()->check() ? Auth::user()->hasAnyRole(['superadmin','admin_ecole']) : false,
            'canExport' => auth()->check() ? Auth::user()->can('export', Cours::class) : false,
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
            ->where('ecole_id', auth()->user()->ecole_id)
            ->select('id', 'name', 'email')
            ->orderBy('name')
            ->get();

        return Inertia::render('Cours/Create', [
            'instructeurs' => $instructeurs,
            'niveaux' => array_keys(Cours::NIVEAUX), // Utiliser les niveaux étendus du modèle
            'joursDisponibles' => $this->getJoursDisponibles(),
        ]);
    }

    /**
     * Store a newly created course in storage.
     *
     * @param  \App\Http\Requests\StoreCoursRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreCoursRequest $request)
    {
        // Autorisation déjà gérée dans StoreCoursRequest::authorize()
        
        // Validation et préparation des données déjà gérées dans StoreCoursRequest
        $validated = $request->validated();
        
        // Validation instructeur même école si assigné
        if ($validated['instructeur_id']) {
            $instructeur = User::find($validated['instructeur_id']);
            if (!$instructeur || $instructeur->ecole_id !== auth()->user()->ecole_id) {
                return back()->withErrors([
                    'instructeur_id' => 'L\'instructeur doit appartenir à votre école.'
                ])->withInput();
            }
        }

        // Vérification des conflits horaires si instructeur assigné
        if ($validated['instructeur_id']) {
            $conflict = $this->checkScheduleConflict(
                $validated['jour_semaine'],
                $validated['heure_debut'],
                $validated['heure_fin'],
                $validated['instructeur_id']
            );

            if ($conflict) {
                return back()->withErrors([
                    'horaire' => 'Un conflit horaire existe avec un autre cours de cet instructeur.'
                ])->withInput();
            }
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
            'tauxRemplissage' => $cours->places_max > 0 ? ($cours->membres()->count() / $cours->places_max) * 100 : 0,
            'presencesMoyenne' => $this->calculatePresenceMoyenne($cours),
        ];

        // Historique des présences (dernières 4 semaines)
        $presencesHistory = $this->getPresencesHistory($cours, 4);

        return Inertia::render('Cours/Show', [
            'cours' => $cours,
            'stats' => $stats,
            'presencesHistory' => $presencesHistory,
            'canEdit' => auth()->check() ? Auth::user()->can('update', $cours) : false,
            'canDelete' => auth()->check() ? Auth::user()->can('delete', $cours) : false,
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
            ->where('ecole_id', auth()->user()->ecole_id)
            ->select('id', 'name', 'email')
            ->orderBy('name')
            ->get();

        return Inertia::render('Cours/Edit', [
            'cours' => $cours,
            'instructeurs' => $instructeurs,
            'niveaux' => array_keys(Cours::NIVEAUX), // Utiliser les niveaux étendus du modèle
            'joursDisponibles' => $this->getJoursDisponibles(),
        ]);
    }

    /**
     * Update the specified course in storage.
     *
     * @param  \App\Http\Requests\UpdateCoursRequest  $request
     * @param  \App\Models\Cours  $cours
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateCoursRequest $request, Cours $cours)
    {
        // Autorisation déjà gérée dans UpdateCoursRequest::authorize()
        
        // Validation et préparation des données déjà gérées dans UpdateCoursRequest
        $validated = $request->validated();

        // Validation instructeur même école si assigné
        if ($validated['instructeur_id']) {
            $instructeur = User::find($validated['instructeur_id']);
            if (!$instructeur || $instructeur->ecole_id !== auth()->user()->ecole_id) {
                return back()->withErrors([
                    'instructeur_id' => 'L\'instructeur doit appartenir à votre école.'
                ])->withInput();
            }
        }

        // Vérification des conflits horaires si instructeur assigné (en excluant le cours actuel)
        if ($validated['instructeur_id']) {
            $conflict = $this->checkScheduleConflict(
                $validated['jour_semaine'],
                $validated['heure_debut'],
                $validated['heure_fin'],
                $validated['instructeur_id'],
                $cours->id
            );

            if ($conflict) {
                return back()->withErrors([
                    'horaire' => 'Un conflit horaire existe avec un autre cours de cet instructeur.'
                ])->withInput();
            }
        }

        $cours->update($validated);

        return redirect()->route('cours.show', $cours)
            ->with('success', 'Cours mis à jour avec succès.');
    }

    /**
     * Duplicate the specified course.
     *
     * @param  \App\Models\Cours  $cours
     * @return \Illuminate\Http\RedirectResponse
     */
    public function duplicate(Cours $cours)
    {
        $this->authorize('create', Cours::class);
        
        // Créer une copie du cours avec des modifications
        $nouveauCours = $cours->replicate();
        $nouveauCours->nom = $cours->nom . ' (Copie)';
        $nouveauCours->actif = false; // Désactivé par défaut
        $nouveauCours->created_at = now();
        $nouveauCours->updated_at = now();
        
        $nouveauCours->save();
        
        return redirect()->route('cours.edit', $nouveauCours)
            ->with('success', 'Cours dupliqué avec succès. Modifiez les détails nécessaires.');
    }

    /**
     * Show the form for creating multiple sessions.
     *
     * @param  \App\Models\Cours  $cours
     * @return \Inertia\Response
     */
    public function sessionsForm(Cours $cours)
    {
        $this->authorize('update', $cours);
        
        $joursDisponibles = [
            'lundi' => 'Lundi',
            'mardi' => 'Mardi', 
            'mercredi' => 'Mercredi',
            'jeudi' => 'Jeudi',
            'vendredi' => 'Vendredi',
            'samedi' => 'Samedi',
            'dimanche' => 'Dimanche'
        ];
        
        // Enlever le jour actuel de la liste
        unset($joursDisponibles[$cours->jour_semaine]);
        
        return Inertia::render('Cours/Sessions', [
            'cours' => $cours->load('instructeur'),
            'joursDisponibles' => $joursDisponibles,
        ]);
    }

    /**
     * Create multiple sessions for a course.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cours  $cours
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createSessions(Request $request, Cours $cours)
    {
        $this->authorize('update', $cours);
        
        $validated = $request->validate([
            'jours_semaine' => 'required|array|min:1',
            'jours_semaine.*' => 'in:lundi,mardi,mercredi,jeudi,vendredi,samedi,dimanche',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after:date_debut',
            'frequence' => 'required|in:hebdomadaire,bihebdomadaire',
            'dupliquer_inscriptions' => 'boolean'
        ]);
        
        $sessionsCreees = 0;
        
        foreach ($validated['jours_semaine'] as $jour) {
            // Éviter de dupliquer le jour existant
            if ($jour === $cours->jour_semaine) {
                continue;
            }
            
            // Créer nouvelle session
            $nouveauCours = $cours->replicate();
            $nouveauCours->nom = $cours->nom . ' (' . ucfirst($jour) . ')';
            $nouveauCours->jour_semaine = $jour;
            $nouveauCours->heure_debut = $validated['heure_debut'];
            $nouveauCours->heure_fin = $validated['heure_fin'];
            $nouveauCours->date_debut = $validated['date_debut'];
            $nouveauCours->date_fin = $validated['date_fin'];
            $nouveauCours->created_at = now();
            $nouveauCours->updated_at = now();
            
            // Vérifier conflits horaires
            $conflit = $this->checkScheduleConflict(
                $jour,
                $validated['heure_debut'],
                $validated['heure_fin'],
                $cours->instructeur_id
            );
            
            if ($conflit) {
                continue; // Passer ce jour en cas de conflit
            }
            
            $nouveauCours->save();
            
            // Dupliquer les inscriptions si demandé
            if ($validated['dupliquer_inscriptions'] ?? false) {
                $membres = $cours->membresActifs;
                foreach ($membres as $membre) {
                    $nouveauCours->inscrireMembre($membre);
                }
            }
            
            $sessionsCreees++;
        }
        
        return redirect()->route('cours.show', $cours)
            ->with('success', "$sessionsCreees session(s) supplémentaire(s) créée(s) avec succès.");
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
        if ($cours->membresActifs()->count() > 0) {
            return back()->withErrors([
                'delete' => 'Impossible de supprimer un cours avec des membres inscrits.'
            ]);
        }

        $cours->delete();

        return redirect()->route('cours.index')
            ->with('success', 'Cours supprimé avec succès.');
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
            'instructeurs' => User::role('instructeur')->where('ecole_id', auth()->user()->ecole_id)->get(),
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
                    $c->instructeur ? $c->instructeur->name : 'Non assigné',
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
        try {
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
        } catch (\Exception $e) {
            return 0;
        }
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

            try {
                $presences = DB::table('presences')
                    ->where('cours_id', $cours->id)
                    ->whereBetween('date_cours', [$weekStart, $weekEnd])
                    ->where('statut', 'present')
                    ->count();

                $history[] = [
                    'semaine' => $weekStart->format('d/m'),
                    'presences' => $presences,
                ];
            } catch (\Exception $e) {
                $history[] = [
                    'semaine' => $weekStart->format('d/m'),
                    'presences' => 0,
                ];
            }
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
