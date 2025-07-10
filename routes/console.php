<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Routes - StudiosDB Enterprise v4.1.10.2
|--------------------------------------------------------------------------
*/

// Commande inspirationnelle
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Commandes de maintenance StudiosDB
Artisan::command('studiosdb:status', function () {
    $this->info('StudiosDB Enterprise v4.1.10.2');
    $this->info('Status: ' . (app()->isDownForMaintenance() ? 'Maintenance' : 'Active'));
    
    // Statistiques rapides
    $ecoles = \App\Models\Ecole::count();
    $users = \App\Models\User::count();
    
    $this->table(
        ['Metric', 'Count'],
        [
            ['Écoles', $ecoles],
            ['Utilisateurs', $users],
        ]
    );
})->purpose('Afficher le statut de StudiosDB');

Artisan::command('studiosdb:cleanup', function () {
    $this->info('Nettoyage des données temporaires...');
    
    // Nettoyer les logs anciens
    $logPath = storage_path('logs');
    $oldLogs = glob($logPath . '/laravel-*.log');
    $cleaned = 0;
    
    foreach ($oldLogs as $log) {
        if (filemtime($log) < strtotime('-30 days')) {
            unlink($log);
            $cleaned++;
        }
    }
    
    $this->info("✅ $cleaned fichiers de logs nettoyés");
    
    // Nettoyer le cache
    Artisan::call('cache:clear');
    $this->info('✅ Cache nettoyé');
    
    $this->info('Nettoyage terminé!');
})->purpose('Nettoyer les données temporaires');

// Commande de backup
Artisan::command('studiosdb:backup', function () {
    $backupPath = storage_path('backups');
    if (!is_dir($backupPath)) {
        mkdir($backupPath, 0755, true);
    }
    
    $filename = 'studiosdb-backup-' . date('Y-m-d-H-i-s') . '.sql';
    $filepath = $backupPath . '/' . $filename;
    
    $database = config('database.connections.mysql.database');
    $username = config('database.connections.mysql.username');
    $password = config('database.connections.mysql.password');
    $host = config('database.connections.mysql.host');
    
    $command = "mysqldump -h {$host} -u {$username} -p{$password} {$database} > {$filepath}";
    
    $this->info('Création du backup...');
    exec($command, $output, $return);
    
    if ($return === 0) {
        $this->info("✅ Backup créé: {$filename}");
    } else {
        $this->error('❌ Erreur lors de la création du backup');
    }
})->purpose('Créer un backup de la base de données');
