#!/bin/bash

echo "=== CORRECTION RAPIDE SOFT DELETES ==="
echo

# 1. Ajouter deleted_at directement via SQL
echo "🔧 Ajout des colonnes deleted_at manquantes..."

mysql -u root -p studiosdb << 'SQL'
-- Ajouter deleted_at aux tables qui en ont besoin
ALTER TABLE cours ADD COLUMN IF NOT EXISTS deleted_at TIMESTAMP NULL DEFAULT NULL;
ALTER TABLE session_cours ADD COLUMN IF NOT EXISTS deleted_at TIMESTAMP NULL DEFAULT NULL;
ALTER TABLE presences ADD COLUMN IF NOT EXISTS deleted_at TIMESTAMP NULL DEFAULT NULL;
ALTER TABLE inscriptions_cours ADD COLUMN IF NOT EXISTS deleted_at TIMESTAMP NULL DEFAULT NULL;
ALTER TABLE paiements ADD COLUMN IF NOT EXISTS deleted_at TIMESTAMP NULL DEFAULT NULL;
ALTER TABLE seminaires ADD COLUMN IF NOT EXISTS deleted_at TIMESTAMP NULL DEFAULT NULL;
ALTER TABLE inscriptions_seminaires ADD COLUMN IF NOT EXISTS deleted_at TIMESTAMP NULL DEFAULT NULL;

-- Afficher les tables avec deleted_at
SELECT TABLE_NAME 
FROM information_schema.COLUMNS 
WHERE TABLE_SCHEMA = 'studiosdb' 
AND COLUMN_NAME = 'deleted_at'
ORDER BY TABLE_NAME;
SQL

echo -e "\n✅ Colonnes deleted_at ajoutées!"

# 2. Tester à nouveau
echo -e "\n📊 Test de comptage des cours..."
php artisan tinker --execute="echo 'Cours actifs: ' . App\Models\Cours::count();"
php artisan tinker --execute="echo 'Cours supprimés: ' . App\Models\Cours::onlyTrashed()->count();"

echo -e "\n✓ Correction appliquée! Vous pouvez maintenant continuer."
