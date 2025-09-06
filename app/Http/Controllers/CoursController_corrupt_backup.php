<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreCoursRequest;
use App\Http\Requests\UpdateCoursRequest;
use App\Models\Cours;
use App\Models\User;
use App\Services\CourseService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class CoursController extends Controller
{
    /* ===================== Helpers ===================== */
    private function joursDisponibles(): array
    {
        return [
            'lundi' => 'Lundi',
            'mardi' => 'Mardi',
            'mercredi' => 'Mercredi',
            'jeudi' => 'Jeudi',
            'vendredi' => 'Vendredi',
            'samedi' => 'Samedi',
            'dimanche' => 'Dimanche',
        ];
    }

    /* ===================== INDEX ===================== */
    public function index(Request $request): Response
    {
        $this->authorize('viewAny', Cours::class);
        $withArchives = $request->boolean('archives');
        $query = Cours::with(['instructeur','ecole'])
            ->withCount('membresActifs as membres_actifs_count')
            ->orderBy('jour_semaine')
            ->orderBy('heure_debut');
        if ($withArchives) {
            $query->onlyTrashed();
        }
        $paginated = $query->paginate(15)->withQueryString();
        $paginated->getCollection()->transform(function ($c) {
            $c->jour_semaine_display = ucfirst($c->jour_semaine);
            $c->heure_debut_format = Carbon::parse($c->heure_debut)->format('H:i');
            $c->heure_fin_format = Carbon::parse($c->heure_fin)->format('H:i');
            $c->inscrits_count = $c->membres_actifs_count;
            $c->is_archived = $c->deleted_at !== null;
            return $c;
        });
        $instructeurs = User::role('instructeur')->select('id','name','email')->orderBy('name')->get();
        $stats = [
            'totalCours' => Cours::count(),
            'coursActifs' => Cours::whereNull('deleted_at')->count(),
            'totalInstructeurs' => $instructeurs->count(),
            'seancesParSemaine' => Cours::whereNull('deleted_at')->count(),
        ];
        return Inertia::render('Cours/Index', [
            'cours' => $paginated,
            'instructeurs' => $instructeurs,
            'stats' => $stats,
            'canCreate' => Auth::user()?->can('create', Cours::class) ?? false,
            'canEdit' => Auth::user()?->hasAnyRole(['superadmin','admin_ecole']) ?? false,
            'canDelete' => Auth::user()?->hasAnyRole(['superadmin','admin_ecole']) ?? false,
            'canExport' => Auth::user()?->can('export', Cours::class) ?? false,
            'showingArchives' => $withArchives,
            'newCoursId' => session('new_cours_id'),
        ]);
    }

    /* ===================== CREATE / STORE ===================== */
    public function create(): Response
    {
        $this->authorize('create', Cours::class);
        $instructeurs = User::role('instructeur')
            ->where('ecole_id', Auth::user()->ecole_id)
            ->orderBy('name')
            ->get(['id','name','email']);
        return Inertia::render('Cours/Create', [
            'instructeurs' => $instructeurs,
            'niveaux' => array_keys(Cours::NIVEAUX),
            'joursDisponibles' => $this->joursDisponibles(),
        ]);
    }

    public function store(StoreCoursRequest $request, CourseService $service)
    {
        $validated = $request->validated();
        if ($validated['instructeur_id']) {
            $inst = User::find($validated['instructeur_id']);
            if (!$inst || $inst->ecole_id !== Auth::user()->ecole_id) {
                return back()->withErrors(['instructeur_id' => 'Instructeur invalide'])->withInput();
            }
        }
        $cours = $service->create($validated);
        return redirect()->route('cours.show', $cours)->with('success', 'Cours créé avec succès.');
    }

    /* ===================== SHOW ===================== */
    public function show(Cours $cours): Response
    {
        $this->authorize('view', $cours);
        return Inertia::render('Cours/Show', [
            'cours' => $cours,
        ]);
    }

    /* ===================== EDIT / UPDATE ===================== */
    public function edit(Cours $cours): Response
    {
        $this->authorize('update', $cours);
        $instructeurs = User::role('instructeur')
            ->where('ecole_id', Auth::user()->ecole_id)
            ->orderBy('name')
            ->get(['id','name','email']);
        return Inertia::render('Cours/Edit', [
            'cours' => $cours,
            'instructeurs' => $instructeurs,
            'niveaux' => array_keys(Cours::NIVEAUX),
            'joursDisponibles' => $this->joursDisponibles(),
        ]);
    }

    public function update(UpdateCoursRequest $request, Cours $cours, CourseService $service)
    {
        $this->authorize('update', $cours);
        $validated = $request->validated();
        if ($validated['instructeur_id']) {
            $inst = User::find($validated['instructeur_id']);
            if (!$inst || $inst->ecole_id !== Auth::user()->ecole_id) {
                return back()->withErrors(['instructeur_id' => 'Instructeur invalide'])->withInput();
            }
        }
        $service->update($cours, $validated);
        return redirect()->route('cours.show', $cours)->with('success', 'Cours mis à jour avec succès.');
    }

    /* ===================== SUPPRESSION ===================== */
    public function destroy(Cours $cours, CourseService $service)
    {
        $this->authorize('delete', $cours);
        if (!$cours->id) {
            Log::warning('Destroy sans id', ['route_param' => request()->route('cours')]);
            return back()->withErrors(['delete' => 'Cours introuvable.']);
        }
        $force = request()->boolean('force');
        if ($force && $cours->membresActifs()->count() > 0) {
            return back()->withErrors(['delete' => 'Inscriptions actives: suppression définitive impossible.']);
        }
        $service->delete($cours, $force);
        $params = [];
        if ($force || request()->boolean('archives')) {
            $params['archives'] = 1;
        }
        return redirect()->route('cours.index', $params)
            ->with('success', $force ? 'Cours supprimé définitivement.' : 'Cours archivé avec succès.');
    }

    /* ===================== DUPLICATION JOUR ===================== */
    public function duplicateJour(Request $request, Cours $cours)
    {
        $this->authorize('view', $cours);
        $this->authorize('create', Cours::class);
        $data = $request->validate([
            'nouveau_jour' => 'required|in:lundi,mardi,mercredi,jeudi,vendredi,samedi,dimanche'
        ]);
        $nouveau = $cours->duppliquerPourJour($data['nouveau_jour']);
        return redirect()->route('cours.index')
            ->with(['success' => 'Cours dupliqué pour ' . ucfirst($data['nouveau_jour']) . ' avec succès.', 'new_cours_id' => $nouveau->id]);
    }

    /* ===================== DUPLICATION SESSION ===================== */
    public function duplicateSession(Request $request, Cours $cours)
    {
        $this->authorize('view', $cours);
        $this->authorize('create', Cours::class);
        $data = $request->validate([
            'nouvelle_session' => 'required|in:automne,hiver,printemps,ete'
        ]);
        $nouveau = $cours->duppliquerPourSession($data['nouvelle_session']);
        return redirect()->route('cours.index')
            ->with('success', 'Cours dupliqué pour session ' . Cours::SESSIONS[$data['nouvelle_session']] . ' avec succès.');
    }

    /* ===================== FORM SESSIONS MULTIPLES ===================== */
    public function sessionsForm(Cours $cours): Response
    {
        $this->authorize('update', $cours);
        return Inertia::render('Cours/SessionsCreate', [
            'cours' => $cours->only(['id','nom','jour_semaine','heure_debut','heure_fin']),
            'joursDisponibles' => $this->joursDisponibles(),
        ]);
    }

    public function createSessions(Request $request, Cours $cours)
    {
        $this->authorize('update', $cours);
        if (!Auth::user()->hasRole('superadmin') && $cours->ecole_id !== Auth::user()->ecole_id) {
            abort(403);
        }
        $data = $request->validate([
            'jours_semaine' => 'required|array|min:1',
            'jours_semaine.*' => 'in:lundi,mardi,mercredi,jeudi,vendredi,samedi,dimanche',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after:date_debut',
            'frequence' => 'required|in:hebdomadaire,bihebdomadaire',
            'dupliquer_inscriptions' => 'boolean'
        ]);
        $count = 0;
        foreach ($data['jours_semaine'] as $jour) {
            if ($jour === $cours->jour_semaine) {
                continue;
            }
            $n = $cours->replicate();
            $n->nom = $cours->nom . ' (' . ucfirst($jour) . ')';
            $n->jour_semaine = $jour;
            $n->heure_debut = $data['heure_debut'];
            $n->heure_fin = $data['heure_fin'];
            $n->date_debut = $data['date_debut'];
            $n->date_fin = $data['date_fin'];
            $n->created_at = now();
            $n->updated_at = now();
            $n->save();
            $count++;
        }
        return redirect()->route('cours.index')->with('success', $count . ' session(s) créée(s).');
    }
}
     * @return \Inertia\Response
     */
    public function show(Cours $cours)
    {
        // Vérifier authorization - maintenant que le route model binding est fixé
        $this->authorize('view', $cours);
        
        $cours->load(['instructeur', 'membres.user']);

        // Statistiques du cours
        $stats = [
            'totalInscrits' => $cours->membres()->count(),
            'placesDisponibles' => max(0, $cours->places_max - $cours->membres()->count()),
            'tauxRemplissage' => $cours->places_max > 0 ? ($cours->membres()->count() / $cours->places_max) * 100 : 0,
            'presencesMoyenne' => $this->calculatePresenceMoyenne($cours),
        ];

        // Historique des présences (dernières 4 semaines)
        $presencesHistory = $this->getPresencesHistory($cours, 4);

        return Inertia::render('Cours/Show', [
            'cours' => $cours,
            'stats' => $stats,
            'presencesHistory' => $presencesHistory,
            'canEdit' => Auth::user()->can('update', $cours),
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
    public function update(UpdateCoursRequest $request, Cours $cours, CourseService $courseService)
    {
        $this->authorize('update', $cours);
        
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

    $courseService->update($cours, $validated);

        return redirect()->route('cours.show', $cours)
            ->with('success', 'Cours mis à jour avec succès.');
    }

    /**
     * Remove the specified course from storage.
     *
     * @param  \App\Models\Cours  $cours
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Cours $cours, CourseService $courseService)
    {
        $this->authorize('delete', $cours);

        if (!$cours->id) {
            Log::warning('Destroy reçu sans id sur instance Cours', [
                'raw_route_param' => request()->route('cours'),
                'class' => get_class($cours)
            ]);
            return back()->withErrors(['delete' => 'Erreur interne: cours introuvable (binding).']);
        }

    // Nouveau comportement (logs debug retirés):
    //  - Par défaut: soft delete (archivage)
    //  - ?archiver=1 soft delete explicite (compat rétro)
    //  - ?force=1 suppression définitive (si aucune inscription active)

    $force = request()->boolean('force');
    $archiveFlag = request()->boolean('archiver');

        // Refus force delete si membres actifs
        if ($force && $cours->membresActifs()->count() > 0) {
            return back()->withErrors([
                'delete' => 'Ce cours contient encore des inscriptions actives. Désinscrivez-les d\'abord ou archivez le cours.'
            ]);
        }
        
        try {
            // Désactiver éventuellement Telescope si non migré pour éviter erreurs 42S02
            if (class_exists('Laravel\\Telescope\\Telescope')) {
                try { \Laravel\Telescope\Telescope::stopRecording(); } catch (\Throwable $e) {}
            }

            // Capturer l'id tôt (forceDelete va nettoyer l'instance)
            $coursId = $cours->id;
            if ($force) {
                $courseService->delete($cours, true); // forceDelete
                $message = 'Cours supprimé définitivement avec succès.';
            } else {
                $courseService->delete($cours, false); // soft delete
                $message = 'Cours archivé avec succès.';
            }

            // Rester sur la vue Archives si on y était ou si suppression définitive
            $redirectParams = [];
            if ($force || request()->boolean('archives')) {
                $redirectParams['archives'] = 1;
            }
            return redirect()->route('cours.index', $redirectParams)
                ->with('success', $message);
        } catch (\Throwable $e) {
            Log::error('Erreur suppression cours', [
                'cours_id' => $cours->id,
                'user_id' => auth()->id(),
                'exception' => $e->getMessage(),
            ]);
            return back()->withErrors(['delete' => 'Erreur suppression: ' . $e->getMessage()]);
        }
    }

    /**
     * Dupliquer cours pour un autre jour.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cours  $cours
     * @return \Illuminate\Http\RedirectResponse
     */
    public function duplicateJour(Request $request, Cours $cours)
    {
            $this->authorize('delete', $cours);
            if (!$cours->id) {
                Log::warning('Destroy reçu sans id sur instance Cours', [
                    'raw_route_param' => request()->route('cours'),
                    'class' => get_class($cours)
                ]);
                return back()->withErrors(['delete' => 'Erreur interne: cours introuvable (binding).']);
            }
            $force = request()->boolean('force');
            $archiveFlag = request()->boolean('archiver');
     * @param  \App\Models\Cours  $cours
     * @return \Illuminate\Http\RedirectResponse
     */
    public function duplicateSession(Request $request, Cours $cours)
    {
    $this->authorize('view', $cours);
    $this->authorize('create', Cours::class);
        
        $validated = $request->validate([
            'nouvelle_session' => 'required|in:automne,hiver,printemps,ete'
        ]);
        
        $nouveauCours = $cours->duppliquerPourSession($validated['nouvelle_session']);
        
        return redirect()->route('cours.index')
            ->with('success', 'Cours dupliqué pour session ' . Cours::SESSIONS[$validated['nouvelle_session']] . ' avec succès.');
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
            'dimanche' => 'Dimanche',
        ];

        return Inertia::render('Cours/SessionsCreate', [
            'cours' => $cours->only(['id','nom','jour_semaine','heure_debut','heure_fin']),
            'joursDisponibles' => $joursDisponibles,
        ]);
    }

    /**
     * Créer plusieurs sessions (jours multiples) à partir d'un cours existant.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Cours $cours
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createSessions(Request $request, Cours $cours)
    {
        $this->authorize('update', $cours);
    if (!Auth::user()->hasRole('superadmin') && $cours->ecole_id !== Auth::user()->ecole_id) abort(403);
        
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
            // Removed: parent_cours_id et group_uid (colonnes inexistantes)
            
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

    public function choisirHoraire(Request $request, Cours $cours)
    {
        $this->authorize('view', $cours);
        $data = $request->validate(['horaire' => 'required|string|min:3|max:25']);
        $membre = Auth::user()->membre ?? null;
        if (!$membre) return back()->withErrors(['membre' => 'Profil membre requis.']);
        if (!$cours->membres()->where('membre_id', $membre->id)->exists()) {
            $cours->inscrireMembre($membre);
        }
        $cours->membres()->updateExistingPivot($membre->id, [
            'horaire_selectionne' => $data['horaire'],
            'statut_validation' => 'pending',
        ]);
        return back()->with('success', 'Horaire soumis pour validation.');
    }

    public function validerInscription(Cours $cours, Membre $membre)
    {
        $this->authorize('update', $cours);
        $cours->membres()->updateExistingPivot($membre->id, ['statut_validation' => 'approuve']);
        return back()->with('success', 'Inscription validée.');
    }

    public function refuserInscription(Cours $cours, Membre $membre)
    {
        $this->authorize('update', $cours);
        $cours->membres()->updateExistingPivot($membre->id, ['statut_validation' => 'refuse']);
        return back()->with('success', 'Inscription refusée.');
    }

    public function proposerAlternative(Request $request, Cours $cours, Membre $membre)
    {
        $this->authorize('update', $cours);
        $data = $request->validate(['alternative' => 'required|string|min:3|max:50']);
        $cours->membres()->updateExistingPivot($membre->id, [
            'proposition_alternative' => json_encode(['propose' => $data['alternative'], 'date' => now()]),
            'statut_validation' => 'pending',
        ]);
        return back()->with('success', 'Alternative proposée.');
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
                $this->authorize('view', $cours);
                $this->authorize('create', Cours::class);
                $validated = $request->validate([
                    'nouveau_jour' => 'required|in:lundi,mardi,mercredi,jeudi,vendredi,samedi,dimanche'
                ]);
                $nouveauCours = $cours->duppliquerPourJour($validated['nouveau_jour']);
                return redirect()->route('cours.index')
                    ->with(['success' => 'Cours dupliqué pour ' . ucfirst($validated['nouveau_jour']) . ' avec succès.', 'new_cours_id' => $nouveauCours->id]);

            $membresInscrits = $cours->membres()->count();
            if ($membresInscrits == 0) return 0;

            return round(($presences / ($totalSessions * $membresInscrits)) * 100, 2);
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
            $this->authorize('view', $cours);
            $this->authorize('create', Cours::class);
            $validated = $request->validate([
                'nouvelle_session' => 'required|in:automne,hiver,printemps,ete'
            ]);
            $nouveauCours = $cours->duppliquerPourSession($validated['nouvelle_session']);
            return redirect()->route('cours.index')
                ->with('success', 'Cours dupliqué pour session ' . Cours::SESSIONS[$validated['nouvelle_session']] . ' avec succès.');
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
            $this->authorize('update', $cours);
            $joursDisponibles = [
                'lundi'=>'Lundi','mardi'=>'Mardi','mercredi'=>'Mercredi','jeudi'=>'Jeudi','vendredi'=>'Vendredi','samedi'=>'Samedi','dimanche'=>'Dimanche'
            ];
            return Inertia::render('Cours/SessionsCreate', [
                'cours' => $cours->only(['id','nom','jour_semaine','heure_debut','heure_fin']),
                'joursDisponibles' => $joursDisponibles,
            ]);
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
                $this->authorize('update', $cours);
                if (!Auth::user()->hasRole('superadmin') && $cours->ecole_id !== Auth::user()->ecole_id) abort(403);
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
                    if ($jour === $cours->jour_semaine) continue;
                    $nouveau = $cours->replicate();
                    $nouveau->nom = $cours->nom . ' (' . ucfirst($jour) . ')';
                    $nouveau->jour_semaine = $jour;
                    $nouveau->heure_debut = $validated['heure_debut'];
                    $nouveau->heure_fin = $validated['heure_fin'];
                    $nouveau->date_debut = $validated['date_debut'];
                    $nouveau->date_fin = $validated['date_fin'];
                    $nouveau->created_at = now();
                    $nouveau->updated_at = now();
                    $nouveau->save();
                    $sessionsCreees++;
                }
                return redirect()->route('cours.index')
                    ->with('success', $sessionsCreees . ' session(s) créée(s) avec succès.');

    // ===================== MISSING ROUTE METHODS (RÉINTRODUITS SIMPLIFIÉS) =====================

    public function inscrireMembre(Request $request, Cours $cours, \App\Services\EnrollmentService $enrollmentService)
    {
        $this->authorize('view', $cours); // ou policy spécifique d'inscription
        $data = $request->validate(['membre_id' => 'nullable|integer|exists:membres,id']);
        $membre = null;
        if (isset($data['membre_id'])) {
            $membre = Membre::find($data['membre_id']);
        } else {
            $membre = auth()->user()->membre ?? null;
        }
        if (!$membre) {
            return back()->withErrors(['membre' => 'Profil membre introuvable.']);
        }
        if (!$cours->peutInscrire($membre)) {
            return back()->withErrors(['inscription' => 'Conditions non remplies ou déjà inscrit.']);
        }
        $enrollmentService->enroll($cours, $membre);
        return back()->with('success', 'Membre inscrit.');
    }

    public function desinscrireMembre(Request $request, Cours $cours, \App\Services\EnrollmentService $enrollmentService)
    {
        $this->authorize('update', $cours);
        $data = $request->validate(['membre_id' => 'required|integer|exists:membres,id']);
        $membre = Membre::findOrFail($data['membre_id']);
        $enrollmentService->unenroll($cours, $membre);
        return back()->with('success', 'Membre désinscrit.');
    }

    public function listeMembres(Cours $cours)
    {
        $this->authorize('view', $cours);
        $membres = $cours->membres()->with('user:id,name,email')->get()->map(function($m){
            return [
                'id'=>$m->id,
                'nom'=>$m->user->name ?? $m->id,
                'email'=>$m->user->email ?? null,
                'statut'=>$m->pivot->statut_inscription,
                'date_inscription'=>$m->pivot->date_inscription,
            ];
        });
        return response()->json(['cours_id'=>$cours->id,'membres'=>$membres]);
    }

    // Alias route sessions -> sessionsForm (pour compat rétro)
    public function sessions(Cours $cours)
    { return $this->sessionsForm($cours); }

    public function annulerSession(Request $request, Cours $cours)
    { return back()->withErrors(['session' => 'Annulation de session non implémentée (nouveau module simplifié).']); }

    public function reporterSession(Request $request, Cours $cours)
    { return back()->withErrors(['session' => 'Report de session non implémenté.']); }

    public function statistiques(Cours $cours, \App\Services\CourseService $courseService)
    {
        $this->authorize('view', $cours);
        return response()->json(['stats'=>$courseService->stats($cours)]);
    }

    public function presences(Cours $cours)
    {
        $this->authorize('view', $cours);
        // Placeholder: compter présences "present"
        try {
            $count = DB::table('presences')->where('cours_id',$cours->id)->where('statut','present')->count();
        } catch (\Throwable $e) { $count = 0; }
        return response()->json(['cours_id'=>$cours->id,'presences_present'=>$count]);
    }

    public function checkDisponibilites(Request $request)
    {
        $data = $request->validate(['jour'=>'required|in:lundi,mardi,mercredi,jeudi,vendredi,samedi,dimanche']);
        $slots = Cours::where('jour_semaine',$data['jour'])->orderBy('heure_debut')->get(['id','heure_debut','heure_fin']);
        return response()->json(['jour'=>$data['jour'],'creneaux'=>$slots]);
    }

    public function checkConflits(Request $request, \App\Services\CourseService $courseService)
    {
        $data = $request->validate([
            'jour'=>'required|in:lundi,mardi,mercredi,jeudi,vendredi,samedi,dimanche',
            'heure_debut'=>'required|date_format:H:i',
            'heure_fin'=>'required|date_format:H:i|after:heure_debut',
            'instructeur_id'=>'required|integer|exists:users,id',
            'exclude_id'=>'nullable|integer'
        ]);
        $conflict = $courseService->hasScheduleConflict($data['jour'],$data['heure_debut'],$data['heure_fin'],$data['instructeur_id'],$data['exclude_id']??null);
        return response()->json(['conflict'=>$conflict]);
    }

    public function search(Request $request)
    {
        $q = $request->get('q');
        $results = Cours::with('instructeur:id,name')
            ->when($q,function($query,$q){ $query->where('nom','like','%'.$q.'%'); })
            ->limit(25)->get(['id','nom','jour_semaine','heure_debut','heure_fin','niveau','instructeur_id']);
        return response()->json(['results'=>$results]);
    }

    public function calendrier(Request $request)
    {
        $cours = Cours::with('instructeur:id,name')->actif()->get();
        $events = $cours->map(function($c){ return [
            'id'=>$c->id,
            'title'=>$c->nom,
            'day'=>$c->jour_semaine,
            'start'=>$c->heure_debut,
            'end'=>$c->heure_fin,
            'instructor'=>$c->instructeur->name ?? null,
        ];});
        return response()->json(['events'=>$events]);
    }

    public function planningGeneral()
    { return $this->planning(); }

    /**
     * Restaurer (un-archiver) un cours soft-deleted.
     */
    public function restore($id)
    {
        $cours = Cours::withTrashed()->findOrFail($id);
        $this->authorize('update', $cours);
        if (!$cours->trashed()) {
            return back()->with('info','Ce cours n\'est pas archivé.');
        }
        $cours->restore();
        return redirect()->route('cours.index')->with('success','Cours restauré.');
    }
}
