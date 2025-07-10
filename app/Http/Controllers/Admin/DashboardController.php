<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Ecole;
use App\Models\Cours;
use App\Models\Ceinture;
use App\Models\Presence;
use App\Models\Paiement;
use App\Models\Seminaire;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DashboardController extends BaseAdminController
{
    /**
     * Afficher le dashboard principal avec gestion d'erreurs robuste
     */
    public function index(): View
    {
        try {
            // Vérification sécurité
            if (!Auth::check()) {
                abort(401, 'Authentification requise');
            }

            // Données avec gestion d'erreur pour chaque section
            $stats = $this->getMainStatsSecure();
            $chartData = $this->getChartDataSecure();
            $recentActivities = $this->getRecentActivitiesSecure();
            $alerts = $this->getAlertsSecure();

            // Log de l'accès au dashboard
            $this->logAdminAction('access_dashboard', 'dashboard', null, [
                'stats_loaded' => !empty($stats),
                'chart_data_loaded' => !empty($chartData),
                'user_role' => $this->getUserRole()
            ]);

            return view('admin.dashboard', [
                'stats' => $stats,
                'chartData' => $chartData,
                'recentActivities' => $recentActivities,
                'alerts' => $alerts,
                'user' => Auth::user(),
                'ecole' => Auth::user()->ecole
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur Dashboard', [
                'message' => $e->getMessage(),
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);

            // Dashboard de fallback en cas d'erreur
            return $this->getFallbackDashboard();
        }
    }

    /**
     * API Stats sécurisée
     */
    public function stats(): JsonResponse
    {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Non authentifié'
                ], 401);
            }

            $stats = $this->getMainStatsSecure();
            
            return response()->json([
                'success' => true,
                'stats' => $stats,
                'timestamp' => now()->toISOString(),
                'user_role' => $this->getUserRole()
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur API Stats', [
                'message' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du chargement des statistiques'
            ], 500);
        }
    }

    /**
     * Statistiques principales avec gestion d'erreurs robuste
     */
    private function getMainStatsSecure(): array
    {
        try {
            $user = Auth::user();
            $ecoleId = $user->ecole_id;
            $userRole = $this->getUserRole();

            // Cache par rôle et école
            $cacheKey = "dashboard_stats_{$userRole}_{$ecoleId}";
            
            return Cache::remember($cacheKey, 300, function () use ($user, $ecoleId, $userRole) {
                if ($userRole === 'superadmin') {
                    return $this->getSuperAdminStats();
                } else {
                    return $this->getEcoleStats($ecoleId);
                }
            });

        } catch (\Exception $e) {
            Log::warning('Erreur stats principales', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            return $this->getDefaultStats();
        }
    }

    /**
     * Stats SuperAdmin
     */
    private function getSuperAdminStats(): array
    {
        try {
            return [
                'total_users' => User::count(),
                'total_ecoles' => Ecole::where('active', 1)->count(),
                'total_cours' => Cours::count(),
                'total_ceintures' => Ceinture::count(),
                'cours_actifs' => Cours::where('statut', 'actif')->count(),
                'cours_termines' => Cours::where('statut', 'termine')->count(),
                'cours_archives' => Cours::where('statut', 'archive')->count(),
                'presences_mois' => $this->getPresencesMoisSecure(),
                'paiements_mois' => $this->getPaiementsMoisSecure(),
                'revenus_mois' => $this->getRevenusMoisSecure(),
                'user_role' => 'superadmin'
            ];
        } catch (\Exception $e) {
            Log::warning('Erreur stats superadmin', ['error' => $e->getMessage()]);
            return $this->getDefaultStats();
        }
    }

    /**
     * Stats École
     */
    private function getEcoleStats(int $ecoleId): array
    {
        try {
            return [
                'total_users' => User::where('ecole_id', $ecoleId)->count(),
                'total_cours' => Cours::where('ecole_id', $ecoleId)->count(),
                'total_ceintures' => Ceinture::where('ecole_id', $ecoleId)->count(),
                'cours_actifs' => Cours::where('ecole_id', $ecoleId)->where('statut', 'actif')->count(),
                'cours_termines' => Cours::where('ecole_id', $ecoleId)->where('statut', 'termine')->count(),
                'cours_archives' => Cours::where('ecole_id', $ecoleId)->where('statut', 'archive')->count(),
                'users_verified' => User::where('ecole_id', $ecoleId)->whereNotNull('email_verified_at')->count(),
                'presences_mois' => $this->getPresencesMoisSecure($ecoleId),
                'paiements_mois' => $this->getPaiementsMoisSecure($ecoleId),
                'revenus_mois' => $this->getRevenusMoisSecure($ecoleId),
                'user_role' => 'admin_ecole'
            ];
        } catch (\Exception $e) {
            Log::warning('Erreur stats école', [
                'error' => $e->getMessage(),
                'ecole_id' => $ecoleId
            ]);
            return $this->getDefaultStats();
        }
    }

    /**
     * Données graphiques sécurisées
     */
    private function getChartDataSecure(): array
    {
        try {
            $user = Auth::user();
            $ecoleId = $user->ecole_id;

            // Données d'inscriptions (30 derniers jours)
            $inscriptionsData = $this->getInscriptionsData($ecoleId);
            
            // Données de cours (30 derniers jours)
            $coursData = $this->getCoursData($ecoleId);

            return [
                'inscriptions' => $inscriptionsData,
                'cours' => $coursData,
                'labels' => [
                    'inscriptions' => array_keys($inscriptionsData),
                    'cours' => array_keys($coursData)
                ],
                'datasets' => [
                    [
                        'label' => 'Inscriptions',
                        'data' => array_values($inscriptionsData),
                        'borderColor' => 'rgb(59, 130, 246)',
                        'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                        'tension' => 0.4
                    ],
                    [
                        'label' => 'Cours',
                        'data' => array_values($coursData),
                        'borderColor' => 'rgb(139, 92, 246)',
                        'backgroundColor' => 'rgba(139, 92, 246, 0.1)',
                        'tension' => 0.4
                    ]
                ]
            ];

        } catch (\Exception $e) {
            Log::warning('Erreur données graphiques', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            return $this->getDefaultChartData();
        }
    }

    /**
     * Activités récentes sécurisées
     */
    private function getRecentActivitiesSecure(): array
    {
        try {
            $user = Auth::user();
            $ecoleId = $user->ecole_id;

            return [
                'users' => User::where('ecole_id', $ecoleId)
                    ->latest()
                    ->take(5)
                    ->select(['id', 'name', 'email', 'created_at'])
                    ->get(),
                'cours' => Cours::where('ecole_id', $ecoleId)
                    ->latest()
                    ->take(5)
                    ->select(['id', 'nom', 'statut', 'created_at'])
                    ->get(),
                'activities' => collect([]),
                'timestamp' => now()
            ];

        } catch (\Exception $e) {
            Log::warning('Erreur activités récentes', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            return [
                'users' => collect([]),
                'cours' => collect([]),
                'activities' => collect([]),
                'timestamp' => now()
            ];
        }
    }

    /**
     * Alertes sécurisées
     */
    private function getAlertsSecure(): array
    {
        try {
            $alerts = [];
            $user = Auth::user();
            $ecole = $user->ecole;

            // École inactive
            if ($ecole && !$ecole->active) {
                $alerts[] = [
                    'type' => 'warning',
                    'title' => 'École inactive',
                    'message' => 'Votre école est marquée comme inactive'
                ];
            }

            return $alerts;

        } catch (\Exception $e) {
            Log::warning('Erreur alertes', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            return [];
        }
    }

    /**
     * Méthodes utilitaires sécurisées
     */
    private function getPresencesMoisSecure(?int $ecoleId = null): int
    {
        try {
            $query = DB::table('presences')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year);
                
            if ($ecoleId) {
                $query->where('ecole_id', $ecoleId);
            }
            
            return $query->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getPaiementsMoisSecure(?int $ecoleId = null): int
    {
        try {
            $query = DB::table('paiements')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year);
                
            if ($ecoleId) {
                $query->where('ecole_id', $ecoleId);
            }
            
            return $query->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getRevenusMoisSecure(?int $ecoleId = null): float
    {
        try {
            $query = DB::table('paiements')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->where('statut', 'valide');
                
            if ($ecoleId) {
                $query->where('ecole_id', $ecoleId);
            }
            
            return (float) $query->sum('montant');
        } catch (\Exception $e) {
            return 0.0;
        }
    }

    private function getInscriptionsData(?int $ecoleId = null): array
    {
        try {
            $query = User::whereBetween('created_at', [now()->subDays(30), now()])
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->orderBy('date');
                
            if ($ecoleId) {
                $query->where('ecole_id', $ecoleId);
            }
            
            return $query->pluck('count', 'date')->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }

    private function getCoursData(?int $ecoleId = null): array
    {
        try {
            $query = Cours::whereBetween('created_at', [now()->subDays(30), now()])
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->orderBy('date');
                
            if ($ecoleId) {
                $query->where('ecole_id', $ecoleId);
            }
            
            return $query->pluck('count', 'date')->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Données par défaut en cas d'erreur
     */
    private function getDefaultStats(): array
    {
        return [
            'total_users' => 0,
            'total_cours' => 0,
            'total_ceintures' => 0,
            'cours_actifs' => 0,
            'cours_termines' => 0,
            'cours_archives' => 0,
            'presences_mois' => 0,
            'paiements_mois' => 0,
            'revenus_mois' => 0,
            'user_role' => 'membre'
        ];
    }

    private function getDefaultChartData(): array
    {
        return [
            'inscriptions' => [],
            'cours' => [],
            'labels' => ['inscriptions' => [], 'cours' => []],
            'datasets' => []
        ];
    }

    /**
     * Dashboard de fallback en cas d'erreur majeure
     */
    private function getFallbackDashboard(): View
    {
        return view('admin.dashboard', [
            'stats' => $this->getDefaultStats(),
            'chartData' => $this->getDefaultChartData(),
            'recentActivities' => [
                'users' => collect([]),
                'cours' => collect([]),
                'activities' => collect([])
            ],
            'alerts' => [[
                'type' => 'warning',
                'title' => 'Mode dégradé',
                'message' => 'Le système fonctionne en mode dégradé. Contactez l\'administrateur.'
            ]],
            'user' => Auth::user(),
            'ecole' => Auth::user()?->ecole
        ]);
    }

    /**
     * Obtenir le rôle de l'utilisateur
     */
    private function getUserRole(): string
    {
        try {
            return Auth::user()->roles->first()?->name ?? 'membre';
        } catch (\Exception $e) {
            return 'membre';
        }
    }
}
