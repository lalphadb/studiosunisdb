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
    public function __construct()
    {
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
                    'is_minor'           => $m->is_minor,
                    'telephone'          => $m->telephone,
                    'user'               => $m->relationLoaded('user') ? Arr::only($m->user->toArray(), ['email']) : null,
                    'ceinture_actuelle'  => $m->relationLoaded('ceintureActuelle')
                        ? ['id' => $m->ceintureActuelle->id, 'nom' => $m->ceintureActuelle->name, 'couleur_hex' => $m->ceintureActuelle->color_hex]
                        : null,
                    'statut'             => $m->statut,
                    'cours_count'        => (int) $m->getAttribute('cours_count'),
                    'presences_mois'     => (int) $m->getAttribute('presences_mois'),
                ];
            });

        // Stats tuiles (optimisées, une seule requête)
        $stats = Membre::query()
            ->selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN statut = ? THEN 1 ELSE 0 END) as actifs,
                SUM(CASE WHEN MONTH(date_inscription) = ? AND YEAR(date_inscription) = ? THEN 1 ELSE 0 END) as nouveaux_mois
            ', [
                'actif',
                (int) $now->format('m'),
                (int) $now->format('Y'),
            ])
            ->first()
            ->toArray();
        $stats['presences_jour'] = (int) DB::table('presences')->whereDate('date_cours', $now->toDateString())->count();

        $ceintures = Ceinture::query()->select('id','name as nom','color_hex as couleur_hex')->orderBy('order')->get();

        $can = [
            'create' => request()->user()?->can('membres.create') ?? false,
            'update' => request()->user()?->can('membres.edit') ?? false,
            'delete' => request()->user()?->can('membres.delete') ?? false,
            'export' => request()->user()?->can('membres.export') ?? false,
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
            'ceintures' => Ceinture::select('id','name','name as name_fr','color_hex as couleur_hex')->orderBy('order')->get(),
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
            'ceintureActuelle:id,name,color_hex',
            'cours:id,nom', // si relation many-to-many existe
        ]);
        
        // Récupérer toutes les ceintures pour le modal
        $ceintures = Ceinture::orderBy('order')->get(['id', 'name', 'color_hex']);

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
                'ceinture_actuelle' => $membre->ceintureActuelle ? ['id' => $membre->ceintureActuelle->id, 'nom' => $membre->ceintureActuelle->name, 'couleur_hex' => $membre->ceintureActuelle->color_hex] : null,
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
        ]);
    }

    public function edit(Membre $membre): Response
    {
        $ceintures = Ceinture::select('id','name as nom','color_hex as couleur_hex')->orderBy('order')->get();

        return Inertia::render('Membres/Edit', [
            'membre'    => [
                'id'                => $membre->id,
                'nom_complet'       => $membre->nom_complet,
                'prenom'            => $membre->prenom,
                'nom'               => $membre->nom,
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
            ],
            'ceintures' => $ceintures,
        ]);
    }

    public function update(UpdateMembreRequest $request, Membre $membre): RedirectResponse
    {
        $data = $request->validated();

        DB::transaction(function () use (&$membre, $data) {
            $membre->update(Arr::only($data, [
                'prenom','nom','date_naissance','telephone','adresse','statut','ceinture_actuelle_id',
            ]));

            // Si email envoyé, MAJ du user lié ou création
            if (!empty($data['email'])) {
                if ($membre->user) {
                    $membre->user->update(['email' => $data['email']]);
                    if (!empty($data['password'])) {
                        $membre->user->update(['password' => Hash::make($data['password'])]);
                    }
                } else {
                    $user = User::create([
                        'name'      => trim(($data['prenom'] ?? '').' '.($data['nom'] ?? '')),
                        'email'     => $data['email'],
                        'password'  => Hash::make($data['password'] ?? str()->random(16)),
                        'ecole_id'  => auth()->user()?->ecole_id,
                    ]);
                    $user->assignRole('membre');
                    $membre->update(['user_id' => $user->id]);
                }
            }

            if (function_exists('activity')) {
                activity('membres')->performedOn($membre)->causedBy(auth()->user())
                    ->withProperties(['payload' => Arr::except($data, ['password'])])
                    ->log('membre.updated');
            }
        });

        return redirect()->route('membres.show', $membre->id)->with('success', 'Membre mis à jour.');
    }

    public function destroy(Membre $membre): RedirectResponse
    {
        DB::transaction(function () use ($membre) {
            // Optionnel : empêcher suppression si lié à l’utilisateur courant
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

    public function changerCeinture(Request $request, Membre $membre): RedirectResponse
    {
        $this->authorize('update', $membre);

        $validated = $request->validate([
            'ceinture_id' => ['required','integer','exists:ceintures,id'],
            'note'        => ['nullable','string','max:2000'],
        ]);

        DB::transaction(function () use ($membre, $validated) {
            $membre->update(['ceinture_actuelle_id' => $validated['ceinture_id']]);

            if (function_exists('activity')) {
                activity('membres')->performedOn($membre)->causedBy(auth()->user())
                    ->withProperties(['ceinture_id' => $validated['ceinture_id'], 'note' => $validated['note'] ?? null])
                    ->log('membre.changer_ceinture');
            }
        });

        return redirect()->back()->with('success', 'Ceinture mise à jour.');
    }
}
