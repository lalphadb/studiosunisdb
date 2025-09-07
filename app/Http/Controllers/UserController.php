<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exports\MembersExport;
use App\Http\Requests\Membres\BulkMembreActionRequest;
use App\Http\Requests\Membres\StoreMembreRequest;
use App\Http\Requests\Membres\UpdateMembreRequest;
use App\Models\Ceinture;
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
use Spatie\Permission\Models\Role;

final class UserController extends Controller
{
    public function __construct(
        private ProgressionCeintureService $progressionService
    ) {
        // Policy UserPolicy recommandée
        $this->authorizeResource(User::class, 'user');
    }

    public function index(Request $request): Response
    {
        $filters = [
            'q'           => trim((string) $request->string('q')),
            'statut'      => $request->string('statut')->toString() ?: null,
            'ceinture_id' => $request->integer('ceinture_id') ?: null,
            'age_group'   => $request->string('age_group')->toString() ?: null, // mineur|adulte
            'type'        => $request->string('type')->toString() ?: 'all', // all|membres|admins
            'sort'        => $request->string('sort')->toString() ?: 'created_at',
            'dir'         => strtolower($request->string('dir')->toString() ?: 'desc'),
            'per_page'    => (int) ($request->integer('per_page') ?: 15),
        ];

        $allowSort = ['created_at','nom','prenom','name','date_inscription','date_derniere_presence','last_login_at'];
        if (! in_array($filters['sort'], $allowSort, true)) {
            $filters['sort'] = 'created_at';
        }
        if (! in_array($filters['dir'], ['asc','desc'], true)) {
            $filters['dir'] = 'desc';
        }
        $filters['per_page'] = max(5, min(100, $filters['per_page']));

        $now = CarbonImmutable::now();

        /** @var QueryBuilder $query */
        $query = User::query()
            ->with([
                'roles:id,name',
                'ceintureActuelle:id,name,color_hex',
            ])
            ->withCount([
                'cours as cours_count',
                'presences as presences_mois' => function ($q) use ($now) {
                    $q->whereMonth('date_cours', (int) $now->format('m'))
                      ->whereYear('date_cours', (int) $now->format('Y'));
                },
            ]);

        // Filtrer par type
        if ($filters['type'] === 'membres') {
            $query->membresKarate(); // Scope : WHERE prenom IS NOT NULL
        } elseif ($filters['type'] === 'admins') {
            $query->adminsOnly(); // Scope : WHERE prenom IS NULL
        }

        // Recherche textuelle
        if ($filters['q']) {
            $q = $filters['q'];
            $query->where(function ($w) use ($q) {
                $w->where('prenom', 'like', "%{$q}%")
                  ->orWhere('nom', 'like', "%{$q}%")
                  ->orWhere('name', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%")
                  ->orWhere('telephone', 'like', "%{$q}%");
            });
        }

        // Statut (pour membres karaté)
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

        $users = $query
            ->orderBy($filters['sort'], $filters['dir'])
            ->paginate($filters['per_page'])
            ->withQueryString()
            ->through(function (User $u) {
                return [
                    'id'                 => $u->id,
                    'nom_complet'        => $u->nom_complet,
                    'prenom'             => $u->prenom,
                    'nom'                => $u->nom,
                    'name'               => $u->name,
                    'email'              => $u->email,
                    'age'                => $u->age,
                    'is_minor'           => $u->age ? $u->age < 18 : false,
                    'telephone'          => $u->telephone,
                    'is_membre_karate'   => $u->isMembreKarate(),
                    'is_admin_only'      => $u->isAdminOnly(),
                    'active'             => $u->active,
                    'last_login_at'      => $u->last_login_at?->toDateString(),
                    'ceinture_actuelle'  => $u->relationLoaded('ceintureActuelle') && $u->ceintureActuelle
                        ? ['id' => $u->ceintureActuelle->id, 'nom' => $u->ceintureActuelle->name, 'couleur_hex' => $u->ceintureActuelle->color_hex]
                        : null,
                    'statut'             => $u->statut,
                    'cours_count'        => (int) $u->getAttribute('cours_count'),
                    'presences_mois'     => (int) $u->getAttribute('presences_mois'),
                    'roles'              => $u->relationLoaded('roles') ? $u->roles->pluck('name') : [],
                ];
            });

        // Stats tuiles (optimisées)
        $statsRaw = User::query()
            ->selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN statut = ? THEN 1 ELSE 0 END) as actifs_karate,
                SUM(CASE WHEN active = 1 THEN 1 ELSE 0 END) as actifs_systeme,
                SUM(CASE WHEN prenom IS NOT NULL AND nom IS NOT NULL THEN 1 ELSE 0 END) as membres_karate,
                SUM(CASE WHEN MONTH(date_inscription) = ? AND YEAR(date_inscription) = ? THEN 1 ELSE 0 END) as nouveaux_mois
            ', [
                'actif',
                (int) $now->format('m'),
                (int) $now->format('Y'),
            ])
            ->first();
        
        $stats = [
            'total' => (int) $statsRaw->total,
            'actifs_karate' => (int) $statsRaw->actifs_karate,
            'actifs_systeme' => (int) $statsRaw->actifs_systeme,
            'membres_karate' => (int) $statsRaw->membres_karate,
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

        $roles = Role::query()
            ->when(!auth()->user()->hasRole('superadmin'), fn($q) => $q->where('name','!=','superadmin'))
            ->pluck('name');

        $can = [
            'create' => request()->user()?->can('create', User::class) ?? false,
            'update' => request()->user()?->can('viewAny', User::class) ?? false,
            'delete' => request()->user()?->can('viewAny', User::class) ?? false,
            'export' => request()->user()?->can('viewAny', User::class) ?? false,
            'manageRoles' => request()->user()?->can('manageRoles', request()->user()) ?? false,
        ];

        return Inertia::render('Users/Index', [
            'users'     => $users,
            'filters'   => Arr::only($filters, ['q','statut','ceinture_id','age_group','type','sort','dir','per_page']),
            'ceintures' => $ceintures,
            'roles'     => $roles,
            'stats'     => $stats,
            'can'       => $can,
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', User::class);

        $ceintures = Ceinture::query()->select('id','name','name_en','color_hex','order')->orderBy('order')->get()->map(function($c) {
            return [
                'id' => $c->id,
                'nom' => $c->name,
                'couleur_hex' => $c->color_hex,
                'order' => $c->order,
            ];
        });

        $roles = Role::query()
            ->when(!auth()->user()->hasRole('superadmin'), fn($q) => $q->where('name','!=','superadmin'))
            ->pluck('name');

        return Inertia::render('Users/Create', [
            'ceintures' => $ceintures,
            'roles' => $roles,
        ]);
    }

    public function store(StoreMembreRequest $request): RedirectResponse
    {
        $data = $request->validated();

        DB::transaction(function () use ($data) {
            /** @var User $user */
            $user = User::create([
                'name'                  => trim(($data['prenom'] ?? '').' '.($data['nom'] ?? '')),
                'email'                 => $data['email'],
                'password'              => Hash::make($data['password'] ?? str()->random(16)),
                'ecole_id'              => auth()->user()->ecole_id ?? 1,
                'active'                => $data['active'] ?? true,
                'email_verified_at'     => ($data['email_verified'] ?? false) ? now() : null,
                
                // Données karaté (si c'est un membre)
                'prenom'                => $data['prenom'] ?? null,
                'nom'                   => $data['nom'] ?? null,
                'date_naissance'        => $data['date_naissance'] ?? null,
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

            // Assigner rôles
            $roles = $data['roles'] ?? ['membre'];
            if (empty($roles) || !in_array('membre', $roles)) {
                $roles[] = 'membre';
            }
            
            // Empêcher superadmin pour non-superadmin
            $authUser = auth()->user();
            if (!$authUser->hasRole('superadmin')) {
                $roles = array_filter($roles, fn($role) => $role !== 'superadmin');
            }
            
            $user->syncRoles($roles);

            if (function_exists('activity')) {
                activity('users')->performedOn($user)->causedBy(auth()->user())
                    ->withProperties(['payload' => Arr::except($data, ['password'])])
                    ->log('user.created');
            }
        });

        return redirect()->route('users.index')->with('success', 'Utilisateur créé avec succès.');
    }

    public function show(User $user): Response
    {
        $user->load([
            'roles:id,name',
            'ceintureActuelle:id,name,color_hex,order',
            'cours:id,nom',
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
        
        // Historique progressions (si membre karaté)
        $historiqueProgressions = collect([]);
        $validationProgression = null;
        
        if ($user->isMembreKarate()) {
            $historiqueProgressions = $this->progressionService->getHistoriqueProgression($user);
            
            // Validation progression suivante
            $prochaineCeinture = $user->ceintureActuelle?->suivante();
            if ($prochaineCeinture) {
                $validationProgression = $this->progressionService->peutProgresser($user, $prochaineCeinture);
                $validationProgression['prochaine_ceinture'] = [
                    'id' => $prochaineCeinture->id,
                    'nom' => $prochaineCeinture->nom,
                    'couleur_hex' => $prochaineCeinture->couleur_hex,
                ];
            }
        }

        return Inertia::render('Users/Show', [
            'user' => [
                'id'                => $user->id,
                'nom_complet'       => $user->nom_complet,
                'name'              => $user->name,
                'email'             => $user->email,
                'prenom'            => $user->prenom,
                'nom'               => $user->nom,
                'date_naissance'    => $user->date_naissance?->toDateString(),
                'age'               => $user->age,
                'telephone'         => $user->telephone,
                'adresse'           => $user->adresse,
                'statut'            => $user->statut,
                'active'            => $user->active,
                'is_membre_karate'  => $user->isMembreKarate(),
                'is_admin_only'     => $user->isAdminOnly(),
                'last_login_at'     => $user->last_login_at?->toDateString(),
                'email_verified_at' => $user->email_verified_at?->toDateString(),
                'ceinture_actuelle' => $user->ceintureActuelle ? [
                    'id' => $user->ceintureActuelle->id, 
                    'nom' => $user->ceintureActuelle->name, 
                    'couleur_hex' => $user->ceintureActuelle->color_hex,
                    'order' => $user->ceintureActuelle->order,
                ] : null,
                'cours'             => $user->cours?->map->only(['id','nom']),
                'roles'             => $user->roles->map(fn($role) => [
                    'id' => $role->id,
                    'name' => $role->name,
                ]),
                'date_inscription'  => $user->date_inscription?->toDateString(),
                'date_derniere_presence' => $user->date_derniere_presence?->toDateString(),
                'sexe'              => $user->sexe,
                'ville'             => $user->ville,
                'contact_urgence_nom' => $user->contact_urgence_nom,
                'contact_urgence_telephone' => $user->contact_urgence_telephone,
                'contact_urgence_relation' => $user->contact_urgence_relation,
                'consentement_photos' => $user->consentement_photos,
                'consentement_communications' => $user->consentement_communications,
            ],
            'ceintures' => $ceintures,
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

    public function edit(User $user): Response
    {
        $user->load(['roles', 'ceintureActuelle']);
        
        $ceintures = Ceinture::query()->select('id','name','name_en','color_hex','order')->orderBy('order')->get()->map(function($c) {
            return [
                'id' => $c->id,
                'nom' => $c->name,
                'couleur_hex' => $c->color_hex,
                'order' => $c->order,
            ];
        });

        $roles = Role::query()
            ->when(!auth()->user()->hasRole('superadmin'), fn($q) => $q->where('name','!=','superadmin'))
            ->pluck('name');

        return Inertia::render('Users/Edit', [
            'user' => [
                'id'                => $user->id,
                'nom_complet'       => $user->nom_complet,
                'name'              => $user->name,
                'email'             => $user->email,
                'prenom'            => $user->prenom,
                'nom'               => $user->nom,
                'date_naissance'    => $user->date_naissance?->toDateString(),
                'sexe'              => $user->sexe,
                'telephone'         => $user->telephone,
                'adresse'           => $user->adresse,
                'ville'             => $user->ville,
                'code_postal'       => $user->code_postal,
                'contact_urgence_nom' => $user->contact_urgence_nom,
                'contact_urgence_telephone' => $user->contact_urgence_telephone,
                'contact_urgence_relation' => $user->contact_urgence_relation,
                'statut'            => $user->statut,
                'ceinture_actuelle_id' => $user->ceinture_actuelle_id,
                'notes_medicales'   => $user->notes_medicales,
                'allergies'         => json_decode($user->allergies ?? '[]', true) ?: [],
                'consentement_photos' => $user->consentement_photos,
                'consentement_communications' => $user->consentement_communications,
                'date_inscription'  => $user->date_inscription?->toDateString(),
                'date_derniere_presence' => $user->date_derniere_presence?->toDateString(),
                'active'            => $user->active,
                'email_verified_at' => $user->email_verified_at,
                'last_login_at'     => $user->last_login_at?->toDateString(),
                'is_membre_karate'  => $user->isMembreKarate(),
                'is_admin_only'     => $user->isAdminOnly(),
                'roles' => $user->roles->map(fn($role) => [
                    'id' => $role->id,
                    'name' => $role->name,
                ]),
            ],
            'ceintures' => $ceintures,
            'roles' => $roles,
        ]);
    }

    public function update(UpdateMembreRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();

        DB::transaction(function () use (&$user, $data) {
            // Données de base utilisateur
            $userData = [
                'name' => trim(($data['prenom'] ?? $user->prenom ?? '') . ' ' . ($data['nom'] ?? $user->nom ?? '')),
                'email' => $data['email'] ?? $user->email,
                'active' => $data['active'] ?? $user->active,
            ];

            // Mot de passe si fourni
            if (!empty($data['password'])) {
                $userData['password'] = Hash::make($data['password']);
            }

            // Email vérifié
            if (isset($data['email_verified'])) {
                $userData['email_verified_at'] = $data['email_verified'] ? now() : null;
            }

            // Données karaté (si membre)
            $karateData = Arr::only($data, [
                'prenom', 'nom', 'date_naissance', 'sexe', 'telephone',
                'adresse', 'ville', 'code_postal', 'province',
                'contact_urgence_nom', 'contact_urgence_telephone', 'contact_urgence_relation',
                'statut', 'ceinture_actuelle_id', 'date_inscription', 'date_derniere_presence',
                'notes_medicales', 'allergies', 'consentement_photos', 'consentement_communications'
            ]);

            $user->update(array_merge($userData, $karateData));

            // Gestion des rôles
            if (isset($data['roles']) && auth()->user()->can('manageRoles', $user)) {
                $roles = collect($data['roles'])
                    ->reject(fn($r) => $r === 'superadmin' && !auth()->user()->hasRole('superadmin'))
                    ->all();
                $user->syncRoles($roles);
            }

            if (function_exists('activity')) {
                activity('users')->performedOn($user)->causedBy(auth()->user())
                    ->withProperties(['payload' => Arr::except($data, ['password'])])
                    ->log('user.updated');
            }
        });

        return redirect()->route('users.show', $user->id)->with('success', 'Utilisateur mis à jour.');
    }

    public function destroy(User $user): RedirectResponse
    {
        DB::transaction(function () use ($user) {
            // Empêcher suppression de son propre compte
            if ($user->id === auth()->id()) {
                throw ValidationException::withMessages([
                    'user' => 'Vous ne pouvez pas supprimer votre propre compte.',
                ]);
            }

            $user->delete();

            if (function_exists('activity')) {
                activity('users')->performedOn($user)->causedBy(auth()->user())->log('user.deleted');
            }
        });

        return redirect()->route('users.index')->with('success', 'Utilisateur supprimé.');
    }

    public function export(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $filters = $request->only(['q','statut','ceinture_id','age_group','type','sort','dir']);
        $filename = 'users_'.now()->timezone(config('app.timezone', 'UTC'))->format('Ymd_His').'.xlsx';

        return Excel::download(new MembersExport($filters), $filename);
    }

    public function bulk(BulkMembreActionRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $ids  = array_unique($data['ids']);

        DB::transaction(function () use ($data, $ids) {
            $action = $data['action'];

            if ($action === 'statut') {
                User::whereIn('id', $ids)->update(['statut' => $data['value']]);
            }

            if ($action === 'active') {
                User::whereIn('id', $ids)->update(['active' => $data['value']]);
            }

            if ($action === 'assign_ceinture') {
                User::whereIn('id', $ids)->update(['ceinture_actuelle_id' => $data['ceinture_id']]);
            }

            if ($action === 'assign_role') {
                $users = User::whereIn('id', $ids)->get();
                foreach ($users as $user) {
                    $user->assignRole($data['role']);
                }
            }

            if (function_exists('activity')) {
                activity('users')->causedBy(auth()->user())
                    ->withProperties($data)
                    ->log("users.bulk.{$action}");
            }
        });

        return redirect()->back()->with('success', 'Action de masse exécutée.');
    }

    /**
     * Faire progresser un utilisateur vers une nouvelle ceinture
     */
    public function progresserCeinture(Request $request, User $user): RedirectResponse
    {
        $this->authorize('update', $user);

        if (!$user->isMembreKarate()) {
            return back()->withErrors(['progression' => 'Seuls les membres karaté peuvent progresser en ceinture.']);
        }

        $validated = $request->validate([
            'ceinture_id' => ['required','integer','exists:ceintures,id'],
            'notes'       => ['nullable','string','max:2000'],
            'forcer'      => ['boolean'],
        ]);

        $nouvelleCeinture = Ceinture::findOrFail($validated['ceinture_id']);

        if (!$validated['forcer']) {
            $validation = $this->progressionService->peutProgresser($user, $nouvelleCeinture);
            if (!$validation['peut_progresser']) {
                return back()->withErrors([
                    'progression' => 'Progression bloquée: ' . implode(', ', $validation['raisons_blocage'])
                ]);
            }
        }

        try {
            $progression = $this->progressionService->progresserMembre(
                $user, 
                $nouvelleCeinture, 
                $validated['notes'] ?? null,
                $validated['forcer'] ? 'attribution_forcee' : 'attribution_manuelle'
            );

            return redirect()->back()->with('success', 
                "Progression réussie: {$user->nom_complet} → {$nouvelleCeinture->nom}"
            );

        } catch (\Exception $e) {
            return back()->withErrors([
                'progression' => 'Erreur lors de la progression: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Reset password
     */
    public function resetPassword(Request $request, User $user): RedirectResponse
    {
        $this->authorize('update', $user);
        
        $request->validate(['password' => ['required','string','min:8','confirmed']]);
        
        $user->update(['password' => Hash::make($request->password)]);
        
        return back()->with('success','Mot de passe réinitialisé avec succès.');
    }

    /**
     * Gestion des rôles
     */
    public function manageRoles(Request $request, User $user): RedirectResponse
    {
        $this->authorize('update', $user);
        
        $request->validate([
            'roles' => ['required','array'],
            'roles.*' => ['exists:roles,name'],
        ]);
        
        $rolesInput = collect($request->roles)
            ->reject(fn($r) => $r === 'superadmin' && !auth()->user()->hasRole('superadmin'))
            ->all();
            
        $user->syncRoles($rolesInput);
        
        return back()->with('success','Rôles mis à jour avec succès.');
    }
}