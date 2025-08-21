#!/bin/bash

# =====================================================
# STUDIOSDB - DIAGNOSTIC ET FIX DES MIGRATIONS
# =====================================================

echo "╔══════════════════════════════════════════╗"
echo "║   DIAGNOSTIC DES MIGRATIONS               ║"
echo "╚══════════════════════════════════════════╝"
echo ""

cd /home/studiosdb/studiosunisdb

# 1. Vérifier l'état actuel des migrations
echo "📊 État actuel des migrations:"
php artisan migrate:status | grep -E "(ecole|index)" || true
echo ""

# 2. Vérifier si la table ecoles existe
echo "🔍 Vérification de la table ecoles:"
php artisan tinker --execute="
if (\Schema::hasTable('ecoles')) {
    echo '✅ Table ecoles existe déjà';
} else {
    echo '❌ Table ecoles n\'existe pas';
}"
echo ""

# 3. Vérifier les indexes sur membres
echo "🔍 Vérification des indexes sur membres:"
php artisan tinker --execute="
if (\Schema::hasTable('membres')) {
    \$sm = \Schema::getConnection()->getDoctrineSchemaManager();
    \$indexes = \$sm->listTableIndexes('membres');
    foreach (\$indexes as \$name => \$index) {
        echo '  - ' . \$name . PHP_EOL;
    }
} else {
    echo '❌ Table membres n\'existe pas';
}"
echo ""

# 4. Forcer l'exécution de la migration ecoles si nécessaire
echo "🔧 Tentative de création de la table ecoles..."
php artisan migrate --path=database/migrations/2025_08_21_000001_create_ecoles_table.php --force 2>&1 | grep -v "Nothing to migrate" || true

# 5. Forcer l'exécution de la migration ecole_id
echo "🔧 Ajout de ecole_id aux tables..."
php artisan migrate --path=database/migrations/2025_08_21_000002_add_ecole_id_to_remaining_tables.php --force 2>&1 | grep -v "Nothing to migrate" || true

# 6. Re-tenter toutes les migrations
echo ""
echo "🔄 Re-tentative de toutes les migrations pendantes..."
php artisan migrate --force

echo ""
echo "╔══════════════════════════════════════════╗"
echo "║   DIAGNOSTIC TERMINÉ                      ║"
echo "╚══════════════════════════════════════════╝"
