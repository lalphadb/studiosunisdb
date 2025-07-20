<?php

namespace App\Http\Controllers;

use App\Models\Membre;
use App\Models\Cours;
use App\Models\Presence;
use App\Models\Paiement;
use App\Models\Ceinture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Statistiques principales
        $stats = $this->getStatisticsPrincipales();
        
        // Données spécifiques selon le rôle
        if ($user->hasRole(['admin', 'superadmin'])) {
            return $this->dashboardAdmin($stats);
        } elseif ($user->hasRole('instructeur')) {
            return $this->dashboardInstructeur($stats, $user);
        } else {
            return $this->dashboardMembre($stats, $user);
        }
    }

    private function getStatisticsPrincipales(): array
    {
        // Statistiques de base
        $totalMembres = Membre::count();
        $membresActifs = Membre::where('statut', 'actif')->count();
        $totalCours = Cours::where('actif', true)->count();
        $presencesAujourdhui = Presence::whereDate('date_cours', today())->count();
        
        // Revenus du mois
        $revenusMois = Paiement::where('statut', 'paye')
            ->whereMonth('date_paiement', now()->month)
            ->whereYear('date_paiement', now()->year)
            ->sum('montant');
            
        $revenusMoisPrecedent = Paiement::where('statut', 'paye')
            ->whereMonth('date_paiement', now()->subMonth()->month)
            ->whereYear('date_paiement', now()->subMonth()->year)
            ->sum('montant');
            
        $evolutionRevenus = $revenusMoisPrecedent > 0 ? 
            (($revenusMois - $revenusMoisPrecedent) / $revenusMoisPrecedent) * 100 : 0;

        // Paiements en retard
        $paiementsEnRetard = Paiement::where('statut', 'en_attente')
            ->where('date_echeance', '<', today())
            ->count();

        return [
            'total_membres' => $totalMembres,
            'membres_actifs' => $membresActifs,
            'total_cours' => $totalCours,
            'presences_aujourd_hui' => $presencesAujourdhui,
            'revenus_mois' => $revenusMois,
            'evolution_revenus' => round($evolutionRevenus, 1),
            'paiements_en_retard' => $paiementsEnRetard,
        ];
    }

    private function dashboardAdmin(array $stats): \Inertia\Response
    {
        // Progression des ceintures
        $progressionCeintures = Ceinture::leftJoin('membres', 'ceintures.id', '=', 'membres.ceinture_actuelle_id')
            ->select('ceintures.nom', 'ceintures.couleur_hex', DB::raw('COUNT(membres.id) as count'))
            ->where('ceintures.actif', true)
            ->groupBy('ceintures.id', 'ceintures.nom', 'ceintures.couleur_hex', 'ceintures.ordre')
            ->orderBy('ceintures.ordre')
            ->get();

        // Évolution des présences (6 derniers mois)
        $evolutionPresences = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $presences = Presence::whereYear('date_cours', $date->year)
                ->whereMonth('date_cours', $date->month)
                ->whereIn('statut', ['present', 'retard'])
                ->count();
                
            $evolutionPresences[] = [
                'mois' => $date->format('M'),
                'presences' => $presences
            ];
        }

        // Statistiques de capacité
        $coursActifs = Cours::where('actif', true)->get();
        $totalPlaces = $coursActifs->sum('places_max');
        $placesOccupees = DB::table('cours_membres')
            ->join('cours', 'cours_membres.cours_id', '=', 'cours.id')
            ->where('cours.actif', true)
            ->where('cours_membres.statut', 'actif')
            ->count();

        $tauxOccupation = $totalPlaces > 0 ? round(($placesOccupees / $totalPlaces) * 100) : 0;

        // Taux de présence global
        $totalPresencesAttendues = DB::table('cours_membres')
            ->join('cours', 'cours_membres.cours_id', '=', 'cours.id')
            ->where('cours.actif', true)
            ->where('cours_membres.statut', 'actif')
            ->whereMonth('cours_membres.created_at', now()->month)
            ->count() * 4; // Approximation 4 cours par mois

        $presencesEffectives = Presence::whereMonth('date_cours', now()->month)
            ->whereIn('statut', ['present', 'retard'])
            ->count();

        $tauxPresence = $totalPresencesAttendues > 0 ? 
            round(($presencesEffectives / $totalPresencesAttendues) * 100) : 0;

        return Inertia::render('Dashboard', [
            'stats' => $stats,
            'progression_ceintures' => $progressionCeintures,
            'evolution_presences' => $evolutionPresences,
            'taux_occupation' => $tauxOccupation,
            'taux_presence' => $tauxPresence,
            'role' => 'admin'
        ]);
    }

    private function dashboardInstructeur(array $stats, $user): \Inertia\Response
    {
        // Cours de l'instructeur
        $mesCours = Cours::where('instructeur_id', $user->id)
            ->where('actif', true)
            ->with(['membres' => function($query) {
                $query->where('cours_membres.statut', 'actif');
            }])
            ->get();

        // Présences du jour pour mes cours
        $presencesAujourdhui = Presence::whereHas('cours', function($query) use ($user) {
                $query->where('instructeur_id', $user->id);
            })
            ->whereDate('date_cours', today())
            ->with(['membre', 'cours'])
            ->get();

        // Examens à planifier
        $examensAPlanifier = Membre::whereHas('cours', function($query) use ($user) {
                $query->where('instructeur_id', $user->id);
            })
            ->where('statut', 'actif')
            ->get()
            ->filter(function($membre) {
                return $membre->peutProgresserCeinture();
            });

        return Inertia::render('Dashboard', [
            'stats' => array_merge($stats, [
                'mes_cours' => $mesCours->count(),
                'mes_eleves' => $mesCours->sum(function($cours) {
                    return $cours->membres->count();
                }),
            ]),
            'mes_cours' => $mesCours,
            'presences_aujourd_hui' => $presencesAujourdhui,
            'examens_a_planifier' => $examensAPlanifier,
            'role' => 'instructeur'
        ]);
    }

    private function dashboardMembre(array $stats, $user): \Inertia\Response
    {
        $membre = Membre::where('user_id', $user->id)->first();
        
        if (!$membre) {
            return Inertia::render('Dashboard', [
                'stats' => $stats,
                'role' => 'membre',
                'message' => 'Profil membre non trouvé. Contactez l\'administration.'
            ]);
        }

        // Mes cours
        $mesCours = $membre->cours()
            ->where('cours_membres.statut', 'actif')
            ->where('cours.actif', true)
            ->get();

        // Mes présences récentes
        $mesPresences = $membre->presences()
            ->with('cours')
            ->orderByDesc('date_cours')
            ->take(10)
            ->get();

        // Mes paiements
        $mesPaiements = $membre->paiements()
            ->orderByDesc('date_echeance')
            ->take(5)
            ->get();

        // Progression ceinture
        $prochaineCeinture = $membre->prochaineCeinture();
        $peutProgresser = $membre->peutProgresserCeinture();

        return Inertia::render('Dashboard', [
            'stats' => array_merge($stats, [
                'mon_taux_presence' => $membre->calculerTauxPresence(),
                'mes_cours_count' => $mesCours->count(),
                'solde_du' => $membre->soldeEnCours(),
            ]),
            'membre' => $membre->load('ceintureActuelle'),
            'mes_cours' => $mesCours,
            'mes_presences' => $mesPresences,
            'mes_paiements' => $mesPaiements,
            'prochaine_ceinture' => $prochaineCeinture,
            'peut_progresser' => $peutProgresser,
            'role' => 'membre'
        ]);
    }

    public function metriquesTempsReel(Request $request)
    {
        $stats = $this->getStatisticsPrincipales();
        
        // Ajouter des métriques temps réel
        $stats['presences_en_cours'] = Presence::whereDate('date_cours', today())
            ->whereTime('created_at', '>=', now()->subHours(2))
            ->count();
            
        $stats['nouveaux_paiements'] = Paiement::where('statut', 'paye')
            ->whereDate('date_paiement', today())
            ->count();

        return response()->json($stats);
    }
}
