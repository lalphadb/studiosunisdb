<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSeminaireRequest;
use App\Http\Requests\Admin\UpdateSeminaireRequest;
use App\Http\Requests\Admin\BulkValidateSeminaireRequest;
use App\Models\Seminaire;
use App\Models\Ecole;
use App\Models\User;
use App\Models\InscriptionSeminaire;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SeminaireController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('can:viewAny,App\Models\Seminaire', only: ['index']),
            new Middleware('can:view,seminaire', only: ['show', 'inscriptions']),
            new Middleware('can:create,App\Models\Seminaire', only: ['create', 'store']),
            new Middleware('can:update,seminaire', only: ['edit', 'update']),
            new Middleware('can:delete,seminaire', only: ['destroy']),
            new Middleware('can:bulkUpdate,App\Models\Seminaire', only: ['bulkValidateInscriptions']),
        ];
    }

    public function index(Request $request): View
    {
        $query = Seminaire::with(['ecole', 'inscriptions']);

        // Filtrage multi-tenant STRICT
        if (auth()->user()->hasRole('admin_ecole')) {
            $query->where('ecole_id', auth()->user()->ecole_id);
        }

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('titre', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('instructeur', 'like', "%{$search}%")
                  ->orWhere('lieu', 'like', "%{$search}%");
            });
        }

        // Filtres
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $seminaires = $query->orderBy('date_debut', 'desc')->paginate(25);

        return view('admin.seminaires.index', compact('seminaires'));
    }

    public function create(): View
    {
        $ecoles = Ecole::query();
        
        if (auth()->user()->hasRole('admin_ecole')) {
            $ecoles->where('id', auth()->user()->ecole_id);
        }
        
        $ecoles = $ecoles->orderBy('nom')->get();

        return view('admin.seminaires.create', compact('ecoles'));
    }

    public function store(StoreSeminaireRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        
        // Auto-assignation ecole_id pour admin_ecole
        if (auth()->user()->hasRole('admin_ecole')) {
            $validated['ecole_id'] = auth()->user()->ecole_id;
        }

        // Assigner l'utilisateur qui traite
        $validated['processed_by_user_id'] = auth()->id();

        $seminaire = Seminaire::create($validated);

        return redirect()
            ->route('admin.seminaires.index')
            ->with('success', 'Séminaire créé avec succès.');
    }

    public function show(Seminaire $seminaire): View
    {
        $seminaire->load(['ecole', 'processedBy', 'inscriptions.user']);
        return view('admin.seminaires.show', compact('seminaire'));
    }

    public function edit(Seminaire $seminaire): View
    {
        $ecoles = Ecole::query();
        
        if (auth()->user()->hasRole('admin_ecole')) {
            $ecoles->where('id', auth()->user()->ecole_id);
        }
        
        $ecoles = $ecoles->orderBy('nom')->get();

        return view('admin.seminaires.edit', compact('seminaire', 'ecoles'));
    }

    public function update(UpdateSeminaireRequest $request, Seminaire $seminaire): RedirectResponse
    {
        $seminaire->update($request->validated());

        return redirect()
            ->route('admin.seminaires.index')
            ->with('success', 'Séminaire mis à jour avec succès.');
    }

    public function destroy(Seminaire $seminaire): RedirectResponse
    {
        $seminaire->delete();

        return redirect()
            ->route('admin.seminaires.index')
            ->with('success', 'Séminaire supprimé avec succès.');
    }

    /**
     * Afficher les inscriptions d'un séminaire avec gestion multi-tenant
     */
    public function inscriptions(Seminaire $seminaire): View
    {
        $query = InscriptionSeminaire::with(['user', 'ecole'])
            ->where('seminaire_id', $seminaire->id);

        // Filtrage multi-tenant pour les inscriptions
        if (auth()->user()->hasRole('admin_ecole')) {
            $query->whereHas('user', function ($q) {
                $q->where('ecole_id', auth()->user()->ecole_id);
            });
        }

        $inscriptions = $query->orderBy('created_at', 'desc')->paginate(50);

        return view('admin.seminaires.inscriptions', compact('seminaire', 'inscriptions'));
    }

    /**
     * VALIDATION DE MASSE pour les inscriptions au séminaire
     */
    public function bulkValidateInscriptions(BulkValidateSeminaireRequest $request, Seminaire $seminaire): RedirectResponse
    {
        $validated = $request->validated();
        $inscriptionIds = $validated['inscription_ids'];
        $action = $validated['action'];

        try {
            DB::beginTransaction();

            $query = InscriptionSeminaire::whereIn('id', $inscriptionIds)
                ->where('seminaire_id', $seminaire->id);
            
            // Filtrage multi-tenant STRICT pour les inscriptions
            if (auth()->user()->hasRole('admin_ecole')) {
                $query->whereHas('user', function ($q) {
                    $q->where('ecole_id', auth()->user()->ecole_id);
                });
            }

            $count = 0;

            switch ($action) {
                case 'marquer_present':
                    $count = $query->update([
                        'statut' => 'present',
                        'date_presence' => now(),
                        'updated_at' => now()
                    ]);
                    break;

                case 'marquer_absent':
                    $count = $query->update([
                        'statut' => 'absent',
                        'updated_at' => now()
                    ]);
                    break;

                case 'confirmer_inscription':
                    $count = $query->update([
                        'statut' => 'confirme',
                        'date_confirmation' => now(),
                        'updated_at' => now()
                    ]);
                    break;

                case 'annuler_inscription':
                    $count = $query->update([
                        'statut' => 'annule',
                        'updated_at' => now()
                    ]);
                    break;

                case 'attribuer_certificat':
                    // Seulement si le séminaire délivre des certificats ET que la personne était présente
                    if ($seminaire->certificat) {
                        $count = $query->where('statut', 'present')->update([
                            'certificat_obtenu' => true,
                            'date_certificat' => now(),
                            'updated_at' => now()
                        ]);
                    }
                    break;

                case 'supprimer':
                    $count = $query->delete();
                    break;
            }

            DB::commit();

            $actionText = match($action) {
                'marquer_present' => 'marquées comme présentes',
                'marquer_absent' => 'marquées comme absentes',
                'confirmer_inscription' => 'confirmées',
                'annuler_inscription' => 'annulées',
                'attribuer_certificat' => 'certifiées',
                'supprimer' => 'supprimées',
            };

            return redirect()
                ->route('admin.seminaires.inscriptions', $seminaire)
                ->with('success', "{$count} inscription(s) {$actionText} avec succès.");

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->route('admin.seminaires.inscriptions', $seminaire)
                ->with('error', 'Erreur lors de l\'action de masse : ' . $e->getMessage());
        }
    }
}
