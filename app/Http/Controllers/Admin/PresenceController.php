<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cours;
use App\Models\Membre;
use App\Models\Presence;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;

class PresenceController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('can:view-presences', only: ['index', 'show']),
            new Middleware('can:create-presence', only: ['create', 'store']),
            new Middleware('can:edit-presence', only: ['edit', 'update']),
            new Middleware('can:delete-presence', only: ['destroy']),
        ];
    }

    /**
     * Affiche la liste des présences
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Presence::with(['cours', 'membre']);

        // Restriction par école selon le rôle
        if (! $user->hasRole('superadmin')) {
            $query->whereHas('cours', function ($q) use ($user) {
                $q->where('ecole_id', $user->ecole_id);
            });
        }

        // Filtres
        if ($request->filled('cours_id')) {
            $query->where('cours_id', $request->cours_id);
        }

        if ($request->filled('date_debut')) {
            $query->where('date_presence', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->where('date_presence', '<=', $request->date_fin);
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $presences = $query->orderBy('date_presence', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(20);

        // Données pour les filtres
        $coursQuery = Cours::query();
        if (! $user->hasRole('superadmin')) {
            $coursQuery->where('ecole_id', $user->ecole_id);
        }
        $cours = $coursQuery->get();

        return view('admin.presences.index', compact('presences', 'cours'));
    }

    /**
     * Affiche le formulaire de création
     */
    public function create(Request $request)
    {
        $user = Auth::user();

        // Cours disponibles selon l'école
        $coursQuery = Cours::with('ecole');
        if (! $user->hasRole('superadmin')) {
            $coursQuery->where('ecole_id', $user->ecole_id);
        }
        $cours = $coursQuery->get();

        // Membres disponibles selon l'école
        $membresQuery = Membre::query();
        if (! $user->hasRole('superadmin')) {
            $membresQuery->where('ecole_id', $user->ecole_id);
        }
        $membres = $membresQuery->get();

        // Pré-sélection si cours_id passé en paramètre
        $selectedCours = $request->get('cours_id');

        return view('admin.presences.create', compact('cours', 'membres', 'selectedCours'));
    }

    /**
     * Enregistre une nouvelle présence
     */
    public function store(Request $request)
    {
        $request->validate([
            'cours_id' => 'required|exists:cours,id',
            'membre_id' => 'required|exists:membres,id',
            'date_presence' => 'required|date',
            'statut' => 'required|in:present,absent,retard,excuse',
            'heure_arrivee' => 'nullable|date_format:H:i',
            'heure_depart' => 'nullable|date_format:H:i',
            'notes' => 'nullable|string|max:500',
        ]);

        // Vérification que le cours et le membre appartiennent à la même école
        $cours = Cours::findOrFail($request->cours_id);
        $membre = Membre::findOrFail($request->membre_id);

        if ($cours->ecole_id !== $membre->ecole_id) {
            return back()->withErrors(['error' => 'Le cours et le membre doivent appartenir à la même école.']);
        }

        // Vérification des permissions d'école
        $user = Auth::user();
        if (! $user->hasRole('superadmin') && $cours->ecole_id !== $user->ecole_id) {
            abort(403, 'Accès non autorisé à cette école.');
        }

        // Vérification unicité (un membre ne peut avoir qu'une présence par cours par date)
        $existingPresence = Presence::where([
            'cours_id' => $request->cours_id,
            'membre_id' => $request->membre_id,
            'date_presence' => $request->date_presence,
        ])->first();

        if ($existingPresence) {
            return back()->withErrors(['error' => 'Une présence existe déjà pour ce membre à ce cours à cette date.']);
        }

        Presence::create($request->all());

        return redirect()->route('admin.presences.index')
            ->with('success', 'Présence enregistrée avec succès.');
    }

    /**
     * Affiche une présence spécifique
     */
    public function show(Presence $presence)
    {
        $user = Auth::user();

        // Vérification des permissions d'école
        if (! $user->hasRole('superadmin') && $presence->cours->ecole_id !== $user->ecole_id) {
            abort(403, 'Accès non autorisé à cette école.');
        }

        $presence->load(['cours.ecole', 'membre']);

        return view('admin.presences.show', compact('presence'));
    }

    /**
     * Affiche le formulaire d'édition
     */
    public function edit(Presence $presence)
    {
        $user = Auth::user();

        // Vérification des permissions d'école
        if (! $user->hasRole('superadmin') && $presence->cours->ecole_id !== $user->ecole_id) {
            abort(403, 'Accès non autorisé à cette école.');
        }

        // Cours disponibles selon l'école
        $coursQuery = Cours::with('ecole');
        if (! $user->hasRole('superadmin')) {
            $coursQuery->where('ecole_id', $user->ecole_id);
        }
        $cours = $coursQuery->get();

        // Membres disponibles selon l'école
        $membresQuery = Membre::query();
        if (! $user->hasRole('superadmin')) {
            $membresQuery->where('ecole_id', $user->ecole_id);
        }
        $membres = $membresQuery->get();

        return view('admin.presences.edit', compact('presence', 'cours', 'membres'));
    }

    /**
     * Met à jour une présence
     */
    public function update(Request $request, Presence $presence)
    {
        $user = Auth::user();

        // Vérification des permissions d'école
        if (! $user->hasRole('superadmin') && $presence->cours->ecole_id !== $user->ecole_id) {
            abort(403, 'Accès non autorisé à cette école.');
        }

        $request->validate([
            'cours_id' => 'required|exists:cours,id',
            'membre_id' => 'required|exists:membres,id',
            'date_presence' => 'required|date',
            'statut' => 'required|in:present,absent,retard,excuse',
            'heure_arrivee' => 'nullable|date_format:H:i',
            'heure_depart' => 'nullable|date_format:H:i',
            'notes' => 'nullable|string|max:500',
        ]);

        // Vérification que le cours et le membre appartiennent à la même école
        $cours = Cours::findOrFail($request->cours_id);
        $membre = Membre::findOrFail($request->membre_id);

        if ($cours->ecole_id !== $membre->ecole_id) {
            return back()->withErrors(['error' => 'Le cours et le membre doivent appartenir à la même école.']);
        }

        // Vérification des permissions d'école pour les nouvelles données
        if (! $user->hasRole('superadmin') && $cours->ecole_id !== $user->ecole_id) {
            abort(403, 'Accès non autorisé à cette école.');
        }

        // Vérification unicité (sauf pour la présence actuelle)
        $existingPresence = Presence::where([
            'cours_id' => $request->cours_id,
            'membre_id' => $request->membre_id,
            'date_presence' => $request->date_presence,
        ])->where('id', '!=', $presence->id)->first();

        if ($existingPresence) {
            return back()->withErrors(['error' => 'Une présence existe déjà pour ce membre à ce cours à cette date.']);
        }

        $presence->update($request->all());

        return redirect()->route('admin.presences.index')
            ->with('success', 'Présence mise à jour avec succès.');
    }

    /**
     * Supprime une présence
     */
    public function destroy(Presence $presence)
    {
        $user = Auth::user();

        // Vérification des permissions d'école
        if (! $user->hasRole('superadmin') && $presence->cours->ecole_id !== $user->ecole_id) {
            abort(403, 'Accès non autorisé à cette école.');
        }

        $presence->delete();

        return redirect()->route('admin.presences.index')
            ->with('success', 'Présence supprimée avec succès.');
    }

    /**
     * Interface de prise de présence rapide pour un cours
     */
    public function prisePresence(Request $request, Cours $cours)
    {
        $user = Auth::user();

        // Vérification des permissions d'école
        if (! $user->hasRole('superadmin') && $cours->ecole_id !== $user->ecole_id) {
            abort(403, 'Accès non autorisé à cette école.');
        }

        $date = $request->get('date', Carbon::today()->format('Y-m-d'));

        // Récupérer les membres inscrits à ce cours
        $membres = $cours->membres()->get();

        // Récupérer les présences existantes pour cette date
        $presencesExistantes = Presence::where('cours_id', $cours->id)
            ->where('date_presence', $date)
            ->get()
            ->keyBy('membre_id');

        return view('admin.presences.prise-presence', compact('cours', 'membres', 'date', 'presencesExistantes'));
    }

    /**
     * Enregistre les présences en lot
     */
    public function storePrisePresence(Request $request, Cours $cours)
    {
        $user = Auth::user();

        // Vérification des permissions d'école
        if (! $user->hasRole('superadmin') && $cours->ecole_id !== $user->ecole_id) {
            abort(403, 'Accès non autorisé à cette école.');
        }

        $request->validate([
            'date_presence' => 'required|date',
            'presences' => 'array',
            'presences.*.membre_id' => 'required|exists:membres,id',
            'presences.*.statut' => 'required|in:present,absent,retard,excuse',
            'presences.*.heure_arrivee' => 'nullable|date_format:H:i',
            'presences.*.notes' => 'nullable|string|max:200',
        ]);

        $date = $request->date_presence;

        // Supprimer les présences existantes pour cette date et ce cours
        Presence::where('cours_id', $cours->id)
            ->where('date_presence', $date)
            ->delete();

        // Créer les nouvelles présences
        foreach ($request->presences as $presenceData) {
            if (isset($presenceData['statut'])) {
                Presence::create([
                    'cours_id' => $cours->id,
                    'membre_id' => $presenceData['membre_id'],
                    'date_presence' => $date,
                    'statut' => $presenceData['statut'],
                    'heure_arrivee' => $presenceData['heure_arrivee'] ?? null,
                    'notes' => $presenceData['notes'] ?? null,
                ]);
            }
        }

        return redirect()->route('admin.presences.prise-presence', $cours)
            ->with('success', 'Présences enregistrées avec succès.');
    }

    /**
     * Export PDF des présences
     */
    public function exportPdf(Request $request)
    {
        $user = Auth::user();
        $query = Presence::with(['cours.ecole', 'membre']);

        // Restriction par école selon le rôle
        if (! $user->hasRole('superadmin')) {
            $query->whereHas('cours', function ($q) use ($user) {
                $q->where('ecole_id', $user->ecole_id);
            });
        }

        // Filtres
        if ($request->filled('cours_id')) {
            $query->where('cours_id', $request->cours_id);
        }

        if ($request->filled('date_debut')) {
            $query->where('date_presence', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->where('date_presence', '<=', $request->date_fin);
        }

        $presences = $query->orderBy('date_presence', 'desc')->get();

        $pdf = Pdf::loadView('admin.presences.pdf', compact('presences'));

        return $pdf->download('presences_'.Carbon::now()->format('Y-m-d_H-i-s').'.pdf');
    }

    /**
     * Affiche les statistiques de présences
     */
    public function statistiques(Request $request)
    {
        $user = Auth::user();
        $query = Presence::with(['cours.ecole', 'membre']);

        // Restriction par école selon le rôle
        if (! $user->hasRole('superadmin')) {
            $query->whereHas('cours', function ($q) use ($user) {
                $q->where('ecole_id', $user->ecole_id);
            });
        }

        // Période par défaut : 30 derniers jours
        $dateDebut = $request->get('date_debut', Carbon::now()->subDays(30)->format('Y-m-d'));
        $dateFin = $request->get('date_fin', Carbon::now()->format('Y-m-d'));

        $query->whereBetween('date_presence', [$dateDebut, $dateFin]);

        $presences = $query->get();

        // Calculs statistiques
        $stats = [
            'total' => $presences->count(),
            'presents' => $presences->where('statut', 'present')->count(),
            'absents' => $presences->where('statut', 'absent')->count(),
            'retards' => $presences->where('statut', 'retard')->count(),
            'excuses' => $presences->where('statut', 'excuse')->count(),
            'taux_presence' => $presences->count() > 0 ?
                round(($presences->where('statut', 'present')->count() / $presences->count()) * 100, 1) : 0,
        ];

        // Statistiques par cours
        $statsCours = $presences->groupBy('cours.nom')->map(function ($presencesCours) {
            return [
                'total' => $presencesCours->count(),
                'presents' => $presencesCours->where('statut', 'present')->count(),
                'taux' => $presencesCours->count() > 0 ?
                    round(($presencesCours->where('statut', 'present')->count() / $presencesCours->count()) * 100, 1) : 0,
            ];
        });

        return view('admin.presences.statistiques', compact('stats', 'statsCours', 'dateDebut', 'dateFin'));
    }
}
