#!/bin/bash

echo "=== CORRECTION SOFT DELETES (Compatible MySQL) ==="
echo

# 1. Créer un script PHP pour ajouter les colonnes de manière sûre
cat > fix_soft_deletes.php << 'PHP'
<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

echo "🔧 Ajout des colonnes deleted_at...\n";

$tables = [
    'cours',
    'session_cours',
    'presences',
    'inscriptions_cours',
    'paiements',
    'seminaires',
    'inscriptions_seminaires'
];

foreach ($tables as $table) {
    if (Schema::hasTable($table)) {
        if (!Schema::hasColumn($table, 'deleted_at')) {
            Schema::table($table, function (Blueprint $table) {
                $table->softDeletes();
            });
            echo "✓ Ajouté deleted_at à la table: $table\n";
        } else {
            echo "→ La table $table a déjà deleted_at\n";
        }
    } else {
        echo "⚠️  Table $table non trouvée\n";
    }
}

echo "\n✅ Colonnes deleted_at ajoutées avec succès!\n";

// Vérifier le résultat
echo "\n📊 Vérification des données:\n";
try {
    echo "Écoles: " . \App\Models\Ecole::count() . "\n";
    echo "Utilisateurs: " . \App\Models\User::count() . "\n";
    echo "Cours actifs: " . \App\Models\Cours::count() . "\n";
    echo "Cours supprimés: " . \App\Models\Cours::onlyTrashed()->count() . "\n";
    echo "Ceintures: " . \App\Models\Ceinture::count() . "\n";
} catch (Exception $e) {
    echo "Erreur lors de la vérification: " . $e->getMessage() . "\n";
}
PHP

# 2. Exécuter le script PHP
echo "Exécution du script de correction..."
php fix_soft_deletes.php

# 3. Supprimer le script temporaire
rm -f fix_soft_deletes.php

# 4. Vérifier dans MySQL
echo -e "\n🔍 Vérification dans MySQL..."
mysql -u root -p studiosdb -e "
SELECT TABLE_NAME, COLUMN_NAME 
FROM information_schema.COLUMNS 
WHERE TABLE_SCHEMA = 'studiosdb' 
AND COLUMN_NAME = 'deleted_at'
ORDER BY TABLE_NAME;" 2>/dev/null || echo "Entrez le mot de passe MySQL si demandé"

echo -e "\n✅ Correction terminée!"
