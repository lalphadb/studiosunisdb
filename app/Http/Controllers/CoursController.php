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
    private function joursDisponibles(): array
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

    public function index(Request $request): Response
    {
        $this->authorize('viewAny', Cours::class);
        $withArchives = $request->boolean('archives');
        $query = Cours::with(['instructeur', 'ecole'])
            ->withCount('usersActifs as membres_actifs_count')  // CORRIGÉ: usersActifs au lieu de membresActifs
            ->orderBy('jour_semaine')->orderBy('heure_debut');
        if ($withArchives) {
            $query->onlyTrashed();
        }
        $paginated = $query->paginate(15)->withQueryString();
        $paginated->getCollection()->transform(function ($c) {
            $c->jour_semaine_display = ucfirst($c->jour_semaine);
            $c->heure_debut_format = Carbon::parse($c->heure_debut)->format('H:i');
            $c->heure_fin_format = Carbon::parse($c->heure_fin)->format('H:i');
            $c->inscrits_count = $c->membres_actifs_count; // Utilise le count aliasé
            $c->is_archived = $c->deleted_at !== null;

            return $c;
        });
        $instructeurs = User::role('instructeur')->select('id', 'name', 'email')->orderBy('name')->get();
        $stats = [
            'totalCours' => Cours::count(),
            'coursActifs' => Cours::whereNull('deleted_at')->count(),
            'totalInstructeurs' => $instructeurs->count(),
            'seancesParSemaine' => Cours::whereNull('deleted_at')->count(),
        ];

        return Inertia::render('Cours/Index', ['cours' => $paginated, 'instructeurs' => $instructeurs, 'stats' => $stats,
            'canCreate' => Auth::user()?->can('create', Cours::class) ?? false,
            'canEdit' => Auth::user()?->hasAnyRole(['superadmin', 'admin_ecole']) ?? false,
            'canDelete' => Auth::user()?->hasAnyRole(['superadmin', 'admin_ecole']) ?? false,
            'canExport' => Auth::user()?->can('export', Cours::class) ?? false,
            'showingArchives' => $withArchives, 'newCoursId' => session('new_cours_id')]);
    }

    public function create(): Response
    {
        $this->authorize('create', Cours::class);
        $instructeurs = User::role('instructeur')->where('ecole_id', Auth::user()->ecole_id)
            ->orderBy('name')->get(['id', 'name', 'email']);

        return Inertia::render('Cours/Create', ['instructeurs' => $instructeurs, 'niveaux' => array_keys(Cours::NIVEAUX), 'joursDisponibles' => $this->joursDisponibles()]);
    }

    public function store(StoreCoursRequest $request, CourseService $service)
    {
        $v = $request->validated();
        if ($v['instructeur_id']) {
            $inst = User::find($v['instructeur_id']);
            if (! $inst || $inst->ecole_id !== Auth::user()->ecole_id) {
                return back()->withErrors(['instructeur_id' => 'Instructeur invalide'])->withInput();
            }
        }
        $cours = $service->create($v);

        return redirect()->route('cours.show', $cours)->with('success', 'Cours créé avec succès.');
    }

    public function show(Cours $cours): Response
    {
        $this->authorize('view', $cours);

        return Inertia::render('Cours/Show', ['cours' => $cours]);
    }

    public function edit(Cours $cours): Response
    {
        $this->authorize('update', $cours);
        $instructeurs = User::role('instructeur')->where('ecole_id', Auth::user()->ecole_id)->orderBy('name')->get(['id', 'name', 'email']);

        // Préparer les données avec formatage optimal pour formulaire
        $coursData = $cours->toArray();

        // Formater les dates pour inputs HTML (YYYY-MM-DD)
        if ($coursData['date_debut']) {
            $coursData['date_debut'] = Carbon::parse($coursData['date_debut'])->format('Y-m-d');
        }
        if ($coursData['date_fin']) {
            $coursData['date_fin'] = Carbon::parse($coursData['date_fin'])->format('Y-m-d');
        }

        // Formater les heures pour inputs HTML (HH:MM)
        if ($coursData['heure_debut']) {
            $coursData['heure_debut'] = Carbon::parse($coursData['heure_debut'])->format('H:i');
        }
        if ($coursData['heure_fin']) {
            $coursData['heure_fin'] = Carbon::parse($coursData['heure_fin'])->format('H:i');
        }

        // Assurer valeurs par défaut pour éviter champs vides
        $coursData['instructeur_id'] = $coursData['instructeur_id'] ?? '';
        $coursData['age_max'] = $coursData['age_max'] ?? '';
        $coursData['description'] = $coursData['description'] ?? '';
        $coursData['details_tarif'] = $coursData['details_tarif'] ?? '';

        // Migration ancien système tarif vers nouveau
        if (! $coursData['montant'] && $coursData['tarif_mensuel']) {
            $coursData['montant'] = $coursData['tarif_mensuel'];
            $coursData['type_tarif'] = 'mensuel';
        }

        return Inertia::render('Cours/Edit', [
            'cours' => $coursData,
            'instructeurs' => $instructeurs,
            'niveaux' => array_keys(Cours::NIVEAUX),
            'joursDisponibles' => $this->joursDisponibles(),
        ]);
    }

    // Nouvelle méthode: Dupliquer avec formulaire pré-rempli
    public function duplicateForm(Cours $cours): Response
    {
        $this->authorize('view', $cours);
        $this->authorize('create', Cours::class);

        $instructeurs = User::role('instructeur')->where('ecole_id', Auth::user()->ecole_id)->orderBy('name')->get(['id', 'name', 'email']);

        // Préparer données pour duplication (comme Edit mais avec nom modifié)
        $coursData = $cours->toArray();

        // Modifier le nom pour indiquer que c'est une copie
        $coursData['nom'] = $coursData['nom'].' (Copie)';

        // Désactiver par défaut les copies
        $coursData['actif'] = false;

        // Formater les dates/heures
        if ($coursData['date_debut']) {
            $coursData['date_debut'] = Carbon::parse($coursData['date_debut'])->format('Y-m-d');
        }
        if ($coursData['date_fin']) {
            $coursData['date_fin'] = Carbon::parse($coursData['date_fin'])->format('Y-m-d');
        }
        if ($coursData['heure_debut']) {
            $coursData['heure_debut'] = Carbon::parse($coursData['heure_debut'])->format('H:i');
        }
        if ($coursData['heure_fin']) {
            $coursData['heure_fin'] = Carbon::parse($coursData['heure_fin'])->format('H:i');
        }

        // Assurer valeurs par défaut
        $coursData['instructeur_id'] = $coursData['instructeur_id'] ?? '';
        $coursData['age_max'] = $coursData['age_max'] ?? '';
        $coursData['description'] = $coursData['description'] ?? '';
        $coursData['details_tarif'] = $coursData['details_tarif'] ?? '';

        // Migration tarif
        if (! $coursData['montant'] && $coursData['tarif_mensuel']) {
            $coursData['montant'] = $coursData['tarif_mensuel'];
            $coursData['type_tarif'] = 'mensuel';
        }

        // Retirer l'ID pour éviter confusion
        unset($coursData['id']);
        unset($coursData['created_at']);
        unset($coursData['updated_at']);
        unset($coursData['deleted_at']);

        return Inertia::render('Cours/Create', [
            'coursSource' => $coursData,  // Données pré-remplies
            'instructeurs' => $instructeurs,
            'niveaux' => array_keys(Cours::NIVEAUX),
            'joursDisponibles' => $this->joursDisponibles(),
            'isDuplicate' => true,  // Flag pour indiquer que c'est une duplication
        ]);
    }

    // NOUVELLE MÉTHODE: Vue Planning/Calendrier
    public function planning(Request $request): Response
    {
        $this->authorize('viewAny', Cours::class);

        // Récupérer tous les cours actifs avec instructeur et comptage membres
        $cours = Cours::with(['instructeur'])
            ->withCount('usersActifs as inscrits_count')  // CORRIGÉ: usersActifs au lieu de membresActifs
            ->where('actif', true)
            ->orderBy('jour_semaine')
            ->orderBy('heure_debut')
            ->get();

        // Organiser les cours par jour et heure pour la grille
        $planning = [
            'lundi' => [],
            'mardi' => [],
            'mercredi' => [],
            'jeudi' => [],
            'vendredi' => [],
            'samedi' => [],
            'dimanche' => [],
        ];

        // Remplir le planning
        foreach ($cours as $coursItem) {
            $jour = $coursItem->jour_semaine;
            if (isset($planning[$jour])) {
                $planning[$jour][] = [
                    'id' => $coursItem->id,
                    'nom' => $coursItem->nom,
                    'niveau' => $coursItem->niveau,
                    'heure_debut' => $coursItem->heure_debut ? Carbon::parse($coursItem->heure_debut)->format('H:i') : null,
                    'heure_fin' => $coursItem->heure_fin ? Carbon::parse($coursItem->heure_fin)->format('H:i') : null,
                    'instructeur' => $coursItem->instructeur ? [
                        'id' => $coursItem->instructeur->id,
                        'name' => $coursItem->instructeur->name,
                    ] : null,
                    'instructeur_id' => $coursItem->instructeur_id,
                    'places_max' => $coursItem->places_max,
                    'inscrits_count' => $coursItem->inscrits_count,
                    'actif' => $coursItem->actif,
                    'jour_semaine' => $coursItem->jour_semaine,
                ];
            }
        }

        // Récupérer tous les instructeurs pour les filtres
        $instructeurs = User::role('instructeur')
            ->where('ecole_id', Auth::user()->ecole_id)
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return Inertia::render('Cours/Planning', [
            'planning' => $planning,
            'instructeurs' => $instructeurs,
            'stats' => [
                'totalCours' => $cours->count(),
                'joursActifs' => collect($planning)->filter(fn ($jour) => count($jour) > 0)->count(),
                'totalInstructeurs' => $instructeurs->count(),
                'conflitsDetectes' => $this->detecterConflitsHoraires($cours),
            ],
        ]);
    }

    // Méthode utilitaire pour détecter les conflits horaires
    private function detecterConflitsHoraires($cours)
    {
        $conflits = 0;
        $coursParJour = $cours->groupBy('jour_semaine');

        foreach ($coursParJour as $jour => $coursJour) {
            for ($i = 0; $i < count($coursJour); $i++) {
                for ($j = $i + 1; $j < count($coursJour); $j++) {
                    $cours1 = $coursJour[$i];
                    $cours2 = $coursJour[$j];

                    if ($cours1->conflitHoraire(
                        $cours2->jour_semaine,
                        $cours2->heure_debut,
                        $cours2->heure_fin
                    )) {
                        $conflits++;
                    }
                }
            }
        }

        return $conflits;
    }

    public function update(UpdateCoursRequest $request, Cours $cours, CourseService $service)
    {
        $this->authorize('update', $cours);
        $v = $request->validated();
        if ($v['instructeur_id']) {
            $inst = User::find($v['instructeur_id']);
            if (! $inst || $inst->ecole_id !== Auth::user()->ecole_id) {
                return back()->withErrors(['instructeur_id' => 'Instructeur invalide'])->withInput();
            }
        }
        $service->update($cours, $v);

        return redirect()->route('cours.show', $cours)->with('success', 'Cours mis à jour avec succès.');
    }

    public function destroy(Cours $cours, CourseService $service)
    {
        $this->authorize('delete', $cours);
        if (! $cours->id) {
            Log::warning('Destroy sans id', ['route_param' => request()->route('cours')]);

            return back()->withErrors(['delete' => 'Cours introuvable.']);
        }
        $force = request()->boolean('force');
        if ($force && $cours->usersActifs()->count() > 0) {
            return back()->withErrors(['delete' => 'Inscriptions actives: suppression définitive impossible.']);
        }
        $service->delete($cours, $force);
        $params = [];
        if ($force || request()->boolean('archives')) {
            $params['archives'] = 1;
        }

return redirect()->route('cours.index', $params)->with('success', $force ? 'Cours supprimé définitivement.' : 'Cours archivé avec succès.');
    }

    // Duplication générale
    public function duplicate(Cours $cours)
    {
        $this->authorize('view', $cours);
        $this->authorize('create', Cours::class);

        $nouveau = $cours->dupliquerClone();

        return redirect()->route('cours.index')->with([
            'success' => 'Cours dupliqué avec succès.',
            'new_cours_id' => $nouveau->id,
        ]);
    }

    // Fonctionnalités de duplication spécialisées
    public function duplicateJour(Request $request, Cours $cours)
    {
        $this->authorize('view', $cours);
        $this->authorize('create', Cours::class);
        $d = $request->validate(['nouveau_jour' => 'required|in:lundi,mardi,mercredi,jeudi,vendredi,samedi,dimanche']);
        $n = $cours->dupliquerJour($d['nouveau_jour']);

        return redirect()->route('cours.index')->with(['success' => 'Cours dupliqué pour '.ucfirst($d['nouveau_jour']).' avec succès.', 'new_cours_id' => $n->id]);
    }

    public function duplicateSession(Request $request, Cours $cours)
    {
        $this->authorize('view', $cours);
        $this->authorize('create', Cours::class);
        $d = $request->validate(['nouvelle_session' => 'required|in:automne,hiver,printemps,ete']);
        $n = $cours->duppliquerPourSession($d['nouvelle_session']);

        return redirect()->route('cours.index')->with('success', 'Cours dupliqué pour session '.Cours::SESSIONS[$d['nouvelle_session']].' avec succès.');
    }

    // Gestion des sessions multiples
    public function sessionsForm(Cours $cours): Response
    {
        $this->authorize('update', $cours);

        return Inertia::render('Cours/SessionsCreate', ['cours' => $cours->only(['id', 'nom', 'jour_semaine', 'heure_debut', 'heure_fin']), 'joursDisponibles' => $this->joursDisponibles()]);
    }

    public function createSessions(Request $request, Cours $cours)
    {
        $this->authorize('update', $cours);
        if (! Auth::user()->hasRole('superadmin') && $cours->ecole_id !== Auth::user()->ecole_id) {
            abort(403);
        } $d = $request->validate([
            'jours_semaine' => 'required|array|min:1', 'jours_semaine.*' => 'in:lundi,mardi,mercredi,jeudi,vendredi,samedi,dimanche', 'heure_debut' => 'required|date_format:H:i', 'heure_fin' => 'required|date_format:H:i|after:heure_debut', 'date_debut' => 'required|date', 'date_fin' => 'nullable|date|after:date_debut', 'frequence' => 'required|in:hebdomadaire,bihebdomadaire', 'dupliquer_inscriptions' => 'boolean',
        ]);
        $count = 0;
        foreach ($d['jours_semaine'] as $jour) {
            if ($jour === $cours->jour_semaine) {
                continue;
            } $n = $cours->replicate();
            $n->nom = $cours->nom.' ('.ucfirst($jour).')';
            $n->jour_semaine = $jour;
            $n->heure_debut = $d['heure_debut'];
            $n->heure_fin = $d['heure_fin'];
            $n->date_debut = $d['date_debut'];
            $n->date_fin = $d['date_fin'];
            $n->created_at = now();
            $n->updated_at = now();
            $n->save();
            $count++;
        }

return redirect()->route('cours.index')->with('success', $count.' session(s) créée(s).');
    }

    // Restore pour cours archivés
    public function restore(Cours $cours)
    {
        $this->authorize('update', $cours);
        if (! $cours->trashed()) {
            return back()->withErrors(['restore' => 'Ce cours n\'est pas archivé.']);
        }
        $cours->restore();

        return redirect()->route('cours.index')->with('success','Cours restauré avec succès.');
    }
}
