<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\{Membre, Cours, User};
use Carbon\Carbon;
use Illuminate\Http\{Request, RedirectResponse};
use Illuminate\Support\Facades\{Auth, DB, Hash};
use Inertia\{Inertia, Response};

/**
 * MembreController Sécurisé - StudiosDB v5 Pro
 * Version adaptée aux tables existantes
 */
final class MembreController extends Controller
{
    /**
     * Liste des membres avec interface moderne
     */
    public function index(Request $request): Response
    {
        try {
            // Vérifier que la table membres existe
            if (!$this->tableExists('membres')) {
                return $this->renderEmptyMembres();
            }

            // Requête sécurisée
            $query = Membre::query();

            // Filtres sécurisés
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function ($q) use ($search) {
                    $q->where('prenom', 'like', "%{$search}%")
                      ->orWhere('nom', 'like', "%{$search}%");
                });
            }

            if ($request->filled('statut')) {
                $query->where('statut', $request->get('statut'));
            }

            // Pagination sécurisée
            $membres = $query->orderBy('created_at', 'desc')
                ->paginate(12)
                ->appends($request->query());

            // Stats sécurisées
            $stats = $this->getStatsSafe();

            return Inertia::render('Membres/Index', [
                'membres' => $membres,
                'stats' => $stats,
                'filters' => $request->only(['search', 'statut'])
            ]);

        } catch (\Exception $e) {
            \Log::error('MembreController Error: ' . $e->getMessage());
            return $this->renderEmptyMembres();
        }
    }

    /**
     * Statistiques sécurisées
     */
    private function getStatsSafe(): array
    {
        try {
            if (!$this->tableExists('membres')) {
                return $this->getStatsDefault();
            }

            $totalMembres = Membre::count();
            $membresActifs = Membre::where('statut', 'actif')->count();
            $nouveauxMois = Membre::whereMonth('created_at', now()->month)->count();

            return [
                'total_membres' => $totalMembres,
                'membres_actifs' => $membresActifs,
                'nouveaux_mois' => $nouveauxMois,
                'taux_activite' => $totalMembres > 0 ? round(($membresActifs / $totalMembres) * 100, 1) : 0
            ];

        } catch (\Exception $e) {
            return $this->getStatsDefault();
        }
    }

    /**
     * Stats par défaut
     */
    private function getStatsDefault(): array
    {
        return [
            'total_membres' => 0,
            'membres_actifs' => 0,
            'nouveaux_mois' => 0,
            'taux_activite' => 0
        ];
    }

    /**
     * Formulaire de création
     */
    public function create(): Response
    {
        return Inertia::render('Membres/Create');
    }

    /**
     * Enregistrement nouveau membre
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'prenom' => 'required|string|max:255',
                'nom' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8',
                'date_naissance' => 'required|date',
                'sexe' => 'required|in:M,F,Autre',
                'telephone' => 'nullable|string|max:20',
                'adresse' => 'nullable|string',
                'statut' => 'string|in:actif,inactif,suspendu'
            ]);

            DB::transaction(function () use ($validated) {
                // Créer utilisateur
                $user = User::create([
                    'name' => $validated['prenom'] . ' ' . $validated['nom'],
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password'])
                ]);

                // Assigner rôle si système de rôles existe
                if (method_exists($user, 'assignRole')) {
                    $user->assignRole('membre');
                }

                // Créer membre
                Membre::create([
                    'user_id' => $user->id,
                    'prenom' => $validated['prenom'],
                    'nom' => $validated['nom'],
                    'date_naissance' => $validated['date_naissance'],
                    'sexe' => $validated['sexe'],
                    'telephone' => $validated['telephone'] ?? null,
                    'adresse' => $validated['adresse'] ?? null,
                    'statut' => $validated['statut'] ?? 'actif',
                    'date_inscription' => now()
                ]);
            });

            return redirect()->route('membres.index')
                ->with('success', 'Membre créé avec succès!');

        } catch (\Exception $e) {
            \Log::error('Création membre error: ' . $e->getMessage());
            return redirect()->back()
                ->withErrors(['error' => 'Erreur lors de la création du membre'])
                ->withInput();
        }
    }

    /**
     * Affichage détaillé d'un membre
     */
    public function show(Membre $membre): Response
    {
        try {
            // Charger relations sécurisées
            if ($this->tableExists('users')) {
                $membre->load('user');
            }

            return Inertia::render('Membres/Show', [
                'membre' => $membre
            ]);

        } catch (\Exception $e) {
            \Log::error('Show membre error: ' . $e->getMessage());
            return redirect()->route('membres.index')
                ->withErrors(['error' => 'Membre introuvable']);
        }
    }

    /**
     * Formulaire d'édition
     */
    public function edit(Membre $membre): Response
    {
        try {
            if ($this->tableExists('users')) {
                $membre->load('user');
            }

            return Inertia::render('Membres/Edit', [
                'membre' => $membre
            ]);

        } catch (\Exception $e) {
            return redirect()->route('membres.index')
                ->withErrors(['error' => 'Erreur lors du chargement']);
        }
    }

    /**
     * Mise à jour membre
     */
    public function update(Request $request, Membre $membre): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'prenom' => 'required|string|max:255',
                'nom' => 'required|string|max:255',
                'date_naissance' => 'required|date',
                'sexe' => 'required|in:M,F,Autre',
                'telephone' => 'nullable|string|max:20',
                'adresse' => 'nullable|string',
                'statut' => 'required|in:actif,inactif,suspendu'
            ]);

            DB::transaction(function () use ($validated, $membre) {
                $membre->update($validated);

                // Mise à jour utilisateur si existe
                if ($membre->user) {
                    $membre->user->update([
                        'name' => $validated['prenom'] . ' ' . $validated['nom']
                    ]);
                }
            });

            return redirect()->route('membres.index')
                ->with('success', 'Membre mis à jour avec succès!');

        } catch (\Exception $e) {
            \Log::error('Update membre error: ' . $e->getMessage());
            return redirect()->back()
                ->withErrors(['error' => 'Erreur lors de la mise à jour'])
                ->withInput();
        }
    }

    /**
     * Suppression membre
     */
    public function destroy(Membre $membre): RedirectResponse
    {
        try {
            // Soft delete - changer statut au lieu de supprimer
            $membre->update(['statut' => 'inactif']);

            return redirect()->route('membres.index')
                ->with('success', 'Membre désactivé avec succès!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Erreur lors de la suppression']);
        }
    }

    /**
     * Export des membres
     */
    public function export(Request $request)
    {
        try {
            $membres = Membre::all();
            
            $filename = 'membres_' . now()->format('Y-m-d') . '.csv';
            
            $callback = function() use ($membres) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['Prénom', 'Nom', 'Email', 'Statut', 'Date inscription']);
                
                foreach ($membres as $membre) {
                    fputcsv($file, [
                        $membre->prenom,
                        $membre->nom,
                        $membre->user->email ?? '',
                        $membre->statut,
                        $membre->created_at->format('Y-m-d')
                    ]);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename={$filename}"
            ]);

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Erreur lors de l\'export']);
        }
    }

    /**
     * Vérifier si une table existe
     */
    private function tableExists(string $table): bool
    {
        try {
            return DB::getSchemaBuilder()->hasTable($table);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Render membres vide en cas d'erreur
     */
    private function renderEmptyMembres(): Response
    {
        return Inertia::render('Membres/Index', [
            'membres' => collect(),
            'stats' => $this->getStatsDefault(),
            'filters' => [],
            'error' => 'Module membres en cours de configuration'
        ]);
    }
}