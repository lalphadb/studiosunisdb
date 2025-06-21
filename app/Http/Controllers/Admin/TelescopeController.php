<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class TelescopeController extends Controller
{
    public static function middleware(): array
    {
        return [
            'auth',
            new \Illuminate\Routing\Middleware\ThrottleRequests('60,1'),
        ];
    }

    /**
     * Obtenir les statistiques en temps réel
     */
    public function stats(): JsonResponse
    {
        try {
            // Vérifier que l'utilisateur est superadmin
            if (!auth()->user()?->hasRole('superadmin')) {
                return response()->json(['success' => false, 'message' => 'Accès non autorisé'], 403);
            }

            $since = now()->subDay(); // Dernières 24h
            
            $stats = [
                'success' => true,
                'exceptions_count' => $this->getExceptionsCount($since),
                'logs_count' => $this->getLogsCount($since),
                'slow_queries' => $this->getSlowQueriesCount($since),
                'failed_requests' => $this->getFailedRequestsCount($since),
            ];
            
            return response()->json($stats);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'exceptions_count' => 0,
                'logs_count' => 0,
                'slow_queries' => 0,
                'failed_requests' => 0,
            ]);
        }
    }

    /**
     * Nettoyer les données Telescope
     */
    public function clear(): JsonResponse
    {
        try {
            if (!auth()->user()?->hasRole('superadmin')) {
                return response()->json(['success' => false, 'message' => 'Accès non autorisé'], 403);
            }

            \Artisan::call('telescope:prune', ['--hours' => 48]);
            
            return response()->json([
                'success' => true,
                'message' => 'Données Telescope nettoyées (gardé 48h)'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getTelescopeStats(): array
    {
        try {
            $since = now()->subDay();
            
            return [
                'exceptions_count' => $this->getExceptionsCount($since),
                'logs_count' => $this->getLogsCount($since),
                'slow_queries' => $this->getSlowQueriesCount($since),
                'failed_requests' => $this->getFailedRequestsCount($since),
            ];
        } catch (\Exception $e) {
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
}
