<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Ecole;
use App\Models\Cours;
use App\Models\Ceinture;
use App\Models\Presence;
use App\Models\Paiement;
use App\Models\Seminaire;
use App\Models\SessionCours;
use App\Models\InscriptionCours;
use App\Models\InscriptionSeminaire;
use App\Models\UserCeinture;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Spatie\Activitylog\Models\Activity;

class DashboardController extends BaseAdminController
{
    /**
     * Afficher le dashboard
     */
    public function index(Request $request): View
    {
        $this->checkPermission('dashboard.view');

        // Déterminer le type de dashboard à afficher
        if ($this->user->isSuperAdmin()) {
            return $this->superAdminDashboard($request);
        } elseif ($this->user->isAdminEcole()) {
            return $this->adminEcoleDashboard($request);
        } else {
            return $this->instructeurDashboard($request);
        }
    }

    /**
     * Dashboard SuperAdmin
     */
    private function superAdminDashboard(Request $request): View
    {
        // Utiliser le cache pour les statistiques lourdes
        $stats = Cache::remember('superadmin_dashboard_stats', 300, function () {
            return [
                'total_ecoles' => Ecole::count(),
                'ecoles_actives' => Ecole::where('actif', true)->count(),
                'total_users' => User::count(),
                'users_actifs' => User::where('actif', true)->count(),
                'total_cours' => Cours::count(),
                'total_seminaires' => Seminaire::count(),
                'revenue_total' => Paiement::where('statut', 'valide')->sum('montant'),
                'revenue_mois' => Paiement::where('statut', 'valide')
                    ->whereMonth('date_paiement', now()->month)
                    ->whereYear('date_paiement', now()->year)
                    ->sum('montant'),
            ];
        });

        // Top 10 écoles par revenus
        $topEcoles = Cache::remember('superadmin_top_ecoles', 600, function () {
            return Ecole::withCount(['users', 'cours'])
                ->with(['paiements' => function ($query) {
                    $query->where('statut', 'valide')
                        ->select('ecole_id', DB::raw('SUM(montant) as total_revenus'))
                        ->groupBy('ecole_id');
                }])
                ->actives()
                ->take(10)
                ->get();
        });

        // Graphique des inscriptions par mois (12 derniers mois)
        $inscriptionsParMois = $this->getInscriptionsParMois();

        // Graphique des revenus par mois (12 derniers mois)
        $revenusParMois = $this->getRevenusParMois();

        // Activité récente
        $activitesRecentes = Activity::with(['causer', 'subject'])
            ->latest()
            ->take(10)
            ->get();

        // Log de l'accès au dashboard
        $this->logAction('dashboard_superadmin_viewed');

        return view('admin.dashboard.superadmin', compact(
            'stats',
            'topEcoles',
            'inscriptionsParMois',
            'revenusParMois',
            'activitesRecentes'
        ));
    }

    /**
     * Dashboard Admin École
     */
    private function adminEcoleDashboard(Request $request): View
    {
        $ecoleId = $this->currentEcole->id;
        $cacheKey = "admin_ecole_dashboard_{$ecoleId}";

        // Utiliser le cache pour les statistiques
        $stats = Cache::remember($cacheKey . '_stats', 300, function () use ($ecoleId) {
            return [
                'total_membres' => User::forEcole($ecoleId)->role('membre')->count(),
                'membres_actifs' => User::forEcole($ecoleId)->role('membre')->actifs()->count(),
                'total_instructeurs' => User::forEcole($ecoleId)->role('instructeur')->count(),
                'total_cours' => Cours::forEcole($ecoleId)->count(),
                'cours_aujourdhui' => $this->getCoursAujourdhui($ecoleId),
                'presences_semaine' => $this->getPresencesSemaine($ecoleId),
                'revenue_mois' => Paiement::forEcole($ecoleId)
                    ->where('statut', 'valide')
                    ->whereMonth('date_paiement', now()->month)
                    ->whereYear('date_paiement', now()->year)
                    ->sum('montant'),
                'paiements_en_attente' => Paiement::forEcole($ecoleId)
                    ->where('statut', 'en_attente')
                    ->count(),
            ];
        });

        // Prochains cours
        $prochainsCours = $this->getProchainsCours($ecoleId, 5);

        // Derniers paiements
        $derniersPaiements = Paiement::forEcole($ecoleId)
            ->with(['user', 'validatedBy'])
            ->latest('created_at')
            ->take(10)
            ->get();

        // Membres récemment inscrits
        $nouveauxMembres = User::forEcole($ecoleId)
            ->role('membre')
            ->latest('created_at')
            ->take(5)
            ->get();

        // Graphique des présences (30 derniers jours)
        $presencesParJour = $this->getPresencesParJour($ecoleId, 30);

        // Taux de présence par cours
        $tauxPresenceParCours = $this->getTauxPresenceParCours($ecoleId);

        // Séminaires à venir
        $seminairesAVenir = Seminaire::forEcole($ecoleId)
            ->where('date_debut', '>', now())
            ->where('statut', 'planifie')
            ->orderBy('date_debut')
            ->take(3)
            ->get();

        // Log de l'accès au dashboard
        $this->logAction('dashboard_admin_ecole_viewed');

        return view('admin.dashboard.admin-ecole', compact(
            'stats',
            'prochainsCours',
            'derniersPaiements',
            'nouveauxMembres',
            'presencesParJour',
            'tauxPresenceParCours',
            'seminairesAVenir'
        ));
    }

    /**
     * Dashboard Instructeur
     */
    private function instructeurDashboard(Request $request): View
    {
        $userId = $this->user->id;
        $ecoleId = $this->currentEcole->id;

        // Cours de l'instructeur
        $mesCours = Cours::forEcole($ecoleId)
            ->whereHas('instructeurs', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->with('horaires')
            ->get();

        // Statistiques
        $stats = [
            'mes_cours' => $mesCours->count(),
            'total_eleves' => $this->getTotalElevesInstructeur($userId, $ecoleId),
            'cours_semaine' => $this->getCoursInstructeurSemaine($userId, $ecoleId),
            'presences_mois' => $this->getPresencesInstructeurMois($userId, $ecoleId),
        ];

        // Prochaines sessions
        $prochainesSessions = $this->getProchainesSessionsInstructeur($userId, $ecoleId, 10);

        // Élèves par cours
        $elevesParCours = [];
        foreach ($mesCours as $cours) {
            $elevesParCours[$cours->id] = [
                'cours' => $cours,
                'eleves' => $cours->inscriptions()->with('user')->get()
            ];
        }

        // Log de l'accès au dashboard
        $this->logAction('dashboard_instructeur_viewed');

        return view('admin.dashboard.instructeur', compact(
            'stats',
            'mesCours',
            'prochainesSessions',
            'elevesParCours'
        ));
    }

    /**
     * Obtenir les inscriptions par mois (12 derniers mois)
     */
    private function getInscriptionsParMois(): array
    {
        $data = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $count = User::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
            
            $data[] = [
                'mois' => $date->format('M Y'),
                'count' => $count
            ];
        }

        return $data;
    }

    /**
     * Obtenir les revenus par mois (12 derniers mois)
     */
    private function getRevenusParMois(): array
    {
        $data = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $montant = Paiement::where('statut', 'valide')
                ->whereMonth('date_paiement', $date->month)
                ->whereYear('date_paiement', $date->year)
                ->sum('montant');
            
            $data[] = [
                'mois' => $date->format('M Y'),
                'montant' => $montant
            ];
        }

        return $data;
    }

    /**
     * Obtenir les cours d'aujourd'hui
     */
    private function getCoursAujourdhui(int $ecoleId): int
    {
        return SessionCours::whereHas('cours', function ($query) use ($ecoleId) {
                $query->where('ecole_id', $ecoleId);
            })
            ->whereDate('date', today())
            ->where('statut', '!=', 'annule')
            ->count();
    }

    /**
     * Obtenir les présences de la semaine
     */
    private function getPresencesSemaine(int $ecoleId): int
    {
        return Presence::forEcole($ecoleId)
            ->whereHas('sessionCours', function ($query) {
                $query->whereBetween('date', [
                    now()->startOfWeek(),
                    now()->endOfWeek()
                ]);
            })
            ->where('status', 'present')
            ->count();
    }

    /**
     * Obtenir les prochains cours
     */
    private function getProchainsCours(int $ecoleId, int $limit = 5)
    {
        return SessionCours::with(['cours', 'instructeur'])
            ->whereHas('cours', function ($query) use ($ecoleId) {
                $query->where('ecole_id', $ecoleId);
            })
            ->where('date', '>=', now())
            ->where('statut', '!=', 'annule')
            ->orderBy('date')
            ->orderBy('heure_debut')
            ->limit($limit)
            ->get();
    }

    /**
     * Obtenir les présences par jour
     */
    private function getPresencesParJour(int $ecoleId, int $jours = 30): array
    {
        $data = [];
        
        for ($i = $jours - 1; $i >= 0; $i--) {
            $date = now()->subDays($i);
            
            $stats = Presence::forEcole($ecoleId)
                ->whereHas('sessionCours', function ($query) use ($date) {
                    $query->whereDate('date', $date);
                })
                ->selectRaw('
                    COUNT(CASE WHEN status = "present" THEN 1 END) as presents,
                    COUNT(CASE WHEN status = "absent" THEN 1 END) as absents,
                    COUNT(CASE WHEN status = "retard" THEN 1 END) as retards
                ')
                ->first();
            
            $data[] = [
                'date' => $date->format('d/m'),
                'presents' => $stats->presents ?? 0,
                'absents' => $stats->absents ?? 0,
                'retards' => $stats->retards ?? 0
            ];
        }

        return $data;
    }

    /**
     * Obtenir le taux de présence par cours
     */
    private function getTauxPresenceParCours(int $ecoleId)
    {
        return Cours::forEcole($ecoleId)
            ->where('actif', true)
            ->withCount([
                'presences as total_presences',
                'presences as presents' => function ($query) {
                    $query->where('status', 'present');
                }
            ])
            ->having('total_presences', '>', 0)
            ->get()
            ->map(function ($cours) {
                $cours->taux_presence = $cours->total_presences > 0
                    ? round(($cours->presents / $cours->total_presences) * 100, 2)
                    : 0;
                return $cours;
            })
            ->sortByDesc('taux_presence')
            ->take(10);
    }

    /**
     * Obtenir le total d'élèves pour un instructeur
     */
    private function getTotalElevesInstructeur(int $instructeurId, int $ecoleId): int
    {
        return InscriptionCours::whereHas('cours', function ($query) use ($instructeurId, $ecoleId) {
                $query->where('ecole_id', $ecoleId)
                    ->whereHas('instructeurs', function ($q) use ($instructeurId) {
                        $q->where('user_id', $instructeurId);
                    });
            })
            ->where('actif', true)
            ->distinct('user_id')
            ->count('user_id');
    }

    /**
     * Obtenir les cours de la semaine pour un instructeur
     */
    private function getCoursInstructeurSemaine(int $instructeurId, int $ecoleId): int
    {
        return SessionCours::where('instructeur_id', $instructeurId)
            ->whereHas('cours', function ($query) use ($ecoleId) {
                $query->where('ecole_id', $ecoleId);
            })
            ->whereBetween('date', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])
            ->where('statut', '!=', 'annule')
            ->count();
    }

    /**
     * Obtenir les présences du mois pour un instructeur
     */
    private function getPresencesInstructeurMois(int $instructeurId, int $ecoleId): int
    {
        return Presence::whereHas('sessionCours', function ($query) use ($instructeurId) {
                $query->where('instructeur_id', $instructeurId)
                    ->whereMonth('date', now()->month)
                    ->whereYear('date', now()->year);
            })
            ->where('ecole_id', $ecoleId)
            ->where('status', 'present')
            ->count();
    }

    /**
     * Obtenir les prochaines sessions pour un instructeur
     */
    private function getProchainesSessionsInstructeur(int $instructeurId, int $ecoleId, int $limit = 10)
    {
        return SessionCours::with('cours')
            ->where('instructeur_id', $instructeurId)
            ->whereHas('cours', function ($query) use ($ecoleId) {
                $query->where('ecole_id', $ecoleId);
            })
            ->where('date', '>=', now())
            ->where('statut', '!=', 'annule')
            ->orderBy('date')
            ->orderBy('heure_debut')
            ->limit($limit)
            ->get();
    }

    /**
     * Export des statistiques du dashboard
     */
    public function export(Request $request): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $this->checkPermission('dashboard.export');

        $type = $request->get('type', 'pdf');
        $periode = $request->get('periode', 'mois');

        $data = $this->prepareExportData($periode);

        $this->logAction('dashboard_exported', null, [
            'type' => $type,
            'periode' => $periode
        ]);

        if ($type === 'excel') {
            return $this->exportToExcel(
                new \App\Exports\DashboardExport($data),
                'dashboard-' . now()->format('Y-m-d') . '.xlsx'
            );
        }

        return $this->exportToPdf(
            'admin.dashboard.export-pdf',
            $data,
            'dashboard-' . now()->format('Y-m-d') . '.pdf'
        );
    }

    /**
     * Préparer les données pour l'export
     */
    private function prepareExportData(string $periode): array
    {
        $data = [
            'periode' => $periode,
            'ecole' => $this->currentEcole,
            'generated_at' => now(),
            'generated_by' => $this->user->nom_complet
        ];

        // Récupérer les stats selon la période
        switch ($periode) {
            case 'jour':
                $data['stats'] = $this->getStatsPourJour();
                break;
            case 'semaine':
                $data['stats'] = $this->getStatsPourSemaine();
                break;
            case 'mois':
                $data['stats'] = $this->getStatsPourMois();
                break;
            case 'annee':
                $data['stats'] = $this->getStatsPourAnnee();
                break;
            default:
                $data['stats'] = $this->getStatsPourMois();
        }

        return $data;
    }

    /**
     * Obtenir les statistiques pour un jour
     */
    private function getStatsPourJour(): array
    {
        $ecoleId = $this->currentEcole->id;
        
        return [
            'presences' => Presence::forEcole($ecoleId)
                ->whereHas('sessionCours', function ($query) {
                    $query->whereDate('date', today());
                })
                ->count(),
            'revenus' => Paiement::forEcole($ecoleId)
                ->where('statut', 'valide')
                ->whereDate('date_paiement', today())
                ->sum('montant'),
            'nouveaux_membres' => User::forEcole($ecoleId)
                ->whereDate('created_at', today())
                ->count(),
        ];
    }

    /**
     * Obtenir les statistiques pour une semaine
     */
    private function getStatsPourSemaine(): array
    {
        $ecoleId = $this->currentEcole->id;
        
        return [
            'presences' => Presence::forEcole($ecoleId)
                ->whereHas('sessionCours', function ($query) {
                    $query->whereBetween('date', [
                        now()->startOfWeek(),
                        now()->endOfWeek()
                    ]);
                })
                ->count(),
            'revenus' => Paiement::forEcole($ecoleId)
                ->where('statut', 'valide')
                ->whereBetween('date_paiement', [
                    now()->startOfWeek(),
                    now()->endOfWeek()
                ])
                ->sum('montant'),
            'nouveaux_membres' => User::forEcole($ecoleId)
                ->whereBetween('created_at', [
                    now()->startOfWeek(),
                    now()->endOfWeek()
                ])
                ->count(),
        ];
    }

    /**
     * Obtenir les statistiques pour un mois
     */
    private function getStatsPourMois(): array
    {
        $ecoleId = $this->currentEcole->id;
        
        return [
            'presences' => Presence::forEcole($ecoleId)
                ->whereHas('sessionCours', function ($query) {
                    $query->whereMonth('date', now()->month)
                        ->whereYear('date', now()->year);
                })
                ->count(),
            'revenus' => Paiement::forEcole($ecoleId)
                ->where('statut', 'valide')
                ->whereMonth('date_paiement', now()->month)
                ->whereYear('date_paiement', now()->year)
                ->sum('montant'),
            'nouveaux_membres' => User::forEcole($ecoleId)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];
    }

    /**
     * Obtenir les statistiques pour une année
     */
    private function getStatsPourAnnee(): array
    {
        $ecoleId = $this->currentEcole->id;
        
        return [
            'presences' => Presence::forEcole($ecoleId)
                ->whereHas('sessionCours', function ($query) {
                    $query->whereYear('date', now()->year);
                })
                ->count(),
            'revenus' => Paiement::forEcole($ecoleId)
                ->where('statut', 'valide')
                ->whereYear('date_paiement', now()->year)
                ->sum('montant'),
            'nouveaux_membres' => User::forEcole($ecoleId)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];
    }

    /**
     * API endpoint pour rafraîchir les stats en temps réel
     */
    public function refreshStats(Request $request): JsonResponse
    {
        $this->checkPermission('dashboard.view');

        $type = $request->get('type', 'general');
        $ecoleId = $this->currentEcole->id;

        // Invalider le cache
        Cache::forget("admin_ecole_dashboard_{$ecoleId}_stats");

        $stats = [];

        switch ($type) {
            case 'presences':
                $stats = [
                    'presences_jour' => $this->getPresencesParJour($ecoleId, 1)[0],
                    'taux_presence' => $this->getTauxPresenceParCours($ecoleId)
                ];
                break;
            case 'paiements':
                $stats = [
                    'revenue_jour' => Paiement::forEcole($ecoleId)
                        ->where('statut', 'valide')
                        ->whereDate('date_paiement', today())
                        ->sum('montant'),
                    'paiements_en_attente' => Paiement::forEcole($ecoleId)
                        ->where('statut', 'en_attente')
                        ->count()
                ];
                break;
            default:
                $stats = $this->getStatsForDashboard($ecoleId);
        }

        return $this->jsonResponse($stats, 'Statistiques rafraîchies');
    }

    /**
     * Obtenir les stats principales pour le dashboard
     */
    private function getStatsForDashboard(int $ecoleId): array
    {
        return [
            'membres_actifs' => User::forEcole($ecoleId)->role('membre')->actifs()->count(),
            'cours_aujourdhui' => $this->getCoursAujourdhui($ecoleId),
            'presences_semaine' => $this->getPresencesSemaine($ecoleId),
            'revenue_mois' => Paiement::forEcole($ecoleId)
                ->where('statut', 'valide')
                ->whereMonth('date_paiement', now()->month)
                ->whereYear('date_paiement', now()->year)
                ->sum('montant')
        ];
    }
}
