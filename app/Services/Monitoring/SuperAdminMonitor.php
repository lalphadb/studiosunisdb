<?php

namespace App\Services\Monitoring;

use App\Models\User;
use Illuminate\Support\Facades\{Log, Cache, Mail};
use Carbon\Carbon;

class SuperAdminMonitor
{
    private const CACHE_PREFIX = 'superadmin_monitor_';
    private const UNUSUAL_HOUR_START = 22;
    private const UNUSUAL_HOUR_END = 6;
    
    /**
     * Logger une action SuperAdmin avec détection d'anomalies
     */
    public function logAction(string $action, User $user, array $context = []): void
    {
        $logData = [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'action' => $action,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now()->toIso8601String(),
            'context' => $context,
        ];
        
        // Log standard
        Log::channel('superadmin')->info("SuperAdmin Action: {$action}", $logData);
        
        // Détection d'activité suspecte
        if ($this->isUnusualActivity($user, $action)) {
            $this->handleUnusualActivity($user, $action, $logData);
        }
        
        // Mise à jour des métriques
        $this->updateActivityMetrics($user);
    }
    
    /**
     * Détecter une activité inhabituelle
     */
    private function isUnusualActivity(User $user, string $action): bool
    {
        // Nouvelle IP
        if ($this->isNewIpAddress($user)) {
            return true;
        }
        
        // Heure inhabituelle
        if ($this->isUnusualHour()) {
            return true;
        }
        
        // Actions sensibles
        $sensitiveActions = [
            'users.destroy',
            'users.create_superadmin',
            'permissions.modify',
            'exports.all_data',
            'ecoles.delete',
        ];
        
        if (in_array($action, $sensitiveActions)) {
            return true;
        }
        
        // Trop d'actions récentes
        if ($this->hasTooManyRecentActions($user)) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Vérifier si c'est une nouvelle adresse IP
     */
    private function isNewIpAddress(User $user): bool
    {
        $knownIps = Cache::get(self::CACHE_PREFIX . "ips_{$user->id}", []);
        $currentIp = request()->ip();
        
        if (!in_array($currentIp, $knownIps)) {
            $knownIps[] = $currentIp;
            Cache::put(
                self::CACHE_PREFIX . "ips_{$user->id}", 
                array_slice($knownIps, -10),
                now()->addDays(30)
            );
            return true;
        }
        
        return false;
    }
    
    /**
     * Vérifier si c'est une heure inhabituelle
     */
    private function isUnusualHour(): bool
    {
        $hour = now()->hour;
        return $hour >= self::UNUSUAL_HOUR_START || $hour <= self::UNUSUAL_HOUR_END;
    }
    
    /**
     * Vérifier le nombre d'actions récentes
     */
    private function hasTooManyRecentActions(User $user): bool
    {
        $recentActions = Cache::get(self::CACHE_PREFIX . "actions_{$user->id}", 0);
        return $recentActions > config('studiosdb.monitoring.max_actions_per_hour', 50);
    }
    
    /**
     * Gérer une activité inhabituelle détectée
     */
    private function handleUnusualActivity(User $user, string $action, array $logData): void
    {
        // Log priorité haute
        Log::channel('superadmin')->warning('🚨 ACTIVITÉ INHABITUELLE DÉTECTÉE', $logData);
        
        // Email d'alerte immédiat
        if ($this->isHighRiskActivity($action)) {
            $this->sendCriticalAlert($user, $action, $logData);
        }
        
        // Enregistrer l'incident
        Cache::put(
            self::CACHE_PREFIX . "incidents_{$user->id}_" . now()->timestamp,
            $logData,
            now()->addDays(30)
        );
    }
    
    /**
     * Envoyer une alerte critique
     */
    private function sendCriticalAlert(User $user, string $action, array $data): void
    {
        $emailContent = view('emails.security-alert', [
            'user' => $user,
            'action' => $action,
            'data' => $data,
            'timestamp' => now()
        ])->render();
        
        Mail::raw($emailContent, function ($message) use ($action) {
            $message->to(config('studiosdb.monitoring.alert_email_critical'))
                    ->cc(config('studiosdb.monitoring.alert_email_superadmin'))
                    ->subject("[URGENT] Activité SuperAdmin Suspecte: {$action}")
                    ->priority(1);
        });
    }
    
    /**
     * Déterminer si c'est une activité à haut risque
     */
    private function isHighRiskActivity(string $action): bool
    {
        return in_array($action, [
            'users.mass_delete',
            'permissions.revoke_all',
            'exports.complete_database',
            'ecoles.delete',
            'system.configuration_change',
        ]);
    }
    
    /**
     * Mettre à jour les métriques d'activité
     */
    private function updateActivityMetrics(User $user): void
    {
        $key = self::CACHE_PREFIX . "actions_{$user->id}";
        $count = Cache::get($key, 0);
        Cache::put($key, $count + 1, now()->addHour());
    }
    
    /**
     * Obtenir un rapport d'activité pour un utilisateur
     */
    public function getActivityReport(User $user, int $days = 7): array
    {
        $startDate = now()->subDays($days);
        
        // Analyser les logs
        $logPath = storage_path('logs/superadmin.log');
        $logs = [];
        
        if (file_exists($logPath)) {
            // Parser les logs (implementation simplifiée)
            $content = file_get_contents($logPath);
            // ... parsing logic
        }
        
        return [
            'user' => $user->only(['id', 'name', 'email']),
            'period' => [
                'start' => $startDate->toDateString(),
                'end' => now()->toDateString(),
            ],
            'metrics' => [
                'total_actions' => count($logs),
                'unusual_activities' => 0, // À calculer
                'ips_used' => Cache::get(self::CACHE_PREFIX . "ips_{$user->id}", []),
                'peak_hours' => [], // À calculer
            ],
            'recent_incidents' => $this->getRecentIncidents($user),
        ];
    }
    
    /**
     * Obtenir les incidents récents
     */
    private function getRecentIncidents(User $user): array
    {
        $incidents = [];
        $pattern = self::CACHE_PREFIX . "incidents_{$user->id}_*";
        
        // Récupérer depuis le cache
        // Implementation dépend du driver de cache
        
        return array_slice($incidents, 0, 10);
    }
}
