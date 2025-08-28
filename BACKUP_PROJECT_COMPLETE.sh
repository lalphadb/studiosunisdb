#!/bin/bash
echo "=== SAUVEGARDE COMPLÈTE PROJET STUDIOSDB ==="
cd /home/studiosdb/studiosunisdb

TIMESTAMP=$(date +"%Y%m%d_%H%M%S")
BACKUP_DIR="backups/studiosdb_complete_${TIMESTAMP}"

echo "🚀 SAUVEGARDE PROJET COMPLET"
echo "📁 Destination : ${BACKUP_DIR}"
echo "🕐 Horodatage : ${TIMESTAMP}"

# Créer structure backup
mkdir -p "${BACKUP_DIR}"
mkdir -p "${BACKUP_DIR}/app"
mkdir -p "${BACKUP_DIR}/database"
mkdir -p "${BACKUP_DIR}/resources"
mkdir -p "${BACKUP_DIR}/config"
mkdir -p "${BACKUP_DIR}/routes"
mkdir -p "${BACKUP_DIR}/scripts"
mkdir -p "${BACKUP_DIR}/docs"

echo ""
echo "📋 SAUVEGARDE APPLICATION COMPLETE"

# Application Laravel complète
echo "- Controllers..."
cp -r app/Http/Controllers "${BACKUP_DIR}/app/" 2>/dev/null || echo "  Erreur Controllers"

echo "- Models..."
cp -r app/Models "${BACKUP_DIR}/app/" 2>/dev/null || echo "  Erreur Models"

echo "- Policies..."
cp -r app/Policies "${BACKUP_DIR}/app/" 2>/dev/null || echo "  Erreur Policies"

echo "- Requests..."
cp -r app/Http/Requests "${BACKUP_DIR}/app/" 2>/dev/null || echo "  Erreur Requests"

echo "- Resources..."
cp -r app/Http/Resources "${BACKUP_DIR}/app/" 2>/dev/null || echo "  Erreur Resources"

echo "- Middlewares..."
cp -r app/Http/Middleware "${BACKUP_DIR}/app/" 2>/dev/null || echo "  Erreur Middlewares"

# Base de données
echo "- Migrations..."
cp -r database/migrations "${BACKUP_DIR}/database/" 2>/dev/null || echo "  Erreur Migrations"

echo "- Seeders..."
cp -r database/seeders "${BACKUP_DIR}/database/" 2>/dev/null || echo "  Erreur Seeders"

echo "- Factories..."
cp -r database/factories "${BACKUP_DIR}/database/" 2>/dev/null || echo "  Erreur Factories"

# Interface utilisateur
echo "- Pages Vue..."
cp -r resources/js/Pages "${BACKUP_DIR}/resources/" 2>/dev/null || echo "  Erreur Pages"

echo "- Components..."
cp -r resources/js/Components "${BACKUP_DIR}/resources/" 2>/dev/null || echo "  Erreur Components"

echo "- Layouts..."
cp -r resources/js/Layouts "${BACKUP_DIR}/resources/" 2>/dev/null || echo "  Erreur Layouts"

echo "- Views Blade..."
cp -r resources/views "${BACKUP_DIR}/resources/" 2>/dev/null || echo "  Erreur Views"

echo "- CSS/Assets..."
cp -r resources/css "${BACKUP_DIR}/resources/" 2>/dev/null || echo "  Erreur CSS"

# Configuration
echo "- Configuration..."
cp -r config "${BACKUP_DIR}/" 2>/dev/null || echo "  Erreur Config"

echo "- Routes..."
cp -r routes "${BACKUP_DIR}/" 2>/dev/null || echo "  Erreur Routes"

# Fichiers racine importants
echo "- Fichiers racine..."
cp composer.json "${BACKUP_DIR}/" 2>/dev/null
cp composer.lock "${BACKUP_DIR}/" 2>/dev/null
cp package.json "${BACKUP_DIR}/" 2>/dev/null
cp package-lock.json "${BACKUP_DIR}/" 2>/dev/null
cp artisan "${BACKUP_DIR}/" 2>/dev/null
cp .env.example "${BACKUP_DIR}/" 2>/dev/null
cp README*.md "${BACKUP_DIR}/" 2>/dev/null

# Scripts et documentation
echo "- Scripts..."
cp *.sh "${BACKUP_DIR}/scripts/" 2>/dev/null || echo "  Aucun script"

echo "- Documentation..."
cp -r docs "${BACKUP_DIR}/" 2>/dev/null || echo "  Aucune doc"

echo ""
echo "🔍 GÉNÉRATION ÉTAT SYSTÈME"

# État migrations
echo "=== ÉTAT MIGRATIONS ===" > "${BACKUP_DIR}/system_snapshot.txt"
php artisan migrate:status >> "${BACKUP_DIR}/system_snapshot.txt" 2>&1

# Structure DB complète
echo -e "\n=== STRUCTURE BASE DONNÉES ===" >> "${BACKUP_DIR}/system_snapshot.txt"
php artisan tinker --execute="
echo 'Tables existantes:' . PHP_EOL;
\$tables = DB::select('SHOW TABLES');
foreach (\$tables as \$table) {
    \$tableName = array_values((array)\$table)[0];
    echo '- ' . \$tableName . PHP_EOL;
    
    // Compter enregistrements
    try {
        \$count = DB::table(\$tableName)->count();
        echo '  Enregistrements: ' . \$count . PHP_EOL;
    } catch (Exception \$e) {
        echo '  Erreur count: ' . \$e->getMessage() . PHP_EOL;
    }
}
" >> "${BACKUP_DIR}/system_snapshot.txt" 2>&1

# État modules
echo -e "\n=== ÉTAT MODULES ===" >> "${BACKUP_DIR}/system_snapshot.txt"
echo "J1 Bootstrap sécurité : DONE" >> "${BACKUP_DIR}/system_snapshot.txt"
echo "J2 Dashboard (UI ref) : DONE" >> "${BACKUP_DIR}/system_snapshot.txt" 
echo "J3 Cours (fonct ref) : DONE (Corrections DB appliquées)" >> "${BACKUP_DIR}/system_snapshot.txt"
echo "J4 Utilisateurs      : TODO" >> "${BACKUP_DIR}/system_snapshot.txt"
echo "J5 Membres          : TODO" >> "${BACKUP_DIR}/system_snapshot.txt"
echo "J6 Inscription self : TODO" >> "${BACKUP_DIR}/system_snapshot.txt"

# Versions et environnement
echo -e "\n=== ENVIRONNEMENT ===" >> "${BACKUP_DIR}/system_snapshot.txt"
php artisan about >> "${BACKUP_DIR}/system_snapshot.txt" 2>&1

# Permissions et utilisateurs
echo -e "\n=== PERMISSIONS SPATIE ===" >> "${BACKUP_DIR}/system_snapshot.txt"
php artisan tinker --execute="
if (class_exists('Spatie\\\\Permission\\\\Models\\\\Role')) {
    echo 'Roles:' . PHP_EOL;
    \$roles = Spatie\\\\Permission\\\\Models\\\\Role::all();
    foreach (\$roles as \$role) {
        echo '- ' . \$role->name . PHP_EOL;
    }
    
    echo PHP_EOL . 'Permissions:' . PHP_EOL;
    \$permissions = Spatie\\\\Permission\\\\Models\\\\Permission::all();
    foreach (\$permissions as \$perm) {
        echo '- ' . \$perm->name . PHP_EOL;
    }
} else {
    echo 'Spatie Permission non configuré' . PHP_EOL;
}
" >> "${BACKUP_DIR}/system_snapshot.txt" 2>&1

echo ""
echo "📊 STATISTIQUES SAUVEGARDE"

# Compter fichiers sauvegardés
TOTAL_FILES=$(find "${BACKUP_DIR}" -type f | wc -l)
TOTAL_SIZE=$(du -sh "${BACKUP_DIR}" | cut -f1)

echo "📁 Fichiers sauvegardés : ${TOTAL_FILES}"
echo "💾 Taille totale        : ${TOTAL_SIZE}"

# Détail par catégorie
echo ""
echo "📋 DÉTAIL SAUVEGARDE :"
echo "- Controllers      : $(find "${BACKUP_DIR}/app/Controllers" -name "*.php" 2>/dev/null | wc -l) fichiers"
echo "- Models           : $(find "${BACKUP_DIR}/app/Models" -name "*.php" 2>/dev/null | wc -l) fichiers"
echo "- Migrations       : $(find "${BACKUP_DIR}/database/migrations" -name "*.php" 2>/dev/null | wc -l) fichiers"
echo "- Pages Vue        : $(find "${BACKUP_DIR}/resources/Pages" -name "*.vue" 2>/dev/null | wc -l) fichiers"
echo "- Scripts          : $(find "${BACKUP_DIR}/scripts" -name "*.sh" 2>/dev/null | wc -l) fichiers"

echo ""
echo "✅ SAUVEGARDE COMPLÈTE PROJET TERMINÉE"
echo "📁 Location: ${BACKUP_DIR}"
echo "📊 Snapshot: ${BACKUP_DIR}/system_snapshot.txt"
echo ""

# Retourner le chemin pour usage par d'autres scripts
echo "${BACKUP_DIR}"
