<?php

namespace App\Console\Commands;

use App\Http\Controllers\DashboardController;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route as RouteFacade;

/**
 * Commande Diagnostic Dashboard - StudiosDB v6 Pro
 * Laravel 11.x Ultra-Professionnel
 */
class DiagnosticDashboard extends Command
{
    protected $signature = 'studiosdb:diagnostic-dashboard';

    protected $description = 'Diagnostic complet du dashboard StudiosDB v6';

    public function handle(): int
    {
        $this->info('🎯 DIAGNOSTIC DASHBOARD STUDIOSDB V6 PRO');
        $this->newLine();

        // Test base de données
        $this->info('📊 Test Base de Données...');
        try {
            $dbName = DB::connection()->getDatabaseName();
            $userCount = DB::table('users')->count();
            $this->info("✅ Connexion DB: {$dbName} ({$userCount} utilisateurs)");
        } catch (\Exception $e) {
            $this->error('❌ Erreur DB: '.$e->getMessage());
        }

        // Test routes
        $this->info('🛣️ Test Routes...');
        $dashboardRoutes = collect(RouteFacade::getRoutes())->filter(function ($route) {
            return str_contains($route->uri(), 'dashboard');
        });

        $this->info('✅ Routes dashboard trouvées: '.$dashboardRoutes->count());

        // Test contrôleur
        $this->info('🎮 Test Contrôleur...');
        try {
            $controller = new DashboardController;
            $this->info('✅ DashboardController instancié');
        } catch (\Exception $e) {
            $this->error('❌ Erreur contrôleur: '.$e->getMessage());
        }

        // Recommandations
        $this->newLine();
        $this->info('🎯 RECOMMANDATIONS:');
        $this->line('1. Testez: http://studiosdb.local:8000/dashboard');
        $this->line('2. Vérifiez console navigateur (F12)');
        $this->line('3. Compilez assets: npm run build');

        return self::SUCCESS;
    }
}
