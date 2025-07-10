<?php
// Script pour vérifier l'ordre des migrations et les dépendances

$migrations_dir = 'database/migrations';
$migrations = [];

// Lire toutes les migrations
foreach (glob($migrations_dir . '/*.php') as $file) {
    $content = file_get_contents($file);
    $filename = basename($file);
    
    // Extraire les foreign keys
    preg_match_all('/foreign\([\'"](\w+)[\'"]\).*references\([\'"](\w+)[\'"]\).*on\([\'"](\w+)[\'"]\)/', $content, $matches);
    
    $foreign_keys = [];
    if (!empty($matches[0])) {
        for ($i = 0; $i < count($matches[0]); $i++) {
            $foreign_keys[] = [
                'column' => $matches[1][$i],
                'references' => $matches[2][$i],
                'on_table' => $matches[3][$i]
            ];
        }
    }
    
    // Extraire le nom de la table
    preg_match('/Schema::create\([\'"](\w+)[\'"]/', $content, $table_match);
    $table_name = $table_match[1] ?? 'unknown';
    
    $migrations[] = [
        'file' => $filename,
        'table' => $table_name,
        'foreign_keys' => $foreign_keys,
        'timestamp' => substr($filename, 0, 17)
    ];
}

// Trier par timestamp
usort($migrations, function($a, $b) {
    return strcmp($a['timestamp'], $b['timestamp']);
});

// Afficher l'ordre et vérifier les dépendances
echo "📋 ORDRE DES MIGRATIONS\n";
echo "======================\n\n";

$tables_created = [];
$errors = [];

foreach ($migrations as $i => $migration) {
    echo ($i + 1) . ". " . $migration['file'] . "\n";
    echo "   Table: " . $migration['table'] . "\n";
    
    if (!empty($migration['foreign_keys'])) {
        echo "   Foreign Keys:\n";
        foreach ($migration['foreign_keys'] as $fk) {
            echo "     - " . $fk['column'] . " -> " . $fk['on_table'] . "." . $fk['references'] . "\n";
            
            // Vérifier si la table référencée existe déjà
            if (!in_array($fk['on_table'], $tables_created)) {
                $errors[] = "❌ ERREUR: La table '{$fk['on_table']}' n'existe pas encore pour la foreign key dans " . $migration['file'];
            }
        }
    }
    
    $tables_created[] = $migration['table'];
    echo "\n";
}

if (!empty($errors)) {
    echo "\n⚠️  PROBLÈMES DÉTECTÉS:\n";
    foreach ($errors as $error) {
        echo $error . "\n";
    }
} else {
    echo "\n✅ Ordre des migrations correct!\n";
}
