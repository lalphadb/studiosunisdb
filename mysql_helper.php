#!/usr/bin/env php
<?php
/**
 * MySQL Helper Tool pour StudiosDB
 * Permet la gestion de la base de données via MCP
 */

// Configuration
$config = [
    'host' => '127.0.0.1',
    'port' => 3306,
    'username' => 'root',
    'password' => 'LkmP0km1',
    'database' => 'studiosdb'
];

// Récupération de l'action
$action = $argv[1] ?? 'help';

try {
    $pdo = new PDO(
        "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']};charset=utf8mb4",
        $config['username'],
        $config['password'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );

    switch ($action) {
        case 'tables':
            // Lister toutes les tables
            $stmt = $pdo->query("SHOW TABLES");
            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
            echo "📊 Tables dans la base de données:\n";
            foreach ($tables as $table) {
                $count = $pdo->query("SELECT COUNT(*) FROM `$table`")->fetchColumn();
                echo "  • $table ($count lignes)\n";
            }
            break;

        case 'backup':
            // Créer une sauvegarde SQL
            $backupFile = $argv[2] ?? 'backup_' . date('Y-m-d_His') . '.sql';
            $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
            
            $output = "-- StudiosDB Backup " . date('Y-m-d H:i:s') . "\n\n";
            $output .= "SET FOREIGN_KEY_CHECKS=0;\n\n";
            
            foreach ($tables as $table) {
                // Structure de la table
                $createTable = $pdo->query("SHOW CREATE TABLE `$table`")->fetch();
                $output .= "DROP TABLE IF EXISTS `$table`;\n";
                $output .= $createTable['Create Table'] . ";\n\n";
                
                // Données de la table
                $rows = $pdo->query("SELECT * FROM `$table`")->fetchAll();
                if (!empty($rows)) {
                    $output .= "INSERT INTO `$table` VALUES\n";
                    $values = [];
                    foreach ($rows as $row) {
                        $rowValues = array_map(function($value) use ($pdo) {
                            return $value === null ? 'NULL' : $pdo->quote($value);
                        }, $row);
                        $values[] = '(' . implode(',', $rowValues) . ')';
                    }
                    $output .= implode(",\n", $values) . ";\n\n";
                }
            }
            
            $output .= "SET FOREIGN_KEY_CHECKS=1;\n";
            file_put_contents($backupFile, $output);
            echo "✅ Backup créé: $backupFile\n";
            break;

        case 'help':
        default:
            echo "📚 MySQL Helper Tool - Commandes disponibles:\n\n";
            echo "  php mysql_helper.php tables              - Lister toutes les tables\n";
            echo "  php mysql_helper.php backup [fichier]    - Créer une sauvegarde\n";
            break;
    }

} catch (PDOException $e) {
    echo "❌ Erreur de connexion: " . $e->getMessage() . "\n";
    exit(1);
}
