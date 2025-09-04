<?php

namespace App\Http\Controllers;

use App\Models\Presence;
use App\Models\Cours;
use App\Models\Membre;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Carbon\Carbon;

/**
 * Contrôleur Présences Ultra-Professionnel Laravel 11
 * Interface tablette tactile et gestion complète
 */
class PresenceController extends Controller
{
    /**
     * Liste des présences avec filtres
     */
    public function index(Request $request): Response
    {
        $query = Presence::with(['membre', 'cours'])
            ->when($request->date_debut, fn($q, $date) => $q->whereDate('date_cours', '>=', $date))
            ->when($request->date_fin, fn($q, $date) => $q->whereDate('date_cours', '<=', $date))
            ->when($request->cours_id, fn($q, $cours) => $q->where('cours_id', $cours))
            ->when($request->statut, fn($q, $statut) => $q->where('statut', $statut));

        $presences = $query->orderBy('date_cours', 'desc')
                          ->orderBy('heure_arrivee', 'desc')
                          ->paginate(50);

        $cours = Cours::where('statut', 'actif')->get(['id', 'nom']);
        
        $stats = [
            'total_presences' => Presence::count(),
            'presences_aujourd_hui' => Presence::whereDate('date_cours', today())->count(),
            'taux_presence_semaine' => $this->calculateTauxPresenceSemaine(),
        ];

        return Inertia::render('Presences/Index', compact('presences', 'cours', 'stats'));
    }

    /**
     * Interface tablette tactile
     */
    public function tablette(Request $request): Response
    {
        $cours = Cours::where('statut', 'actif')
            ->with(['membres' => function($query) {
                $query->where('statut', 'actif');
            }])
            ->get();

        $cours_today = $cours->filter(function($cours) {
            // Logique pour filtrer les cours du jour actuel
            // TODO: Implémenter avec les horaires
            return true;
        });

        return Inertia::render('Presences/Tablette', [
            'cours' => $cours_today->values(),
            'date_cours' => today()->format('Y-m-d'),
            'message_accueil' => "Bienvenue au dojo! Sélectionnez votre cours."
        ]);
    }

    /**
     * Sauvegarder les présences (API endpoint)
     */
    public function sauvegarder(Request $request)
    {
        try {
            $validated = $request->validate([
                'cours_id' => 'required|exists:cours,id',
                'date_cours' => 'required|date',
                'presences' => 'required|array',
                'presences.*.membre_id' => 'required|exists:membres,id',
                'presences.*.statut' => 'required|in:present,absent,retard,excuse',
                'presences.*.heure_arrivee' => 'nullable|date_format:H:i',
                'presences.*.notes' => 'nullable|string|max:500'
            ]);

            $count = 0;
            foreach ($validated['presences'] as $presenceData) {
                Presence::updateOrCreate(
                    [
                        'cours_id' => $validated['cours_id'],
                        'membre_id' => $presenceData['membre_id'],
                        'date_cours' => $validated['date_cours']
                    ],
                    [
                        'statut' => $presenceData['statut'],
                        'heure_arrivee' => $presenceData['heure_arrivee'] ?? null,
                        'notes' => $presenceData['notes'] ?? null,
                        'instructeur_id' => auth()->id()
                    ]
                );
                $count++;
            }

            return response()->json([
                'success' => true,
                'message' => "✅ {$count} présences sauvegardées avec succès!",
                'count' => $count
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "❌ Erreur lors de la sauvegarde: " . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Rapport des présences
     */
    public function rapport(Request $request): Response
    {
        $periode = $request->periode ?? 'semaine';
        
        $dateDebut = match($periode) {
            'jour' => today(),
            'semaine' => today()->startOfWeek(),
            'mois' => today()->startOfMonth(),
            'annee' => today()->startOfYear(),
            default => today()->startOfWeek()
        };

        $stats = [
            'total_presences' => Presence::whereDate('date_cours', '>=', $dateDebut)->count(),
            'membres_actifs' => Presence::whereDate('date_cours', '>=', $dateDebut)
                                      ->distinct('membre_id')->count(),
            'cours_actifs' => Presence::whereDate('date_cours', '>=', $dateDebut)
                                    ->distinct('cours_id')->count(),
            'taux_presence' => $this->calculateTauxPresence($dateDebut),
            'evolution' => $this->calculateEvolutionPresences($periode),
        ];

        $presences_by_day = Presence::whereDate('date_cours', '>=', $dateDebut)
            ->selectRaw('DATE(date_cours) as date, COUNT(*) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $presences_by_cours = Presence::with('cours')
            ->whereDate('date_cours', '>=', $dateDebut)
            ->selectRaw('cours_id, COUNT(*) as total')
            ->groupBy('cours_id')
            ->get();

        return Inertia::render('Presences/Rapport', [
            'stats' => $stats,
            'periode' => $periode,
            'date_debut' => $dateDebut->format('Y-m-d'),
            'presences_by_day' => $presences_by_day,
            'presences_by_cours' => $presences_by_cours,
        ]);
    }

    /**
     * Calculer le taux de présence de la semaine
     */
    private function calculateTauxPresenceSemaine(): float
    {
        $debuts_semaine = today()->startOfWeek();
        $presents = Presence::where('statut', 'present')
                           ->whereDate('date_cours', '>=', $debuts_semaine)
                           ->count();
        
        $total = Presence::whereDate('date_cours', '>=', $debuts_semaine)->count();
        
        return $total > 0 ? round(($presents / $total) * 100, 1) : 0;
    }

    /**
     * Calculer le taux de présence pour une période
     */
    private function calculateTauxPresence($dateDebut): float
    {
        $presents = Presence::where('statut', 'present')
                           ->whereDate('date_cours', '>=', $dateDebut)
                           ->count();
        
        $total = Presence::whereDate('date_cours', '>=', $dateDebut)->count();
        
        return $total > 0 ? round(($presents / $total) * 100, 1) : 0;
    }

    /**
     * Calculer l'évolution des présences
     */
    private function calculateEvolutionPresences(string $periode): float
    {
        $now = today();
        
        $currentCount = match($periode) {
            'jour' => Presence::whereDate('date_cours', $now)->count(),
            'semaine' => Presence::whereBetween('date_cours', [$now->startOfWeek(), $now->endOfWeek()])->count(),
            'mois' => Presence::whereMonth('date_cours', $now->month)->count(),
            default => 0
        };

        $previousCount = match($periode) {
            'jour' => Presence::whereDate('date_cours', $now->subDay())->count(),
            'semaine' => Presence::whereBetween('date_cours', [$now->subWeek()->startOfWeek(), $now->subWeek()->endOfWeek()])->count(),
            'mois' => Presence::whereMonth('date_cours', $now->subMonth()->month)->count(),
            default => 0
        };

        if ($previousCount === 0) return 100;
        
        return round((($currentCount - $previousCount) / $previousCount) * 100, 1);
    }
}
