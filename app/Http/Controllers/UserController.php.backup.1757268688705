<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exports\MembersExport;
use App\Http\Requests\Membres\BulkMembreActionRequest;
use App\Http\Requests\Membres\StoreMembreRequest;
use App\Http\Requests\Membres\UpdateMembreRequest;
use App\Models\Ceinture;
use App\Models\Membre;
use App\Models\User;
use App\Services\ProgressionCeintureService;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;

final class MembreController extends Controller
{
    public function __construct(
        private ProgressionCeintureService $progressionService
    ) {
        // Policy MembrePolicy recommandée
        $this->authorizeResource(Membre::class, 'membre');
    }

    public function index(Request $request): Response
    {
        $filters = [
            'q'           => trim((string) $request->string('q')),
            'statut'      => $request->string('statut')->toString() ?: null,
            'ceinture_id' => $request->integer('ceinture_id') ?: null,
            'age_group'   => $request->string('age_group')->toString() ?: null, // mineur|adulte
            'sort'        => $request->string('sort')->toString() ?: 'created_at',
            'dir'         => strtolower($request->string('dir')->toString() ?: 'desc'),
            'per_page'    => (int) ($request->integer('per_page') ?: 15),
        ];

        $allowSort = ['created_at','nom','prenom','date_inscription','date_derniere_presence'];
        if (! in_array($filters['sort'], $allowSort, true)) {
            $filters['sort'] = 'created_at';
        }
        if (! in_array($filters['dir'], ['asc','desc'], true)) {
            $filters['dir'] = 'desc';
        }
        $filters['per_page'] = max(5, min(100, $filters['per_page']));

        $now = CarbonImmutable::now();

        /** @var QueryBuilder $query */
        $query = Membre::query()
            ->with([
                'user:id,email',
                'ceintureActuelle:id,name,color_hex',
            ])
            ->withCount([
                'cours as cours_count',
                'presences as presences_mois' => function ($q) use ($now) {
                    $q->whereMonth('date_cours', (int) $now->format('m'))
                      ->whereYear('date_cours', (int) $now->format('Y'));
                },
            ]);

        // Recherche textuelle
        if ($filters['q']) {
            $q = $filters['q'];
            $query->where(function ($w) use ($q) {
                $w->where('prenom', 'like', "%{$q}%")
                  ->orWhere('nom', 'like', "%{$q}%")
                  ->orWhere('telephone', 'like', "%{$q}%")
                  ->orWhereHas('user', fn($u) => $u->where('email', 'like', "%{$q}%"));
            });
        }

        // Statut
        if ($filters['statut']) {
            $query->where('statut', $filters['statut']);
        }

        // Ceinture
        if ($filters['ceinture_id']) {
            $query->where('ceinture_actuelle_id', $filters['ceinture_id']);
        }

        // Groupe d'âge
        if ($filters['age_group'] === 'mineur') {
            $query->whereDate('date_naissance', '>', CarbonImmutable::now()->subYears(18)->toDateString());
        } elseif ($filters['age_group'] === 'adulte') {
            $query->whereDate('date_naissance', '<=', CarbonImmutable::now()->subYears(18)->toDateString());
        }

        $membres = $query
            ->orderBy($filters['sort'], $filters['dir'])
            ->paginate($filters['per_page'])
            ->withQueryString()
            ->through(function (Membre $m) {
                return [
                    'id'                 => $m->id,
                    'nom_complet'        => $m->nom_complet,
                    'prenom'             => $m->prenom,
                    'nom'                => $m->nom,
                    'age'                => $m->age,
                    'is_minor'           => $m->age < 18,
                    'telephone'          => $m->telephone,
                    'user'               => $m->relationLoaded('user') && $m->user ? Arr::only($m->user->toArray(), ['email']) : null,
                    'ceinture_actuelle'  => $m->relationLoaded('ceintureActuelle') && $m->ceintureActuelle
                        ? ['id' => $m->ceintureActuelle->id, 'nom' => $m->ceintureActuelle->name, 'couleur_hex' => $m->ceintureActuelle->color_hex]
                        : null,
                    'statut'             => $m->statut,
                    'cours_count'        => (int) $m->getAttribute('cours_count'),
                    'presences_mois'     => (int) $m->getAttribute('presences_mois'),
                ];
            });

        // Stats tuiles (optimisées, une seule requête)
        $statsRaw = Membre::query()
            ->selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN statut = ? THEN 1 ELSE 0 END) as actifs,
                SUM(CASE WHEN MONTH(date_inscription) = ? AND YEAR(date_inscription) = ? THEN 1 ELSE 0 END) as nouveaux_mois
            ', [
                'actif',
                (int) $now->format('m'),
                (int) $now->format('Y'),
            ])
            ->first();
        
        $stats = [
            'total' => (int) $statsRaw->total,
            'actifs' => (int) $statsRaw->actifs,
            'nouveaux_mois' => (int) $statsRaw->nouveaux_mois,
        ];
        $stats['presences_jour'] = (int) DB::table('presences')->whereDate('date_cours', $now->toDateString())->count();

        $ceintures = Ceinture::query()->select('id','name','name_en','color_hex','order')->orderBy('order')->get()->map(function($c) {
            return [
                'id' => $c->id,
                'nom' => $c->name,
                'couleur_hex' => $c->color_hex,
                'order' => $c->order,
            ];
        });

        $can = [
            'create' => request()->user()?->can('create', Membre::class) ?? false,
            'update' => request()->user()?->can('viewAny', Membre::class) ?? false, // Général - détaillé par membre
            'delete' => request()->user()?->can('viewAny', Membre::class) ?? false, // Général - détaillé par membre
            'export' => request()->user()?->can('viewAny', Membre::class) ?? false,
        ];

        return Inertia::render('Membres/Index', [
            'membres'  => $membres,
            'filters'  => Arr::only($filters, ['q','statut','ceinture_id','age_group','sort','dir','per_page']),
            'ceintures'=> $ceintures,
            'stats'    => $stats,
            'can'      => $can,
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', Membre::class);

        return Inertia::render('Membres/Create', [
            'ceintures' => Ceinture::query()->select('id','name','name_en','color_hex','order')->orderBy('order')->get()->map(function($c) {
                return [
                    'id' => $c->id,
                    'nom' => $c->name,
                    'couleur_hex' => $c->color_hex,
                    'order' => $c->order,
                ];
            }),
        ]);
    }

    public function store(StoreMembreRequest $request): RedirectResponse
    {
        $data = $request->validated();

        DB::transaction(function () use ($data) {
            // Crée ou relie un User (email unique par école)
            $user = null;
            if (!empty($data['email'])) {
                $user = User::create([
                    'name'      => trim(($data['prenom'] ?? '').' '.($data['nom'] ?? '')),
                    'email'     => $data['email'],
                    'password'  => Hash::make($data['password'] ?? str()->random(16)),
                    'ecole_id'  => auth()->user()?->ecole_id,
                ]);
                $user->assignRole('membre');
            }

            /** @var Membre $m */
            $m = Membre::create([
                'user_id'               => $user?->id,
                'ecole_id'              => auth()->user()->ecole_id ?? 1, // Assurer ecole_id toujours présent
                'prenom'                => $data['prenom'],
                'nom'                   => $data['nom'],
                'email'                 => $data['email'] ?? null,
                'date_naissance'        => $data['date_naissance'],
                'sexe'                  => $data['sexe'] ?? 'Autre',
                'telephone'             => $data['telephone'] ?? null,
                'adresse'               => $data['adresse'] ?? null,
                'ville'                 => $data['ville'] ?? null,
                'code_postal'           => $data['code_postal'] ?? null,
                'province'              => $data['province'] ?? 'QC',
                'contact_urgence_nom'   => $data['contact_urgence_nom'] ?? null,
                'contact_urgence_telephone' => $data['contact_urgence_telephone'] ?? null,
                'contact_urgence_relation' => $data['contact_urgence_relation'] ?? null,
                'statut'                => $data['statut'] ?? 'actif',
                'ceinture_actuelle_id'  => $data['ceinture_actuelle_id'] ?? null,
                'date_inscription'      => $data['date_inscription'] ?? now()->toDateString(),
                'consentement_photos'   => $data['consentement_photos'] ?? false,
                'consentement_communications' => $data['consentement_communications'] ?? true,
                'date_consentement'     => isset($data['consentement_photos']) || isset($data['consentement_communications']) ? now() : null,
            ]);

            if (function_exists('activity')) {
                activity('membres')->performedOn($m)->causedBy(auth()->user())
                    ->withProperties(['payload' => Arr::except($data, ['password'])])
                    ->log('membre.created');
            }
        });

        return redirect()->route('membres.index')->with('success', 'Membre créé avec succès.');
    }

    public function show(Membre $membre): Response
    {
        $membre->load([
            'user:id,email',
            'ceintureActuelle:id,name,color_hex,order',
            'cours:id,nom', // si relation many-to-many existe
        ]);
        
        // Récupérer toutes les ceintures pour le modal
        $ceintures = Ceinture::orderBy('order')->get(['id', 'name', 'color_hex', 'order'])->map(function($c) {
            return [
                'id' => $c->id,
                'nom' => $c->name,
                'couleur_hex' => $c->color_hex,
                'order' => $c->order,
            ];
        });
        
        // Historique progressions
        $historiqueProgressions = $this->progressionService->getHistoriqueProgression($membre);
        
        // Validation progression suivante
        $prochaineCeinture = $membre->ceintureActuelle?->suivante();
        $validationProgression = null;
        if ($prochaineCeinture) {
            $validationProgression = $this->progressionService->peutProgresser($membre, $prochaineCeinture);
            $validationProgression['prochaine_ceinture'] = [
                'id' => $prochaineCeinture->id,
                'nom' => $prochaineCeinture->nom,
                'couleur_hex' => $prochaineCeinture->couleur_hex,
            ];
        }

        return Inertia::render('Membres/Show', [
            'membre' => [
                'id'                => $membre->id,
                'nom_complet'       => $membre->nom_complet,
                'prenom'            => $membre->prenom,
                'nom'               => $membre->nom,
                'date_naissance'    => $membre->date_naissance?->toDateString(),
                'age'               => $membre->age,
                'telephone'         => $membre->telephone,
                'adresse'           => $membre->adresse,
                'statut'            => $membre->statut,
                'ceinture_actuelle' => $membre->ceintureActuelle ? [
                    'id' => $membre->ceintureActuelle->id, 
                    'nom' => $membre->ceintureActuelle->name, 
                    'couleur_hex' => $membre->ceintureActuelle->color_hex,
                    'order' => $membre->ceintureActuelle->order,
                ] : null,
                'user'              => $membre->user?->only(['email']),
                'cours'             => $membre->cours?->map->only(['id','nom']),
                'date_inscription'  => $membre->date_inscription?->toDateString(),
                'date_derniere_presence' => $membre->date_derniere_presence?->toDateString(),
                'sexe'              => $membre->sexe,
                'ville'             => $membre->ville,
                'contact_urgence_nom' => $membre->contact_urgence_nom,
                'contact_urgence_telephone' => $membre->contact_urgence_telephone,
                'contact_urgence_relation' => $membre->contact_urgence_relation,
                'consentement_photos' => $membre->consentement_photos,
                'consentement_communications' => $membre->consentement_communications,
            ],
            'ceintures' => $ceintures, // Pour le modal de changement
            'historiqueProgressions' => $historiqueProgressions->map(function ($progression) {
                return [
                    'id' => $progression->id,
                    'date_obtention' => $progression->date_obtention->toDateString(),
                    'ceinture_precedente' => $progression->ceinturePrecedente ? [
                        'nom' => $progression->ceinturePrecedente->nom,
                        'couleur_hex' => $progression->ceinturePrecedente->couleur_hex,
                    ] : null,
                    'ceinture_nouvelle' => [
                        'nom' => $progression->ceintureNouvelle->nom,
                        'couleur_hex' => $progression->ceintureNouvelle->couleur_hex,
                    ],
                    'instructeur' => $progression->instructeur ? $progression->instructeur->name : null,
                    'notes' => $progression->notes,
                    'type_progression' => $progression->type_progression,
                ];
            }),
            'validationProgression' => $validationProgression,
        ]);
    }

    public function edit(Membre $membre): Response
    {
        // Charger les relations nécessaires
        $membre->load(['user.roles', 'ceintureActuelle']);
        
        $ceintures = Ceinture::query()->select('id','name','name_en','color_hex','order')->orderBy('order')->get()->map(function($c) {
            return [
                'id' => $c->id,
                'nom' => $c->name,
                'couleur_hex' => $c->color_hex,
                'order' => $c->order,
            ];
        });

        return Inertia::render('Membres/Edit', [
            'membre'    => [
                'id'                => $membre->id,
                'nom_complet'       => $membre->nom_complet,
                'prenom'            => $membre->prenom,
                'nom'               => $membre->nom,
                'email'             => $membre->email,
                'date_naissance'    => $membre->date_naissance?->toDateString(),
                'sexe'              => $membre->sexe,
                'telephone'         => $membre->telephone,
                'adresse'           => $membre->adresse,
                'ville'             => $membre->ville,
                'code_postal'       => $membre->code_postal,
                'contact_urgence_nom' => $membre->contact_urgence_nom,
                'contact_urgence_telephone' => $membre->contact_urgence_telephone,
                'contact_urgence_relation' => $membre->contact_urgence_relation,
                'statut'            => $membre->statut,
                'ceinture_actuelle_id' => $membre->ceinture_actuelle_id,
                'notes_medicales'   => $membre->notes_medicales,
                'allergies'         => json_decode($membre->allergies ?? '[]', true) ?: [],
                'notes_instructeur' => $membre->notes_instructeur,
                'notes_admin'       => $membre->notes_admin,
                'consentement_photos' => $membre->consentement_photos,
                'consentement_communications' => $membre->consentement_communications,
                'date_inscription'  => $membre->date_inscription?->toDateString(),
                'date_derniere_presence' => $membre->date_derniere_presence?->toDateString(),
                
                // AJOUT: Données utilisateur pour la gestion des rôles
                'user' => $membre->user ? [
                    'id' => $membre->user->id,
                    'email' => $membre->user->email,
                    'active' => $membre->user->active ?? true,
                    'email_verified_at' => $membre->user->email_verified_at,
                    'created_at' => $membre->user->created_at,
                    'last_login_at' => $membre->user->last_login_at,
                    'roles' => $membre->user->roles->map(fn($role) => [
                        'id' => $role->id,
                        'name' => $role->name,
                    ]),
                ] : null,
            ],
            'ceintures' => $ceintures,
        ]);
    }

    public function update(UpdateMembreRequest $request, Membre $membre): RedirectResponse
    {
        $data = $request->validated();

        DB::transaction(function () use (&$membre, $data) {
            $membre->update(Arr::only($data, [
                'prenom',
                'nom', 
                'date_naissance',
                'sexe',
                'telephone',
                'adresse',
                'ville',
                'code_postal',
                'contact_urgence_nom',
                'contact_urgence_telephone', 
                'contact_urgence_relation',
                'statut',
                'ceinture_actuelle_id',
                'notes_medicales',
                'allergies',
                'notes_instructeur',
                'notes_admin',
                'consentement_photos',
                'consentement_communications'
            ]));

            // Gestion compte utilisateur + rôles
            $this->handleSystemAccess($membre, $data);

            if (function_exists('activity')) {
                activity('membres')->performedOn($membre)->causedBy(auth()->user())
                    ->withProperties(['payload' => Arr::except($data, ['password','user_password'])])
                    ->log('membre.updated');
            }
        });

        return redirect()->route('membres.show', $membre->id)->with('success', 'Membre mis à jour.');
    }

    public function destroy(Membre $membre): RedirectResponse
    {
        DB::transaction(function () use ($membre) {
            // Optionnel : empêcher suppression si lié à l'utilisateur courant
            if ($membre->user_id && $membre->user_id === auth()->id()) {
                throw ValidationException::withMessages([
                    'membre' => 'Vous ne pouvez pas supprimer votre propre compte.',
                ]);
            }

            $membre->delete();

            if (function_exists('activity')) {
                activity('membres')->performedOn($membre)->causedBy(auth()->user())->log('membre.deleted');
            }
        });

        return redirect()->route('membres.index')->with('success', 'Membre supprimé.');
    }

    public function export(Request $request)
    {
        $this->authorize('viewAny', Membre::class);

        $filters = $request->only(['q','statut','ceinture_id','age_group','sort','dir']);
        $filename = 'membres_'.now()->timezone(config('app.timezone', 'UTC'))->format('Ymd_His').'.xlsx';

        return Excel::download(new MembersExport($filters), $filename);
    }

    public function bulk(BulkMembreActionRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $ids  = array_unique($data['ids']);

        DB::transaction(function () use ($data, $ids) {
            $action = $data['action'];

            if ($action === 'statut') {
                Membre::whereIn('id', $ids)->update(['statut' => $data['value']]);
            }

            if ($action === 'assign_ceinture') {
                Membre::whereIn('id', $ids)->update(['ceinture_actuelle_id' => $data['ceinture_id']]);
            }

            if (function_exists('activity')) {
                activity('membres')->causedBy(auth()->user())
                    ->withProperties($data)
                    ->log("membres.bulk.{$action}");
            }
        });

        return redirect()->back()->with('success', 'Action de masse exécutée.');
    }

    /**
     * NOUVELLE MÉTHODE: Faire progresser un membre vers une nouvelle ceinture
     */
    public function progresserCeinture(Request $request, Membre $membre): RedirectResponse
    {
        $this->authorize('update', $membre);

        $validated = $request->validate([
            'ceinture_id' => ['required','integer','exists:ceintures,id'],
            'notes'       => ['nullable','string','max:2000'],
            'forcer'      => ['boolean'], // Pour outrepasser les validations automatiques
        ]);

        $nouvelleCeinture = Ceinture::findOrFail($validated['ceinture_id']);

        // Vérifier si la progression est valide (sauf si forcée)
        if (!$validated['forcer']) {
            $validation = $this->progressionService->peutProgresser($membre, $nouvelleCeinture);
            if (!$validation['peut_progresser']) {
                return back()->withErrors([
                    'progression' => 'Progression bloquée: ' . implode(', ', $validation['raisons_blocage'])
                ]);
            }
        }

        try {
            $progression = $this->progressionService->progresserMembre(
                $membre, 
                $nouvelleCeinture, 
                $validated['notes'] ?? null,
                $validated['forcer'] ? 'attribution_forcee' : 'attribution_manuelle'
            );

            return redirect()->back()->with('success', 
                "Progression réussie: {$membre->nom_complet} → {$nouvelleCeinture->nom}"
            );

        } catch (\Exception $e) {
            return back()->withErrors([
                'progression' => 'Erreur lors de la progression: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * ANCIENNE MÉTHODE: Compatibilité pour changement simple de ceinture
     */
    public function changerCeinture(Request $request, Membre $membre): RedirectResponse
    {
        // Rediriger vers la nouvelle méthode de progression
        return $this->progresserCeinture($request, $membre);
    }

    /**
     * Gestion unifiée de l'accès système et des rôles
     */
    private function handleSystemAccess(Membre $membre, array $data): void
    {
        $hasSystemAccess = $data['has_system_access'] ?? false;
        
        if (!$hasSystemAccess) {
            // Supprimer l'accès système si désactivé
            if ($membre->user) {
                $membre->user->delete();
                $membre->update(['user_id' => null]);
            }
            return;
        }

        // Créer ou mettre à jour le compte utilisateur
        $userEmail = $data['user_email'] ?? $membre->email;
        $userName = trim("{$membre->prenom} {$membre->nom}");
        
        if ($membre->user) {
            // Mettre à jour utilisateur existant
            $updateData = [
                'name' => $userName,
                'email' => $userEmail,
                'active' => $data['user_active'] ?? true,
            ];
            
            if (!empty($data['user_password'])) {
                $updateData['password'] = Hash::make($data['user_password']);
            }
            
            if (isset($data['user_email_verified'])) {
                $updateData['email_verified_at'] = $data['user_email_verified'] ? now() : null;
            }
            
            $membre->user->update($updateData);
            $user = $membre->user;
        } else {
            // Créer nouveau utilisateur
            $user = User::create([
                'name' => $userName,
                'email' => $userEmail,
                'password' => Hash::make($data['user_password'] ?? str()->random(16)),
                'ecole_id' => auth()->user()?->ecole_id,
                'active' => $data['user_active'] ?? true,
                'email_verified_at' => ($data['user_email_verified'] ?? false) ? now() : null,
            ]);
            
            $membre->update(['user_id' => $user->id]);
        }

        // Gérer les rôles
        $roles = $data['user_roles'] ?? ['membre'];
        
        // Assurer qu'au minimum le rôle 'membre' est assigné
        if (empty($roles) || !in_array('membre', $roles)) {
            $roles[] = 'membre';
        }
        
        // Empêcher l'assignation de superadmin par des non-superadmin
        $authUser = auth()->user();
        if (!$authUser->hasRole('superadmin')) {
            $roles = array_filter($roles, fn($role) => $role !== 'superadmin');
        }
        
        $user->syncRoles($roles);
    }
}
