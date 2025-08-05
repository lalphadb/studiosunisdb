<?php

namespace App\Http\Controllers;

use App\Models\Membre;
use App\Models\Ceinture;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;

/**
 * MembreController - Ultra-Professionnel Laravel 12.21
 * 
 * Contrôleur de gestion des membres pour StudiosDB v5 Pro
 * Compatible avec la nouvelle interface de style identique aux cours
 * 
 * @package StudiosDB\v5\Controllers
 * @version 5.0.0
 * @author StudiosDB Team
 */
class MembreController extends Controller
{
    /**
     * Affichage de la liste des membres avec statistiques
     * 
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        // Construction de la requête avec optimisations
        $query = Membre::with(['ceintureActuelle', 'user'])
            ->withCount(['presences', 'paiements'])
            ->select([
                'membres.*',
                'users.name as user_name',
                'users.email as user_email'
            ])
            ->join('users', 'membres.user_id', '=', 'users.id');

        // Filtres de recherche
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('membres.prenom', 'like', "%{$search}%")
                  ->orWhere('membres.nom', 'like', "%{$search}%")
                  ->orWhere('users.email', 'like', "%{$search}%");
            });
        }

        // Filtre par statut
        if ($request->filled('statut')) {
            $query->where('membres.statut', $request->get('statut'));
        }

        // Filtre par ceinture
        if ($request->filled('ceinture')) {
            $query->where('membres.ceinture_actuelle_id', $request->get('ceinture'));
        }

        // Pagination avec tri
        $membres = $query->orderBy('membres.created_at', 'desc')
            ->paginate(12)
            ->appends($request->query());

        // Calcul des statistiques ultra-pro
        $stats = $this->calculateStats();
        
        // Récupération des ceintures pour les filtres
        $ceintures = Ceinture::orderBy('ordre')->get();

        // Enrichissement des données membres
        $membres->getCollection()->transform(function ($membre) {
            return $this->enrichMembreData($membre);
        });

        return Inertia::render('Membres/Index', [
            'membres' => $membres,
            'ceintures' => $ceintures,
            'stats' => $stats,
            'filters' => $request->only(['search', 'statut', 'ceinture'])
        ]);
    }

    /**
     * Calcul des statistiques temps réel
     * 
     * @return array
     */
    private function calculateStats(): array
    {
        $now = Carbon::now();
        $debutMois = $now->copy()->startOfMonth();
        
        return [
            'total_membres' => Membre::count(),
            'membres_actifs' => Membre::where('statut', 'actif')->count(),
            'nouveaux_mois' => Membre::where('created_at', '>=', $debutMois)->count(),
            'retard_paiement' => Membre::whereHas('paiements', function ($query) {
                $query->where('statut', 'en_retard')
                      ->where('date_echeance', '<', Carbon::now());
            })->count()
        ];
    }

    /**
     * Enrichissement des données membre avec calculs
     * 
     * @param Membre $membre
     * @return Membre
     */
    private function enrichMembreData(Membre $membre): Membre
    {
        // Calcul du taux d'assiduité (30 derniers jours)
        $dateDebut = Carbon::now()->subDays(30);
        $totalCours = $membre->cours()->count();
        $presencesRecentes = $membre->presences()
            ->where('date_cours', '>=', $dateDebut)
            ->where('statut', 'present')
            ->count();
            
        $membre->taux_assiduite = $totalCours > 0 
            ? round(($presencesRecentes / $totalCours) * 100, 1)
            : 0;

        return $membre;
    }

    /**
     * Affichage du formulaire de création
     * 
     * @return Response
     */
    public function create(): Response
    {
        $ceintures = Ceinture::orderBy('ordre')->get();
        
        return Inertia::render('Membres/Create', [
            'ceintures' => $ceintures
        ]);
    }

    /**
     * Enregistrement d'un nouveau membre
     * 
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'date_naissance' => 'required|date',
            'sexe' => 'required|in:M,F,Autre',
            'telephone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string',
            'contact_urgence_nom' => 'nullable|string|max:255',
            'contact_urgence_telephone' => 'nullable|string|max:20',
            'ceinture_actuelle_id' => 'nullable|exists:ceintures,id',
            'notes_medicales' => 'nullable|string',
            'consentement_photos' => 'boolean',
            'consentement_communications' => 'boolean'
        ]);

        // Création utilisateur
        $user = User::create([
            'name' => $validated['prenom'] . ' ' . $validated['nom'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password'])
        ]);

        // Assignation du rôle membre
        $user->assignRole('membre');

        // Création du membre
        $membre = Membre::create(array_merge($validated, [
            'user_id' => $user->id,
            'date_inscription' => Carbon::now(),
            'statut' => 'actif'
        ]));

        return redirect()->route('membres.index')
            ->with('success', 'Membre créé avec succès!');
    }

    /**
     * Affichage détaillé d'un membre
     * 
     * @param Membre $membre
     * @return Response
     */
    public function show(Membre $membre): Response
    {
        $membre->load([
            'ceintureActuelle',
            'user',
            'presences' => fn($q) => $q->latest()->take(10),
            'paiements' => fn($q) => $q->latest()->take(5),
            'cours'
        ]);

        return Inertia::render('Membres/Show', [
            'membre' => $membre
        ]);
    }

    /**
     * Affichage du formulaire d'édition
     * 
     * @param Membre $membre
     * @return Response
     */
    public function edit(Membre $membre): Response
    {
        $membre->load(['ceintureActuelle', 'user']);
        $ceintures = Ceinture::orderBy('ordre')->get();

        return Inertia::render('Membres/Edit', [
            'membre' => $membre,
            'ceintures' => $ceintures
        ]);
    }

    /**
     * Mise à jour d'un membre
     * 
     * @param Request $request
     * @param Membre $membre
     * @return RedirectResponse
     */
    public function update(Request $request, Membre $membre): RedirectResponse
    {
        $validated = $request->validate([
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'date_naissance' => 'required|date',
            'sexe' => 'required|in:M,F,Autre',
            'telephone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string',
            'contact_urgence_nom' => 'nullable|string|max:255',
            'contact_urgence_telephone' => 'nullable|string|max:20',
            'ceinture_actuelle_id' => 'nullable|exists:ceintures,id',
            'statut' => 'required|in:actif,inactif,suspendu',
            'notes_medicales' => 'nullable|string',
            'consentement_photos' => 'boolean',
            'consentement_communications' => 'boolean'
        ]);

        $membre->update($validated);

        // Mise à jour nom utilisateur
        $membre->user->update([
            'name' => $validated['prenom'] . ' ' . $validated['nom']
        ]);

        return redirect()->route('membres.index')
            ->with('success', 'Membre mis à jour avec succès!');
    }

    /**
     * Suppression d'un membre
     * 
     * @param Membre $membre
     * @return RedirectResponse
     */
    public function destroy(Membre $membre): RedirectResponse
    {
        // Soft delete pour préserver l'historique
        $membre->update(['statut' => 'inactif']);
        
        return redirect()->route('membres.index')
            ->with('success', 'Membre désactivé avec succès!');
    }

    /**
     * Export Excel des membres
     * 
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export(Request $request)
    {
        return Excel::download(new MembresExport($request->all()), 
            'membres_' . Carbon::now()->format('Y-m-d') . '.xlsx');
    }

    /**
     * Changement de ceinture
     * 
     * @param Request $request
     * @param Membre $membre
     * @return RedirectResponse
     */
    public function changerCeinture(Request $request, Membre $membre): RedirectResponse
    {
        $validated = $request->validate([
            'nouvelle_ceinture_id' => 'required|exists:ceintures,id',
            'notes_examen' => 'nullable|string'
        ]);

        $membre->update([
            'ceinture_actuelle_id' => $validated['nouvelle_ceinture_id']
        ]);

        // Enregistrer la progression
        ProgressionCeinture::create([
            'membre_id' => $membre->id,
            'ceinture_actuelle_id' => $membre->ceinture_actuelle_id,
            'ceinture_cible_id' => $validated['nouvelle_ceinture_id'],
            'instructeur_id' => auth()->id(),
            'statut' => 'certifie',
            'date_examen' => Carbon::now(),
            'notes_instructeur' => $validated['notes_examen']
        ]);

        return redirect()->back()
            ->with('success', 'Ceinture mise à jour avec succès!');
    }
}
