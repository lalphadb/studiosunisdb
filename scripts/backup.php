<?php

// Script de backup MySQL pour StudiosDB
$timestamp = date('Ymd_His');
$backupDir = '/home/studiosdb/studiosunisdb/backups';
$dbName = 'studiosdb';
$backupFile = "{$backupDir}/{$dbName}_backup_{$timestamp}.sql";

// Créer le répertoire si nécessaire
if (!is_dir($backupDir)) {
    mkdir($backupDir, 0755, true);
}

// Charger les credentials depuis .env
$envFile = '/home/studiosdb/studiosunisdb/.env';
$env = parse_ini_file($envFile);

$username = $env['DB_USERNAME'] ?? 'root';
$password = $env['DB_PASSWORD'] ?? 'LkmP0km1';

echo "📦 Début du backup de la base de données StudiosDB...\n";
echo "📍 Fichier de destination: $backupFile\n";

// Commande mysqldump
$command = sprintf(
    'mysqldump -u%s -p%s --complete-insert --routines --triggers --single-transaction --skip-lock-tables --databases %s > %s 2>&1',
    escapeshellarg($username),
    escapeshellarg($password),
    escapeshellarg($dbName),
    escapeshellarg($backupFile)
);

// Exécuter le backup
exec($command, $output, $returnCode);

if ($returnCode === 0) {
    // Compresser le backup
    exec("gzip $backupFile");
    $compressedFile = "{$backupFile}.gz";
    
    if (file_exists($compressedFile)) {
        $size = round(filesize($compressedFile) / 1024 / 1024, 2);
        echo "✅ Backup réussi!\n";
        echo "📄 Fichier: $compressedFile\n";
        echo "📊 Taille: {$size} MB\n\n";
        
        // Lister les backups existants
        echo "📋 Backups disponibles:\n";
        $backups = glob("{$backupDir}/{$dbName}_backup_*.sql.gz");
        rsort($backups);
        
        foreach (array_slice($backups, 0, 5) as $backup) {
            $name = basename($backup);
            $size = round(filesize($backup) / 1024 / 1024, 2);
            $date = filemtime($backup);
            echo "  - $name ({$size} MB) - " . date('Y-m-d H:i:s', $date) . "\n";
        }
        
        // Nettoyer les vieux backups (garder les 10 derniers)
        if (count($backups) > 10) {
            foreach (array_slice($backups, 10) as $oldBackup) {
                unlink($oldBackup);
                echo "🗑️ Supprimé: " . basename($oldBackup) . "\n";
            }
        }
    }
} else {
    echo "❌ Erreur lors du backup!\n";
    echo "Output: " . implode("\n", $output) . "\n";
    exit(1);
}
