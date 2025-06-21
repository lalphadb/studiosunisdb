#!/bin/bash

# ==============================================================================
# SCRIPT D'AUDIT COMPLET POUR PROJET LARAVEL - STUDIOSUNISDB
# Ce script génère une série de rapports dans le dossier /audit.
# ==============================================================================

# --- CONFIGURATION ---
PROJECT_PATH="/home/studiosdb/studiosunisdb"
AUDIT_PATH="$PROJECT_PATH/audit"

# !!! IMPORTANT : CONFIGUREZ VOS IDENTIFIANTS DE BASE DE DONNÉES ICI !!!
DB_USER="root"
DB_PASS="LkmP0km1" # Attention à la sécurité avec les mots de passe en clair
DB_NAME="studiosdb"

# --- DÉBUT DU SCRIPT ---
echo "========================================="
echo "DÉBUT DE L'AUDIT DU PROJET STUDIOSUNISDB"
echo "========================================="

# Création du répertoire d'audit
mkdir -p "$AUDIT_PATH"
echo "Répertoire d'audit créé : $AUDIT_PATH"

cd "$PROJECT_PATH" || exit

# 1. AUDIT DE L'ENVIRONNEMENT ET DES DÉPENDANCES
echo -n "1/8 - Audit de l'environnement et des dépendances..."
(
    echo "RAPPORT D'AUDIT - ENVIRONNEMENT & DÉPENDANCES"
    echo "Généré le : $(date)"
    echo "=================================================="
    echo "\n--- VERSIONS LOGICIELLES ---"
    php -v
    echo "\nMySQL Version: $(mysql --version)"
    echo "\n--- INFORMATIONS LARAVEL ---"
    php artisan --version
    php artisan about
    echo "\n--- DÉPENDANCES COMPOSER ---"
    composer show
) > "$AUDIT_PATH/01_environnement_et_dependances.txt"
echo " [OK]"

# 2. AUDIT DE LA BASE DE DONNÉES MYSQL
echo -n "2/8 - Audit du schéma de la base de données..."
SCHEMA_FILE="$AUDIT_PATH/02_schema_database.sql"
echo "RAPPORT D'AUDIT - SCHÉMA DE BASE DE DONNÉES" > "$SCHEMA_FILE"
echo "Généré le : $(date)" >> "$SCHEMA_FILE"
echo "==================================================" >> "$SCHEMA_FILE"
# Exporte la structure de chaque table
TABLES=$(mysql -u"$DB_USER" -p"$DB_PASS" "$DB_NAME" -e 'show tables' | grep -v Tables_in)
for table in $TABLES; do
    echo "\n-- Structure de la table : $table" >> "$SCHEMA_FILE"
    mysql -u"$DB_USER" -p"$DB_PASS" "$DB_NAME" -e "SHOW CREATE TABLE $table\G" | grep -v 'Create Table' >> "$SCHEMA_FILE"
done
echo " [OK]"

# 3. AUDIT DES ROUTES
echo -n "3/8 - Audit des routes de l'application..."
(
    echo "RAPPORT D'AUDIT - LISTE DES ROUTES"
    echo "Généré le : $(date)"
    echo "=================================================="
    php artisan route:list
) > "$AUDIT_PATH/03_routes.txt"
# Version JSON pour une analyse par l'IA
php artisan route:list --json > "$AUDIT_PATH/03_routes.json"
echo " [OK]"

# 4. AUDIT DE LA SÉCURITÉ (SPATIE)
echo -n "4/8 - Audit des rôles et permissions..."
(
    echo "RAPPORT D'AUDIT - RÔLES & PERMISSIONS (SPATIE)"
    echo "Généré le : $(date)"
    echo "=================================================="
    php artisan permission:show
) > "$AUDIT_PATH/04_securite_roles_permissions.txt"
echo " [OK]"

# 5. AUDIT DE L'ARBORESCENCE ET DES VUES
echo -n "5/8 - Audit de l'arborescence des fichiers..."
(
    echo "RAPPORT D'AUDIT - ARBORESCENCE DES FICHIERS"
    echo "Généré le : $(date)"
    echo "=================================================="
    echo "\n--- Arborescence de app/ ---"
    tree -L 3 app/
    echo "\n--- Arborescence de resources/views/ ---"
    tree -L 3 resources/views/
    echo "\n--- LISTE COMPLÈTE DES VUES BLADE ---"
    find resources/views -name "*.blade.php"
) > "$AUDIT_PATH/05_arborescence_et_vues.txt"
echo " [OK]"

# 6. ANALYSE STATIQUE DES MODÈLES ET RELATIONS
echo -n "6/8 - Analyse statique des Modèles et Relations Eloquent..."
(
    echo "RAPPORT D'AUDIT - MODÈLES & RELATIONS ELOQUENT"
    echo "Généré le : $(date)"
    echo "=================================================="
    echo "\n--- LISTE DES FICHIERS DE MODÈLES ---"
    find app/Models -name "*.php"
    echo "\n--- RELATIONS ELOQUENT DÉTECTÉES (grep) ---"
    grep -r -E "function (hasOne|hasMany|belongsTo|belongsToMany|morphTo|morphMany|morphToMany|morphedByMany)" app/Models/
) > "$AUDIT_PATH/06_modeles_et_relations.txt"
echo " [OK]"

# 7. AUDIT FONCTIONNEL (TESTS)
echo -n "7/8 - Lancement des tests automatisés..."
(
    echo "RAPPORT D'AUDIT - RÉSULTATS DES TESTS"
    echo "Généré le : $(date)"
    echo "=================================================="
    echo "NOTE : Ce rapport indique l'état des tests existants."
    echo "S'il n'y a pas de tests, il ne peut rien vérifier.\n"
    php artisan test --without-tty
) > "$AUDIT_PATH/07_resultats_tests.txt"
echo " [OK]"

# 8. ARCHIVAGE
echo -n "8/8 - Création de l'archive d'audit..."
tar -czf "$AUDIT_PATH/audit_studiosunisdb_$(date +%F).tar.gz" -C "$AUDIT_PATH" .
echo " [OK]"

echo "========================================="
echo "AUDIT TERMINÉ AVEC SUCCÈS !"
echo "Les rapports se trouvent dans : $AUDIT_PATH"
echo "Une archive a été créée : $AUDIT_PATH/audit_studiosunisdb_$(date +%F).tar.gz"
echo "========================================="
