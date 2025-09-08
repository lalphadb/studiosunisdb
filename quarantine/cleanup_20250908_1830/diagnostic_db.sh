#!/bin/bash

# Script de diagnostic et correction de la base de données StudiosDB
# Version: 7.1.0
# Date: 2025-09-01

set -e  # Arrêter en cas d'erreur

# Couleurs pour l'affichage
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo -e "${BLUE}╔══════════════════════════════════════════════════════════╗${NC}"
echo -e "${BLUE}║     DIAGNOSTIC & CORRECTION BASE DE DONNÉES STUDIOSDB     ║${NC}"
echo -e "${BLUE}╚══════════════════════════════════════════════════════════╝${NC}"
echo ""

cd /home/studiosdb/studiosunisdb

# Fonction pour afficher une section
section() {
    echo ""
    echo -e "${YELLOW}▶ $1${NC}"
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
}

# Fonction pour vérifier le succès d'une commande
check_success() {
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}✓ $1${NC}"
    else
        echo -e "${RED}✗ $1${NC}"
        return 1
    fi
}

# 1. VÉRIFICATION PRÉLIMINAIRE
section "1. VÉRIFICATION PRÉLIMINAIRE"

echo "Vérification de la connexion à la base de données..."
php artisan db:show 2>/dev/null && check_success "Connexion DB OK" || {
    echo -e "${RED}✗ Connexion DB échouée${NC}"
    echo "Vérifiez vos paramètres dans .env"
    exit 1
}

# 2. ÉTAT DES MIGRATIONS
section "2. ÉTAT DES MIGRATIONS"

echo "Migrations en attente:"
PENDING_MIGRATIONS=$(php artisan migrate:status | grep "Pending" | wc -l)
if [ "$PENDING_MIGRATIONS" -gt 0 ]; then
    echo -e "${YELLOW}⚠ $PENDING_MIGRATIONS migration(s) en attente${NC}"
    php artisan migrate:status | grep "Pending"
else
    echo -e "${GREEN}✓ Toutes les migrations sont appliquées${NC}"
fi

echo ""
echo "Migrations liées à ecole_id:"
php artisan migrate:status | grep -E "(ecole|Ecole)" || echo "Aucune migration trouvée"

# 3. STRUCTURE DES TABLES
section "3. VÉRIFICATION STRUCTURE DES TABLES"

php artisan tinker --execute="
    \$checks = [
        'ecoles' => Schema::hasTable('ecoles'),
        'users.ecole_id' => Schema::hasColumn('users', 'ecole_id'),
        'membres.ecole_id' => Schema::hasColumn('membres', 'ecole_id'),
        'cours.ecole_id' => Schema::hasColumn('cours', 'ecole_id'),
        'paiements.ecole_id' => Schema::hasColumn('paiements', 'ecole_id'),
        'presences.ecole_id' => Schema::hasColumn('presences', 'ecole_id'),
        'factures.ecole_id' => Schema::hasColumn('factures', 'ecole_id'),
        'examens.ecole_id' => Schema::hasColumn('examens', 'ecole_id'),
    ];
    
    foreach (\$checks as \$item => \$exists) {
        if (\$exists) {
            echo \"✅ {\$item} OK\" . PHP_EOL;
        } else {
            echo \"❌ {\$item} MANQUANT\" . PHP_EOL;
        }
    }
" 2>/dev/null

# 4. APPLICATION DES MIGRATIONS SI NÉCESSAIRE
section "4. APPLICATION DES CORRECTIONS"

if [ "$PENDING_MIGRATIONS" -gt 0 ]; then
    echo -e "${YELLOW}Application des migrations en attente...${NC}"
    read -p "Voulez-vous appliquer les migrations? (o/N) " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Oo]$ ]]; then
        php artisan migrate --force
        check_success "Migrations appliquées"
    else
        echo -e "${YELLOW}⚠ Migrations ignorées${NC}"
    fi
else
    echo -e "${GREEN}✓ Aucune migration à appliquer${NC}"
fi

# 5. TEST D'INTÉGRITÉ
section "5. TEST D'INTÉGRITÉ COMPLET"

if [ -f "database/seeders/TestEcoleIntegritySeeder.php" ]; then
    echo "Exécution du test d'intégrité..."
    php artisan db:seed --class=TestEcoleIntegritySeeder --force
else
    echo -e "${RED}✗ TestEcoleIntegritySeeder manquant${NC}"
fi

# 6. VÉRIFICATION DES RÔLES
section "6. VÉRIFICATION DES RÔLES SPATIE"

php artisan tinker --execute="
    use Spatie\Permission\Models\Role;
    \$roles = Role::pluck('name')->toArray();
    \$expected = ['superadmin', 'admin', 'instructeur', 'membre'];
    \$obsolete = ['admin_ecole', 'super-admin', 'gestionnaire'];
    
    echo 'Rôles actuels: ' . implode(', ', \$roles) . PHP_EOL;
    
    foreach (\$expected as \$role) {
        if (!in_array(\$role, \$roles)) {
            echo \"❌ Rôle manquant: {\$role}\" . PHP_EOL;
        }
    }
    
    foreach (\$obsolete as \$role) {
        if (in_array(\$role, \$roles)) {
            echo \"⚠️ Rôle obsolète: {\$role}\" . PHP_EOL;
        }
    }
" 2>/dev/null

# 7. STATISTIQUES
section "7. STATISTIQUES DE LA BASE"

php artisan tinker --execute="
    \$stats = [];
    
    // Compter les enregistrements si les tables existent
    if (Schema::hasTable('ecoles')) {
        \$stats['Écoles'] = DB::table('ecoles')->count();
    }
    if (Schema::hasTable('users')) {
        \$stats['Utilisateurs'] = DB::table('users')->count();
    }
    if (Schema::hasTable('membres')) {
        \$stats['Membres'] = DB::table('membres')->count();
    }
    if (Schema::hasTable('cours')) {
        \$stats['Cours'] = DB::table('cours')->count();
    }
    if (Schema::hasTable('presences')) {
        \$stats['Présences'] = DB::table('presences')->count();
    }
    
    foreach (\$stats as \$table => \$count) {
        echo \"• {\$table}: {\$count}\" . PHP_EOL;
    }
" 2>/dev/null

# 8. RECOMMANDATIONS
section "8. RECOMMANDATIONS"

echo -e "${BLUE}Actions recommandées:${NC}"
echo ""

# Vérifier si des corrections sont nécessaires
NEEDS_FIX=false

# Vérifier table ecoles
php artisan tinker --execute="echo Schema::hasTable('ecoles') ? 'OK' : 'MISSING';" 2>/dev/null | grep -q "MISSING" && {
    echo -e "${RED}1. Créer la table 'ecoles':${NC}"
    echo "   php artisan migrate"
    NEEDS_FIX=true
}

# Vérifier ecole_id sur membres
php artisan tinker --execute="echo Schema::hasColumn('membres', 'ecole_id') ? 'OK' : 'MISSING';" 2>/dev/null | grep -q "MISSING" && {
    echo -e "${RED}2. Ajouter 'ecole_id' aux tables manquantes:${NC}"
    echo "   php artisan migrate"
    NEEDS_FIX=true
}

if [ "$NEEDS_FIX" = false ]; then
    echo -e "${GREEN}✅ Aucune action requise - Structure OK${NC}"
fi

echo ""
echo -e "${BLUE}╔══════════════════════════════════════════════════════════╗${NC}"
echo -e "${BLUE}║                    FIN DU DIAGNOSTIC                      ║${NC}"
echo -e "${BLUE}╚══════════════════════════════════════════════════════════╝${NC}"

# Proposer de sauvegarder le rapport
echo ""
read -p "Sauvegarder le rapport complet? (o/N) " -n 1 -r
echo
if [[ $REPLY =~ ^[Oo]$ ]]; then
    REPORT_FILE="/home/studiosdb/db_diagnostic_$(date +%Y%m%d_%H%M%S).log"
    {
        echo "RAPPORT DIAGNOSTIC STUDIOSDB - $(date)"
        echo "========================================"
        php artisan migrate:status
        echo ""
        php artisan about
    } > "$REPORT_FILE" 2>&1
    echo -e "${GREEN}✓ Rapport sauvegardé: $REPORT_FILE${NC}"
fi
