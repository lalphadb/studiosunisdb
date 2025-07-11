#!/bin/bash
set -e

echo "🚀 Configuration propre de StudiosDB..."
echo "======================================"

# 1. Nettoyer complètement
echo "🗑️  Étape 1: Nettoyage complet..."
php artisan db:wipe --force
rm -f database/migrations/2025_*

# 2. S'assurer que la migration des permissions est en premier
echo "📝 Étape 2: Organisation des migrations..."
if [ -f database/migrations/2024_01_01_000000_create_permission_tables.php ]; then
    echo "✓ Migration des permissions déjà en place"
else
    echo "⚠️  Migration des permissions non trouvée!"
    exit 1
fi

# 3. Créer toutes nos migrations personnalisées
echo "📄 Étape 3: Création des migrations StudiosDB..."
./create_all_migrations.sh

# 4. Afficher l'ordre des migrations
echo -e "\n📋 Ordre d'exécution des migrations:"
ls -1 database/migrations/ | sort

# 5. Exécuter les migrations
echo -e "\n🏗️  Étape 4: Exécution des migrations..."
php artisan migrate --force

# 6. Afficher les tables créées
echo -e "\n📊 Tables créées:"
php artisan db:show --counts

echo -e "\n✅ Base de données configurée avec succès!"
