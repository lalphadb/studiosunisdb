<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CoursHoraire;
use App\Models\Cours;
use App\Models\SessionCours;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class CoursHoraireController extends BaseAdminController
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Display horaires for a specific cours and session
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $ecoleId = $user->ecole_id;

        $query = CoursHoraire::pourEcole($ecoleId)
            ->with(['cours', 'session', 'ecole'])
            ->withCount(['inscriptions']);

        // Filtres
        if ($request->filled('cours_id')) {
            $query->where('cours_id', $request->cours_id);
        }

        if ($request->filled('session_id')) {
            $query->where('session_id', $request->session_id);
        }

        if ($request->filled('jour_semaine')) {
            $query->where('jour_semaine', $request->jour_semaine);
        }

        $horaires = $query->orderBy('jour_semaine')
            ->orderBy('heure_debut')
            ->paginate(20);

        // Options pour les filtres
        $cours = Cours::pourEcole($ecoleId)->actifs()->orderBy('nom')->get();
        $sessions = SessionCours::pourEcole($ecoleId)->orderBy('date_debut', 'desc')->get();
        $jours = [
            'lundi' => 'Lundi',
            'mardi' => 'Mardi',
            'mercredi' => 'Mercredi',
            'jeudi' => 'Jeudi',
            'vendredi' => 'Vendredi',
            'samedi' => 'Samedi',
            'dimanche' => 'Dimanche'
        ];

        return view('admin.cours-horaires.index', compact('horaires', 'cours', 'sessions', 'jours'));
    }

    /**
     * Show the form for creating a new horaire
     */
    public function create(Request $request): View
    {
        $user = Auth::user();
        
        // Pré-remplir si cours_id et session_id fournis
        $coursSelectionne = null;
        $sessionSelectionnee = null;
        
        if ($request->filled('cours_id')) {
            $coursSelectionne = Cours::pourEcole($user->ecole_id)->findOrFail($request->cours_id);
        }
        
        if ($request->filled('session_id')) {
            $sessionSelectionnee = SessionCours::pourEcole($user->ecole_id)->findOrFail($request->session_id);
        }

        $cours = Cours::pourEcole($user->ecole_id)->actifs()->orderBy('nom')->get();
        $sessions = SessionCours::pourEcole($user->ecole_id)->orderBy('date_debut', 'desc')->get();
        
        $jours = [
            'lundi' => 'Lundi',
            'mardi' => 'Mardi',
            'mercredi' => 'Mercredi',
            'jeudi' => 'Jeudi',
            'vendredi' => 'Vendredi',
            'samedi' => 'Samedi',
            'dimanche' => 'Dimanche'
        ];

        return view('admin.cours-horaires.create', compact(
            'cours', 'sessions', 'jours', 'coursSelectionne', 'sessionSelectionnee'
        ));
    }

    /**
     * Store a newly created horaire
     */
    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'cours_id' => 'required|exists:cours,id',
            'session_id' => 'nullable|exists:sessions_cours,id',
            'jour_semaine' => 'required|in:lundi,mardi,mercredi,jeudi,vendredi,samedi,dimanche',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'salle' => 'nullable|string|max:255',
            'instructeur_affecte' => 'nullable|string|max:255',
            'capacite_max' => 'nullable|integer|min:1|max:100',
            'prix' => 'nullable|numeric|min:0|max:999999.99',
            'nom_affiche' => 'nullable|string|max:255',
            'active' => 'boolean'
        ]);

        // Vérifier que le cours appartient à la même école
        $cours = Cours::findOrFail($validated['cours_id']);
        if ($cours->ecole_id !== $user->ecole_id) {
            return back()->with('error', 'Cours non autorisé pour votre école.');
        }

        // Vérifier que la session appartient à la même école si fournie
        if ($validated['session_id']) {
            $session = SessionCours::findOrFail($validated['session_id']);
            if ($session->ecole_id !== $user->ecole_id) {
                return back()->with('error', 'Session non autorisée pour votre école.');
            }
        }

        $validated['ecole_id'] = $user->ecole_id;
        
        $horaire = CoursHoraire::create($validated);

        return redirect()->route('admin.cours.show', $cours)
            ->with('success', "Horaire créé avec succès pour '{$cours->nom}'.");
    }

    /**
     * Display the specified horaire
     */
    public function show(CoursHoraire $coursHoraire): View
    {
        $this->authorize('view', $coursHoraire);

        $coursHoraire->load([
            'cours.ecole',
            'session',
            'inscriptions.user'
        ]);

        // Statistiques
        $stats = [
            'nombre_inscrits' => $coursHoraire->inscriptions->where('statut', '!=', 'annule')->count(),
            'places_restantes' => $coursHoraire->nombre_places_restantes,
            'taux_occupation' => $coursHoraire->capacite_effective > 0 
                ? round(($coursHoraire->nombre_inscrits / $coursHoraire->capacite_effective) * 100, 1)
                : 0,
            'revenus' => $coursHoraire->inscriptions->where('statut', '!=', 'annule')->sum('prix_paye')
        ];

        return view('admin.cours-horaires.show', compact('coursHoraire', 'stats'));
    }

    /**
     * Show the form for editing the specified horaire
     */
    public function edit(CoursHoraire $coursHoraire): View
    {
        $this->authorize('update', $coursHoraire);

        $cours = Cours::pourEcole($coursHoraire->ecole_id)->actifs()->orderBy('nom')->get();
        $sessions = SessionCours::pourEcole($coursHoraire->ecole_id)->orderBy('date_debut', 'desc')->get();
        
        $jours = [
            'lundi' => 'Lundi',
            'mardi' => 'Mardi',
            'mercredi' => 'Mercredi',
            'jeudi' => 'Jeudi',
            'vendredi' => 'Vendredi',
            'samedi' => 'Samedi',
            'dimanche' => 'Dimanche'
        ];

        return view('admin.cours-horaires.edit', compact('coursHoraire', 'cours', 'sessions', 'jours'));
    }

    /**
     * Update the specified horaire
     */
    public function update(Request $request, CoursHoraire $coursHoraire): RedirectResponse
    {
        $this->authorize('update', $coursHoraire);

        $validated = $request->validate([
            'cours_id' => 'required|exists:cours,id',
            'session_id' => 'nullable|exists:sessions_cours,id',
            'jour_semaine' => 'required|in:lundi,mardi,mercredi,jeudi,vendredi,samedi,dimanche',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'salle' => 'nullable|string|max:255',
            'instructeur_affecte' => 'nullable|string|max:255',
            'capacite_max' => 'nullable|integer|min:1|max:100',
            'prix' => 'nullable|numeric|min:0|max:999999.99',
            'nom_affiche' => 'nullable|string|max:255',
            'active' => 'boolean'
        ]);

        $coursHoraire->update($validated);

        return redirect()->route('admin.cours-horaires.show', $coursHoraire)
            ->with('success', 'Horaire mis à jour avec succès.');
    }

    /**
     * Remove the specified horaire
     */
    public function destroy(CoursHoraire $coursHoraire): RedirectResponse
    {
        $this->authorize('delete', $coursHoraire);

        // Vérifier qu'il n'y a pas d'inscriptions actives
        $inscriptionsActives = $coursHoraire->inscriptions()->actives()->count();
        if ($inscriptionsActives > 0) {
            return back()->with('error', "Impossible de supprimer cet horaire : {$inscriptionsActives} inscription(s) active(s).");
        }

        $cours = $coursHoraire->cours;
        $coursHoraire->delete();

        return redirect()->route('admin.cours.show', $cours)
            ->with('success', 'Horaire supprimé avec succès.');
    }

    /**
     * Duplicate an horaire to multiple days or sessions
     */
    public function dupliquer(Request $request, CoursHoraire $coursHoraire): RedirectResponse
    {
        $this->authorize('update', $coursHoraire);

        $validated = $request->validate([
            'jours' => 'array|min:1',
            'jours.*' => 'in:lundi,mardi,mercredi,jeudi,vendredi,samedi,dimanche',
            'session_ids' => 'array|min:1',
            'session_ids.*' => 'exists:sessions_cours,id'
        ]);

        $compteur = 0;
        
        foreach ($validated['session_ids'] ?? [$coursHoraire->session_id] as $sessionId) {
            foreach ($validated['jours'] as $jour) {
                // Éviter de dupliquer sur soi-même
                if ($jour === $coursHoraire->jour_semaine && $sessionId === $coursHoraire->session_id) {
                    continue;
                }

                // Vérifier si un horaire similaire existe déjà
                $existe = CoursHoraire::where([
                    'cours_id' => $coursHoraire->cours_id,
                    'session_id' => $sessionId,
                    'jour_semaine' => $jour,
                    'heure_debut' => $coursHoraire->heure_debut
                ])->exists();

                if (!$existe) {
                    $nouveauHoraire = $coursHoraire->replicate();
                    $nouveauHoraire->session_id = $sessionId;
                    $nouveauHoraire->jour_semaine = $jour;
                    $nouveauHoraire->save();
                    $compteur++;
                }
            }
        }

        return back()->with('success', "{$compteur} horaire(s) dupliqué(s) avec succès.");
    }
}
