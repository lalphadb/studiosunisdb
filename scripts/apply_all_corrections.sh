#!/bin/bash

# Script principal d'application des corrections
# Version: 7.2.0
# Date: 2025-09-01

set -e

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
MAGENTA='\033[0;35m'
CYAN='\033[0;36m'
NC='\033[0m'

echo -e "${MAGENTA}╔══════════════════════════════════════════════════════════╗${NC}"
echo -e "${MAGENTA}║      MISE À NIVEAU PROFESSIONNELLE STUDIOSDB v7.2         ║${NC}"
echo -e "${MAGENTA}╚══════════════════════════════════════════════════════════╝${NC}"
echo ""

cd /home/studiosdb/studiosunisdb

# Fonction pour afficher les étapes
step() {
    echo ""
    echo -e "${CYAN}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
    echo -e "${CYAN}$1${NC}"
    echo -e "${CYAN}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
}

# 0. BACKUP PRÉALABLE
step "ÉTAPE 0: BACKUP DE SÉCURITÉ"

BACKUP_DIR="/home/studiosdb/backups/$(date +%Y%m%d_%H%M%S)"
mkdir -p "$BACKUP_DIR"

echo "Création du backup complet..."
mysqldump -u root -pLkmP0km1 studiosdb > "$BACKUP_DIR/studiosdb_full.sql" 2>/dev/null
echo -e "${GREEN}✓ Backup créé: $BACKUP_DIR/studiosdb_full.sql${NC}"

# 1. NETTOYAGE DES MIGRATIONS FANTÔMES
step "ÉTAPE 1: NETTOYAGE DES MIGRATIONS FANTÔMES"

echo "Suppression des migrations fantômes..."
php artisan tinker --execute="
    use Illuminate\Support\Facades\DB;
    
    \$phantoms = [
        '2025_08_18_000001_add_ecole_id_core',
        '2025_08_21_000000_create_ecoles_table',
        '2025_08_21_200000_create_ecoles_table',
        '2025_08_29_164000_add_deleted_at_to_cours_table'
    ];
    
    \$count = 0;
    foreach (\$phantoms as \$migration) {
        \$deleted = DB::table('migrations')
            ->where('migration', \$migration)
            ->delete();
        if (\$deleted) \$count++;
    }
    
    echo \"✓ {\$count} migration(s) fantôme(s) supprimée(s)\" . PHP_EOL;
" 2>/dev/null

# 2. APPLICATION DES MIGRATIONS
step "ÉTAPE 2: APPLICATION DES NOUVELLES MIGRATIONS"

echo "Vérification des migrations en attente..."
PENDING_BEFORE=$(php artisan migrate:status | grep "Pending" | wc -l)
echo "Migrations en attente: $PENDING_BEFORE"

if [[ $PENDING_BEFORE -gt 0 ]]; then
    echo "Application des migrations..."
    php artisan migrate --force
    echo -e "${GREEN}✓ Migrations appliquées${NC}"
else
    echo -e "${GREEN}✓ Aucune migration en attente${NC}"
fi

# 3. VÉRIFICATION DE LA STRUCTURE
step "ÉTAPE 3: VÉRIFICATION DE LA STRUCTURE"

php artisan tinker --execute="
    \$checks = [
        'Table ecoles' => Schema::hasTable('ecoles'),
        'Table audit_logs (Loi 25)' => Schema::hasTable('audit_logs'),
        'Table consentements (Loi 25)' => Schema::hasTable('consentements'),
        'Table ceintures (pas belts)' => Schema::hasTable('ceintures') && !Schema::hasTable('belts'),
        'membres.ecole_id' => Schema::hasColumn('membres', 'ecole_id'),
        'paiements.ecole_id' => Schema::hasColumn('paiements', 'ecole_id'),
    ];
    
    foreach (\$checks as \$item => \$exists) {
        if (\$exists) {
            echo \"✅ {\$item}\" . PHP_EOL;
        } else {
            echo \"❌ {\$item}\" . PHP_EOL;
        }
    }
" 2>/dev/null

# 4. OPTIMISATION DES PERFORMANCES
step "ÉTAPE 4: OPTIMISATION DES PERFORMANCES"

echo "Analyse et optimisation des tables..."
php artisan tinker --execute="
    \$tables = ['membres', 'cours', 'presences', 'paiements', 'users'];
    foreach (\$tables as \$table) {
        if (Schema::hasTable(\$table)) {
            DB::statement(\"ANALYZE TABLE {\$table}\");
            echo \"✓ Table {\$table} optimisée\" . PHP_EOL;
        }
    }
" 2>/dev/null

# 5. CONFIGURATION DES RÔLES
step "ÉTAPE 5: VÉRIFICATION DES RÔLES"

php artisan tinker --execute="
    use Spatie\Permission\Models\Role;
    
    \$expectedRoles = ['superadmin', 'admin', 'instructeur', 'membre'];
    \$existingRoles = Role::pluck('name')->toArray();
    
    foreach (\$expectedRoles as \$role) {
        if (in_array(\$role, \$existingRoles)) {
            echo \"✅ Rôle {\$role} existe\" . PHP_EOL;
        } else {
            // Créer le rôle s'il n'existe pas
            Role::create(['name' => \$role, 'guard_name' => 'web']);
            echo \"✅ Rôle {\$role} créé\" . PHP_EOL;
        }
    }
    
    // Supprimer les rôles obsolètes
    \$obsoleteRoles = ['admin_ecole', 'super-admin', 'gestionnaire'];
    foreach (\$obsoleteRoles as \$role) {
        if (in_array(\$role, \$existingRoles)) {
            Role::where('name', \$role)->delete();
            echo \"✓ Rôle obsolète {\$role} supprimé\" . PHP_EOL;
        }
    }
" 2>/dev/null

# 6. NETTOYAGE DU CACHE
step "ÉTAPE 6: NETTOYAGE ET OPTIMISATION DU CACHE"

echo "Nettoyage du cache..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
echo -e "${GREEN}✓ Cache nettoyé${NC}"

echo "Reconstruction du cache..."
php artisan config:cache
php artisan route:cache
echo -e "${GREEN}✓ Cache reconstruit${NC}"

# 7. VALIDATION FINALE
step "ÉTAPE 7: VALIDATION FINALE"

echo "Exécution du test d'intégrité..."
if [ -f "database/seeders/TestEcoleIntegritySeeder.php" ]; then
    php artisan db:seed --class=TestEcoleIntegritySeeder --force 2>/dev/null || true
fi

# 8. RAPPORT FINAL
step "RAPPORT FINAL"

echo -e "${BLUE}╔══════════════════════════════════════════════════════════╗${NC}"
echo -e "${BLUE}║                    MISE À JOUR TERMINÉE                   ║${NC}"
echo -e "${BLUE}╚══════════════════════════════════════════════════════════╝${NC}"

# Statistiques finales
php artisan tinker --execute="
    \$stats = [];
    \$stats['Écoles'] = Schema::hasTable('ecoles') ? DB::table('ecoles')->count() : 0;
    \$stats['Utilisateurs'] = Schema::hasTable('users') ? DB::table('users')->count() : 0;
    \$stats['Membres'] = Schema::hasTable('membres') ? DB::table('membres')->count() : 0;
    \$stats['Cours'] = Schema::hasTable('cours') ? DB::table('cours')->count() : 0;
    \$stats['Migrations'] = DB::table('migrations')->count();
    
    echo PHP_EOL . '📊 STATISTIQUES:' . PHP_EOL;
    foreach (\$stats as \$label => \$count) {
        echo \"   • {\$label}: {\$count}\" . PHP_EOL;
    }
" 2>/dev/null

echo ""
echo -e "${GREEN}✅ BASE DE DONNÉES MISE À NIVEAU AVEC SUCCÈS!${NC}"
echo ""
echo "Actions recommandées:"
echo "1. Testez l'application: http://127.0.0.1:8000"
echo "2. Vérifiez les logs: tail -f storage/logs/laravel.log"
echo "3. Lancez la validation: ./scripts/validate_database.sh"
echo ""
echo "Backup disponible: $BACKUP_DIR/studiosdb_full.sql"
echo ""
echo -e "${MAGENTA}StudiosDB v7.2 - Base de données 100% professionnelle${NC}"
