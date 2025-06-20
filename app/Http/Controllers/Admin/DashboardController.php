<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ecole;
use App\Models\Membre;
use App\Models\Cours;
use App\Models\Presence;
use App\Models\Paiement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public static function middleware(): array
    {
        return [
            'auth',
            new \Illuminate\Routing\Middleware\ThrottleRequests('60,1'),
        ];
    }

    public function index()
    {
        $user = auth()->user();
        $user_role = $this->getUserRole($user);
        
        // Stats selon le rôle
        $stats = [];
        $telescopeStats = [];
        $activite_recente = [];
        
        switch ($user_role) {
            case 'superadmin':
                $stats = $this->superadminStats();
                $telescopeStats = $this->getTelescopeStats();
                break;
            case 'admin':
                $stats = $this->adminStats($user);
                break;
            case 'instructeur':
                $stats = $this->instructeurStats($user);
                break;
            default:
                $stats = $this->membreStats($user);
                break;
        }

        // Activité récente pour tous
        $activite_recente = $this->getActiviteRecente($user, $user_role);

        // Variables pour les vues spécifiques
        $top_ecoles = collect();
        $revenus_ecoles = collect();
        $stats_utilisateurs = [];
        $membres_recents = collect();
        $cours_populaires = collect();
        $mes_cours_stats = collect();

        if ($user_role === 'superadmin') {
            $top_ecoles = $this->getTopEcoles();
            $revenus_ecoles = $this->getRevenusEcoles();
            $stats_utilisateurs = $this->getStatsUtilisateurs();
        } elseif ($user_role === 'admin') {
            $membres_recents = $this->getMembresRecents($user->ecole_id);
            $cours_populaires = $this->getCoursPopulaires($user->ecole_id);
        } elseif ($user_role === 'instructeur') {
            $mes_cours_stats = $this->getMesCoursStats($user->id);
        }

        return view('admin.dashboard', compact(
            'user',
            'user_role',
            'stats',
            'telescopeStats',
            'activite_recente',
            'top_ecoles',
            'revenus_ecoles',
            'stats_utilisateurs',
            'membres_recents',
            'cours_populaires',
            'mes_cours_stats'
        ));
    }

    private function getUserRole($user): string
    {
        if ($user->hasRole('superadmin')) return 'superadmin';
        if ($user->hasRole('admin')) return 'admin';
        if ($user->hasRole('instructeur')) return 'instructeur';
        return 'membre';
    }

    private function superadminStats(): array
    {
        return [
            'total_ecoles' => Ecole::count(),
            'ecoles_actives' => Ecole::where('active', true)->count(),
            'total_membres' => Membre::count(),
            'membres_actifs' => Membre::where('active', true)->count(),
            'total_cours' => Cours::count(),
            'cours_actifs' => Cours::where('active', true)->count(),
            'taux_presence_global' => $this->calculateTauxPresenceGlobal(),
        ];
    }

    private function adminStats($user): array
    {
        if (!$user->ecole_id) {
            return ['error' => 'Aucune école assignée à cet administrateur'];
        }

        $ecole = Ecole::find($user->ecole_id);
        $totalMembres = Membre::where('ecole_id', $user->ecole_id)->count();
        $capaciteMax = 500; // Par défaut

        return [
            'ecole_nom' => $ecole->nom ?? 'École inconnue',
            'ecole_ville' => $ecole->ville ?? '',
            'total_membres' => $totalMembres,
            'membres_actifs' => Membre::where('ecole_id', $user->ecole_id)->where('active', true)->count(),
            'total_cours' => Cours::where('ecole_id', $user->ecole_id)->count(),
            'cours_actifs' => Cours::where('ecole_id', $user->ecole_id)->where('active', true)->count(),
            'taux_presence' => $this->calculateTauxPresence($user->ecole_id),
            'revenus_mois' => $this->calculateRevenus($user->ecole_id),
            'capacite_max' => $capaciteMax,
            'capacite_utilisee' => round(($totalMembres / $capaciteMax) * 100, 1),
        ];
    }

    private function instructeurStats($user): array
    {
        $mesCours = Cours::where('instructeur_principal_id', $user->id)
            ->orWhere('instructeur_assistant_id', $user->id)
            ->get();

        return [
            'ecole_nom' => $user->ecole->nom ?? 'École inconnue',
            'mes_cours' => $mesCours->count(),
            'cours_actifs' => $mesCours->where('active', true)->count(),
            'total_eleves' => $this->getTotalEleves($mesCours->pluck('id')),
            'presences_aujourd_hui' => $this->getPresencesAujourdhui($mesCours->pluck('id')),
            'presences_semaine' => $this->getPresencesSemaine($mesCours->pluck('id')),
        ];
    }

    private function membreStats($user): array
    {
        return [
            'ecole_nom' => $user->ecole->nom ?? 'École inconnue',
            'email' => $user->email,
        ];
    }

    private function getTelescopeStats(): array
    {
        try {
            if (!config('telescope.enabled', false)) {
                return [
                    'exceptions_count' => 0,
                    'logs_count' => 0,
                    'slow_queries' => 0,
                    'failed_requests' => 0,
                ];
            }

            $since = now()->subDay();
            
            return [
                'exceptions_count' => $this->getExceptionsCount($since),
                'logs_count' => $this->getLogsCount($since),
                'slow_queries' => $this->getSlowQueriesCount($since),
                'failed_requests' => $this->getFailedRequestsCount($since),
            ];
        } catch (\Exception $e) {
            \Log::error('Erreur Telescope stats: ' . $e->getMessage());
            return [
                'exceptions_count' => 0,
                'logs_count' => 0,
                'slow_queries' => 0,
                'failed_requests' => 0,
            ];
        }
    }

    private function getExceptionsCount($since): int
    {
        try {
            return DB::table('telescope_entries')
                ->where('type', 'exception')
                ->where('created_at', '>=', $since)
                ->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getLogsCount($since): int
    {
        try {
            return DB::table('telescope_entries')
                ->where('type', 'log')
                ->whereRaw("JSON_EXTRACT(content, '$.level') = 'error'")
                ->where('created_at', '>=', $since)
                ->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getSlowQueriesCount($since): int
    {
        try {
            return DB::table('telescope_entries')
                ->where('type', 'query')
                ->whereRaw("CAST(JSON_EXTRACT(content, '$.time') AS DECIMAL) > 100")
                ->where('created_at', '>=', $since)
                ->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getFailedRequestsCount($since): int
    {
        try {
            return DB::table('telescope_entries')
                ->where('type', 'request')
                ->whereRaw("CAST(JSON_EXTRACT(content, '$.response_status') AS UNSIGNED) >= 400")
                ->where('created_at', '>=', $since)
                ->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getActiviteRecente($user, $user_role): array
    {
        // Retourner des activités récentes fictives pour le moment
        return [
            [
                'titre' => 'Système opérationnel',
                'description' => 'StudiosUnisDB fonctionne correctement',
                'date' => now()->format('d/m/Y H:i'),
                'icon' => '✅',
                'color' => 'bg-green-100',
            ],
        ];
    }

    private function getTopEcoles()
    {
        return Ecole::withCount('membres')
            ->orderBy('membres_count', 'desc')
            ->limit(5)
            ->get();
    }

    private function getRevenusEcoles()
    {
        return Ecole::withCount('membres')
            ->get()
            ->map(function ($ecole) {
                return [
                    'nom' => $ecole->nom,
                    'membres' => $ecole->membres_count,
                    'revenus' => $ecole->membres_count * 80, // Estimation
                ];
            })
            ->sortByDesc('revenus')
            ->take(5);
    }

    private function getStatsUtilisateurs(): array
    {
        return [
            'superadmins' => User::role('superadmin')->count(),
            'admins' => User::role('admin')->count(),
            'instructeurs' => User::role('instructeur')->count(),
            'membres_users' => User::role('membre')->count(),
        ];
    }

    private function getMembresRecents($ecoleId)
    {
        return Membre::where('ecole_id', $ecoleId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($membre) {
                $membre->statut = $membre->active ? 'actif' : 'inactif';
                return $membre;
            });
    }

    private function getCoursPopulaires($ecoleId)
    {
        return Cours::where('ecole_id', $ecoleId)
            ->withCount('inscriptions')
            ->orderBy('inscriptions_count', 'desc')
            ->limit(5)
            ->get();
    }

    private function getMesCoursStats($userId)
    {
        return Cours::where('instructeur_principal_id', $userId)
            ->orWhere('instructeur_assistant_id', $userId)
            ->withCount('inscriptions')
            ->get()
            ->map(function ($cours) {
                $taux = $cours->capacite_max > 0 ? 
                    round(($cours->inscriptions_count / $cours->capacite_max) * 100, 1) : 0;
                
                return [
                    'nom' => $cours->nom,
                    'statut' => $cours->active ? 'actif' : 'inactif',
                    'inscriptions' => $cours->inscriptions_count,
                    'capacite_max' => $cours->capacite_max,
                    'taux_remplissage' => $taux,
                ];
            });
    }

    private function calculateTauxPresenceGlobal(): int
    {
        return 85; // Valeur par défaut
    }

    private function calculateTauxPresence($ecoleId): int
    {
        return 82; // Valeur par défaut
    }

    private function calculateRevenus($ecoleId): int
    {
        $membres = Membre::where('ecole_id', $ecoleId)->count();
        return $membres * 80; // Estimation
    }

    private function getTotalEleves($coursIds): int
    {
        return DB::table('inscriptions_cours')
            ->whereIn('cours_id', $coursIds)
            ->distinct('membre_id')
            ->count();
    }

    private function getPresencesAujourdhui($coursIds): int
    {
        return Presence::whereIn('cours_id', $coursIds)
            ->whereDate('created_at', today())
            ->count();
    }

    private function getPresencesSemaine($coursIds): int
    {
        return Presence::whereIn('cours_id', $coursIds)
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();
    }
}
