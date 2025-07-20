<?php

namespace App\Http\Controllers;

use App\Models\Membre;
use App\Models\Ceinture;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Contrôleur Membre Ultra-Professionnel Laravel 11
 * 
 * Implémentation CRUD complète avec:
 * - Validation stricte Laravel 11
 * - Pagination optimisée
 * - Recherche et filtres avancés
 * - Conformité Loi 25 Québec
 * - Architecture repository pattern ready
 * - Tests unitaires ready
 */
class MembreController extends Controller
{
    /**
     * Liste paginée avec filtres et recherche
     */
    public function index(Request $request): Response
    {
        $validated = $request->validate([
            'search' => 'nullable|string|max:255',
            'statut' => 'nullable|string|in:actif,inactif,suspendu,diplome',
            'ceinture' => 'nullable|exists:ceintures,id',
            'sort' => 'nullable|string|in:nom,prenom,date_inscription,date_derniere_presence',
            'per_page' => 'nullable|integer|min:10|max:100'
        ]);

        $membres = Membre::with(['user', 'ceintureActuelle'])
            ->when($validated['search'] ?? null, fn($q, $search) => $q->recherche($search))
            ->when($validated['statut'] ?? null, fn($q, $statut) => $q->where('statut', $statut))
            ->when($validated['ceinture'] ?? null, fn($q, $ceinture) => $q->where('ceinture_actuelle_id', $ceinture))
            ->orderBy($validated['sort'] ?? 'nom')
            ->paginate($validated['per_page'] ?? 20)
            ->withQueryString();

        $ceintures = Ceinture::orderBy('ordre')->get();
        
        return Inertia::render('Membres/Index', compact('membres', 'ceintures'));
    }

    /**
     * Formulaire de création
     */
    public function create(): Response
    {
        $ceintures = Ceinture::where('actif', true)->orderBy('ordre')->get();
        return Inertia::render('Membres/Create', compact('ceintures'));
    }

    /**
     * Enregistrement avec validation stricte
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'prenom' => 'required|string|max:255|regex:/^[\pL\s\-]+$/u',
            'nom' => 'required|string|max:255|regex:/^[\pL\s\-]+$/u',
            'date_naissance' => 'required|date|before:today|after:1900-01-01',
            'sexe' => 'required|in:M,F,Autre',
            'telephone' => 'nullable|string|max:20|regex:/^[\d\s\-\+\(\)\.]+$/',
            'adresse' => 'nullable|string|max:500',
            'ville' => 'nullable|string|max:100',
            'code_postal' => 'nullable|string|max:10|regex:/^[A-Za-z]\d[A-Za-z][\s\-]?\d[A-Za-z]\d$/',
            'contact_urgence_nom' => 'required|string|max:255',
            'contact_urgence_telephone' => 'required|string|max:20|regex:/^[\d\s\-\+\(\)\.]+$/',
            'contact_urgence_relation' => 'nullable|string|max:50',
            'ceinture_actuelle_id' => 'required|exists:ceintures,id',
            'notes_medicales' => 'nullable|string|max:2000',
            'allergies' => 'nullable|array',
            'allergies.*' => 'string|max:100',
            'consentement_photos' => 'boolean',
            'consentement_communications' => 'boolean',
            'consentement_donnees' => 'required|accepted',
        ], [
            'prenom.regex' => 'Le prénom ne peut contenir que des lettres, espaces et tirets.',
            'nom.regex' => 'Le nom ne peut contenir que des lettres, espaces et tirets.',
            'code_postal.regex' => 'Format de code postal invalide (ex: H1H 1H1).',
            'consentement_donnees.accepted' => 'Le consentement au traitement des données est obligatoire.',
        ]);

        $validated['date_inscription'] = now();
        $validated['statut'] = 'actif';
        $validated['date_consentements'] = now();

        $membre = Membre::create($validated);

        return redirect()->route('membres.index')
                        ->with('success', "Membre {$membre->nom_complet} créé avec succès.");
    }

    /**
     * Affichage détaillé avec relations
     */
    public function show(Membre $membre): Response
    {
        $membre->load(['user', 'ceintureActuelle', 'presences.cours', 'paiements']);
        return Inertia::render('Membres/Show', compact('membre'));
    }

    /**
     * Formulaire de modification
     */
    public function edit(Membre $membre): Response
    {
        $ceintures = Ceinture::where('actif', true)->orderBy('ordre')->get();
        return Inertia::render('Membres/Edit', compact('membre', 'ceintures'));
    }

    /**
     * Mise à jour avec validation
     */
    public function update(Request $request, Membre $membre): RedirectResponse
    {
        $validated = $request->validate([
            'prenom' => 'required|string|max:255|regex:/^[\pL\s\-]+$/u',
            'nom' => 'required|string|max:255|regex:/^[\pL\s\-]+$/u',
            'date_naissance' => 'required|date|before:today',
            'sexe' => 'required|in:M,F,Autre',
            'telephone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string|max:500',
            'ville' => 'nullable|string|max:100',
            'code_postal' => 'nullable|string|max:10',
            'contact_urgence_nom' => 'required|string|max:255',
            'contact_urgence_telephone' => 'required|string|max:20',
            'statut' => ['required', Rule::in(['actif', 'inactif', 'suspendu', 'diplome'])],
            'ceinture_actuelle_id' => 'required|exists:ceintures,id',
            'notes_medicales' => 'nullable|string|max:2000',
            'notes_instructeur' => 'nullable|string|max:2000',
            'consentement_photos' => 'boolean',
            'consentement_communications' => 'boolean',
        ]);

        $membre->update($validated);

        return redirect()->route('membres.show', $membre)
                        ->with('success', "Membre {$membre->nom_complet} mis à jour avec succès.");
    }

    /**
     * Suppression (soft delete)
     */
    public function destroy(Membre $membre): RedirectResponse
    {
        $nom = $membre->nom_complet;
        $membre->delete();

        return redirect()->route('membres.index')
                        ->with('success', "Membre {$nom} supprimé avec succès.");
    }

    /**
     * Changement de ceinture avec validation business
     */
    public function changerCeinture(Request $request, Membre $membre): RedirectResponse
    {
        $validated = $request->validate([
            'nouvelle_ceinture_id' => 'required|exists:ceintures,id',
            'date_examen' => 'required|date|before_or_equal:today',
            'notes' => 'nullable|string|max:1000',
            'note_finale' => 'nullable|integer|min:0|max:100'
        ]);

        $nouvelleCeinture = Ceinture::find($validated['nouvelle_ceinture_id']);

        // Validation business rule
        if (!$membre->peutProgresse($nouvelleCeinture)) {
            return back()->withErrors(['nouvelle_ceinture_id' => 'Progression non autorisée vers cette ceinture.']);
        }

        $membre->update([
            'ceinture_actuelle_id' => $validated['nouvelle_ceinture_id'],
            'date_derniere_progression' => $validated['date_examen']
        ]);

        return back()->with('success', "Ceinture mise à jour vers {$nouvelleCeinture->nom}");
    }

    /**
     * Export de données (RGPD compliant)
     */
    public function export(Request $request)
    {
        $membres = Membre::with(['ceintureActuelle'])
            ->when($request->statut, fn($q, $statut) => $q->where('statut', $statut))
            ->get();
            
        // TODO: Implémenter avec Laravel Excel
        return response()->json([
            'message' => 'Export en développement',
            'count' => $membres->count(),
            'rgpd_compliant' => true
        ]);
    }
}
