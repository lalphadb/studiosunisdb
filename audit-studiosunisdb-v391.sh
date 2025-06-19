#!/bin/bash
# StudiosUnisDB v3.9.1 - Script d'Audit Complet
# Créé pour: 22 Studios Unis du Québec
# Version: 1.0.0-FINAL

# Configuration couleurs
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
PURPLE='\033[0;35m'
CYAN='\033[0;36m'
NC='\033[0m' # No Color

# Configuration système
LARAVEL_PATH="/home/studiosdb/studiosunisdb"
DB_NAME="studiosdb"
DB_USER="root"
DB_PASS="LkmP0km1"
DOMAIN="https://4lb.ca"
LOG_FILE="/tmp/studiosunisdb-audit-$(date +%Y%m%d_%H%M%S).log"

# Fonction d'affichage avec log
echo_log() {
    echo -e "$1" | tee -a "$LOG_FILE"
}

# Header audit
cat << 'EOF'
╔══════════════════════════════════════════════════════════════════════════════╗
║                    🥋 STUDIOSUNISDB v3.9.1 - AUDIT COMPLET                  ║
║                          22 Studios Unis du Québec                          ║
║                                                                              ║
║ Analyse système complète - Infrastructure à Application                      ║
╚══════════════════════════════════════════════════════════════════════════════╝
EOF

echo_log "${CYAN}📅 Audit démarré le: $(date)${NC}"
echo_log "${CYAN}📁 Chemin Laravel: $LARAVEL_PATH${NC}"
echo_log "${CYAN}📝 Log sauvegardé: $LOG_FILE${NC}\n"

# Variables compteurs
TOTAL_CHECKS=0
PASSED_CHECKS=0
FAILED_CHECKS=0
WARNING_CHECKS=0

# Fonction de vérification
check_status() {
    local test_name="$1"
    local command="$2"
    local expected="$3"
    local type="${4:-info}"
    
    TOTAL_CHECKS=$((TOTAL_CHECKS + 1))
    
    echo_log "${BLUE}🔍 Test: $test_name${NC}"
    
    if eval "$command" >/dev/null 2>&1; then
        if [ "$type" = "reverse" ]; then
            echo_log "${RED}❌ ÉCHEC: $test_name${NC}"
            FAILED_CHECKS=$((FAILED_CHECKS + 1))
            return 1
        else
            echo_log "${GREEN}✅ SUCCÈS: $test_name${NC}"
            PASSED_CHECKS=$((PASSED_CHECKS + 1))
            return 0
        fi
    else
        if [ "$type" = "reverse" ]; then
            echo_log "${GREEN}✅ SUCCÈS: $test_name${NC}"
            PASSED_CHECKS=$((PASSED_CHECKS + 1))
            return 0
        elif [ "$type" = "warning" ]; then
            echo_log "${YELLOW}⚠️  ATTENTION: $test_name${NC}"
            WARNING_CHECKS=$((WARNING_CHECKS + 1))
            return 2
        else
            echo_log "${RED}❌ ÉCHEC: $test_name${NC}"
            FAILED_CHECKS=$((FAILED_CHECKS + 1))
            return 1
        fi
    fi
}

# 1. AUDIT INFRASTRUCTURE
echo_log "\n${PURPLE}═══════════════════════════════════════════════════════════════${NC}"
echo_log "${PURPLE}                    🏗️  AUDIT INFRASTRUCTURE                    ${NC}"
echo_log "${PURPLE}═══════════════════════════════════════════════════════════════${NC}\n"

# Système d'exploitation
check_status "Ubuntu 24.04.2 LTS détecté" "lsb_release -d | grep -q '24.04'"
check_status "Utilisateur studiosdb actif" "id studiosdb"
check_status "Répertoire projet accessible" "[ -d '$LARAVEL_PATH' ]"

# PHP et extensions
check_status "PHP 8.3.6 installé" "php -v | grep -q '8.3'"
check_status "PHP-FPM actif" "systemctl is-active php8.3-fpm"
check_status "Extension MySQL PDO" "php -m | grep -q pdo_mysql"
check_status "Extension GD images" "php -m | grep -q gd"
check_status "Extension Mbstring" "php -m | grep -q mbstring"
check_status "Extension OpenSSL" "php -m | grep -q openssl"
check_status "Extension Zip" "php -m | grep -q zip"

# Base de données
check_status "MySQL 8.0.42 actif" "systemctl is-active mysql"
check_status "Base de données studiosdb existe" "mysql -u $DB_USER -p$DB_PASS -e 'USE $DB_NAME;'"

# Serveur web
check_status "Nginx actif" "systemctl is-active nginx"
check_status "Configuration Laravel Nginx" "[ -f /etc/nginx/sites-available/studiosunisdb ]"
check_status "Site activé" "[ -L /etc/nginx/sites-enabled/studiosunisdb ]"

# SSL et domaine
check_status "Certificat SSL Let's Encrypt" "[ -f /etc/letsencrypt/live/4lb.ca/fullchain.pem ]"
check_status "Domaine accessible HTTPS" "curl -s -o /dev/null -w '%{http_code}' $DOMAIN | grep -q '200'"

# 2. AUDIT LARAVEL
echo_log "\n${PURPLE}═══════════════════════════════════════════════════════════════${NC}"
echo_log "${PURPLE}                     🎯 AUDIT LARAVEL CORE                     ${NC}"
echo_log "${PURPLE}═══════════════════════════════════════════════════════════════${NC}\n"

cd "$LARAVEL_PATH" || exit 1

# Version et configuration
check_status "Laravel 12.18.0 détecté" "grep -q '12.18.0' composer.json"
check_status "Fichier .env présent" "[ -f .env ]"
check_status "APP_KEY configurée" "grep -q '^APP_KEY=' .env"
check_status "Mode production activé" "grep -q 'APP_ENV=production' .env"
check_status "Debug désactivé" "grep -q 'APP_DEBUG=false' .env"

# Composer et dépendances
check_status "Composer installé" "which composer"
check_status "Vendor directory présent" "[ -d vendor ]"
check_status "Autoload optimisé" "[ -f vendor/composer/autoload_classmap.php ]"

# Permissions fichiers
check_status "Storage writable" "[ -w storage ]"
check_status "Bootstrap/cache writable" "[ -w bootstrap/cache ]"
check_status "Logs accessibles" "[ -w storage/logs ]"

# Cache et optimisations
check_status "Config cache présent" "[ -f bootstrap/cache/config.php ]"
check_status "Routes cache présent" "[ -f bootstrap/cache/routes-v7.php ]"

# 3. AUDIT BASE DE DONNÉES
echo_log "\n${PURPLE}═══════════════════════════════════════════════════════════════${NC}"
echo_log "${PURPLE}                    🗄️  AUDIT BASE DE DONNÉES                   ${NC}"
echo_log "${PURPLE}═══════════════════════════════════════════════════════════════${NC}\n"

# Migrations critiques (ordre corrigé v3.8.3)
MIGRATIONS=(
    "0001_01_01_000000_create_users_table"
    "0001_01_01_000001_create_cache_table"
    "0001_01_01_000002_create_jobs_table"
    "2025_06_09_200100_create_ecoles_table"
    "2025_06_09_200200_create_ceintures_table"
    "2025_06_09_200300_add_fields_to_users_table"
    "2025_06_09_200400_create_membres_table"
    "2025_06_09_200500_create_cours_table"
    "2025_06_09_200600_add_missing_columns_to_cours_table"
    "2025_06_09_200700_add_description_to_ecoles_table"
    "2025_06_09_200800_create_cours_horaires_table"
    "2025_06_09_200900_create_membre_ceintures_table"
    "2025_06_09_201000_create_seminaires_table"
    "2025_06_09_201100_create_inscriptions_cours_table"
    "2025_06_09_201200_create_inscriptions_seminaires_table"
    "2025_06_09_201300_create_presences_table"
    "2025_06_09_201400_create_paiements_table"
    "2025_06_09_230000_create_permission_tables"
    "2025_06_09_230100_create_activity_log_table"
    "2025_06_09_230200_add_event_column_to_activity_log_table"
    "2025_06_09_230300_add_batch_uuid_column_to_activity_log_table"
)

echo_log "${CYAN}🔍 Vérification des 21 migrations critiques...${NC}"
for migration in "${MIGRATIONS[@]}"; do
    check_status "Migration $migration" "mysql -u $DB_USER -p$DB_PASS $DB_NAME -e 'SELECT * FROM migrations WHERE migration LIKE \"%$migration%\"' | grep -q '$migration'"
done

# Tables principales
TABLES=("users" "ecoles" "membres" "cours" "cours_horaires" "ceintures" "membre_ceintures" "seminaires" "inscriptions_cours" "inscriptions_seminaires" "presences" "paiements" "permissions" "activity_log")

echo_log "\n${CYAN}🔍 Vérification des 14 tables principales...${NC}"
for table in "${TABLES[@]}"; do
    check_status "Table $table existe" "mysql -u $DB_USER -p$DB_PASS $DB_NAME -e 'DESCRIBE $table'"
done

# Données essentielles
check_status "22 Écoles Studios Unis" "mysql -u $DB_USER -p$DB_PASS $DB_NAME -e 'SELECT COUNT(*) FROM ecoles' | tail -1 | grep -q '22'"
check_status "21 Ceintures configurées" "mysql -u $DB_USER -p$DB_PASS $DB_NAME -e 'SELECT COUNT(*) FROM ceintures' | tail -1 | grep -q '21'"
check_status "Utilisateurs SuperAdmin présents" "mysql -u $DB_USER -p$DB_PASS $DB_NAME -e 'SELECT COUNT(*) FROM users WHERE email=\"lalpha@4lb.ca\"' | tail -1 | grep -q '1'"

# 4. AUDIT PACKAGES LARAVEL
echo_log "\n${PURPLE}═══════════════════════════════════════════════════════════════${NC}"
echo_log "${PURPLE}                     📦 AUDIT PACKAGES LARAVEL                  ${NC}"
echo_log "${PURPLE}═══════════════════════════════════════════════════════════════${NC}\n"

# Packages essentiels
PACKAGES=(
    "laravel/breeze"
    "spatie/laravel-permission"
    "spatie/laravel-activitylog"
    "barryvdh/laravel-dompdf"
    "maatwebsite/excel"
    "intervention/image"
    "laravel/pint"
)

echo_log "${CYAN}🔍 Vérification des 7 packages critiques...${NC}"
for package in "${PACKAGES[@]}"; do
    check_status "Package $package installé" "composer show | grep -q '$package'"
done

# Configuration packages
check_status "Spatie Permission publié" "[ -f config/permission.php ]"
check_status "ActivityLog publié" "[ -f config/activitylog.php ]"
check_status "DomPDF publié" "[ -f config/dompdf.php ]"

# 5. AUDIT SÉCURITÉ
echo_log "\n${PURPLE}═══════════════════════════════════════════════════════════════${NC}"
echo_log "${PURPLE}                      🔒 AUDIT SÉCURITÉ                        ${NC}"
echo_log "${PURPLE}═══════════════════════════════════════════════════════════════${NC}\n"

# Spatie Permissions
check_status "58 permissions configurées" "mysql -u $DB_USER -p$DB_PASS $DB_NAME -e 'SELECT COUNT(*) FROM permissions' | tail -1 | grep -q '58'"
check_status "4 rôles actifs" "mysql -u $DB_USER -p$DB_PASS $DB_NAME -e 'SELECT COUNT(*) FROM roles' | tail -1 | grep -q '[4-9]'"

# Middleware et policies
check_status "Middleware EcoleRestriction" "[ -f app/Http/Middleware/EcoleRestrictionMiddleware.php ]"
check_status "Policy Ecole" "[ -f app/Policies/EcolePolicy.php ]"
check_status "Policy Membre" "[ -f app/Policies/MembrePolicy.php ]"
check_status "Policy Cours" "[ -f app/Policies/CoursPolicy.php ]"
check_status "Policy Presence" "[ -f app/Policies/PresencePolicy.php ]"
check_status "Policy Ceinture" "[ -f app/Policies/CeinturePolicy.php ]"

# Fichiers sensibles protégés
check_status ".env non accessible web" "curl -s -o /dev/null -w '%{http_code}' $DOMAIN/.env | grep -q '404'" "reverse"
check_status "Dossier storage protégé" "curl -s -o /dev/null -w '%{http_code}' $DOMAIN/storage | grep -q '404'" "reverse"

# 6. AUDIT MODULES APPLICATIFS
echo_log "\n${PURPLE}═══════════════════════════════════════════════════════════════${NC}"
echo_log "${PURPLE}                   🎯 AUDIT MODULES APPLICATIFS                ${NC}"
echo_log "${PURPLE}═══════════════════════════════════════════════════════════════${NC}\n"

# Modèles Eloquent
MODELS=("User" "Ecole" "Membre" "Cours" "CoursHoraire" "Presence" "Ceinture" "MembreCeinture" "Seminaire" "InscriptionSeminaire" "InscriptionCours" "Paiement")

echo_log "${CYAN}🔍 Vérification des 12 modèles Eloquent...${NC}"
for model in "${MODELS[@]}"; do
    check_status "Modèle $model.php" "[ -f app/Models/$model.php ]"
done

# Contrôleurs Admin
CONTROLLERS=("DashboardController" "EcoleController" "MembreController" "CoursController" "PresenceController" "CeintureController" "SeminaireController" "PaiementController")

echo_log "\n${CYAN}🔍 Vérification des 8 contrôleurs Admin...${NC}"
for controller in "${CONTROLLERS[@]}"; do
    check_status "Contrôleur $controller.php" "[ -f app/Http/Controllers/Admin/$controller.php ]"
done

# Routes
check_status "Routes web.php" "[ -f routes/web.php ]"
check_status "Routes admin.php" "[ -f routes/admin.php ]"
check_status "Routes auth.php" "[ -f routes/auth.php ]"

# 7. AUDIT INTERFACE UTILISATEUR
echo_log "\n${PURPLE}═══════════════════════════════════════════════════════════════${NC}"
echo_log "${PURPLE}                    🎨 AUDIT INTERFACE UTILISATEUR             ${NC}"
echo_log "${PURPLE}═══════════════════════════════════════════════════════════════${NC}\n"

# Tailwind CSS et assets
check_status "Tailwind CSS configuré" "[ -f tailwind.config.js ]"
check_status "Vite configuré" "[ -f vite.config.js ]"
check_status "CSS app.css présent" "[ -f resources/css/app.css ]"
check_status "JS app.js présent" "[ -f resources/js/app.js ]"
check_status "Assets compilés" "[ -d public/build ]"

# Layouts Blade
check_status "Layout admin.blade.php" "[ -f resources/views/layouts/admin.blade.php ]"
check_status "Layout guest.blade.php" "[ -f resources/views/layouts/guest.blade.php ]"
check_status "Navigation.blade.php" "[ -f resources/views/layouts/navigation.blade.php ]"

# Vues modules critiques (Cours corrigé v3.8.3)
COURS_VIEWS=("index" "create" "edit" "show")
echo_log "\n${CYAN}🔍 Module Cours - 4 vues Tailwind corrigées...${NC}"
for view in "${COURS_VIEWS[@]}"; do
    check_status "Vue cours/$view.blade.php" "[ -f resources/views/admin/cours/$view.blade.php ]"
done

# Autres modules
MODULES=("ecoles" "membres" "presences" "ceintures" "seminaires" "paiements")
for module in "${MODULES[@]}"; do
    check_status "Dossier vues $module/" "[ -d resources/views/admin/$module ]"
done

# 8. AUDIT FONCTIONNALITÉS MÉTIER
echo_log "\n${PURPLE}═══════════════════════════════════════════════════════════════${NC}"
echo_log "${PURPLE}                  💼 AUDIT FONCTIONNALITÉS MÉTIER              ${NC}"
echo_log "${PURPLE}═══════════════════════════════════════════════════════════════${NC}\n"

# Tests fonctionnels des pages critiques
check_status "Page d'accueil accessible" "curl -s -o /dev/null -w '%{http_code}' $DOMAIN | grep -q '200'"
check_status "Dashboard admin accessible" "curl -s -o /dev/null -w '%{http_code}' $DOMAIN/admin | grep -q '[23][0-9][0-9]'"
check_status "Page login accessible" "curl -s -o /dev/null -w '%{http_code}' $DOMAIN/login | grep -q '200'"

# Exports et PDF
check_status "DomPDF fonctionnel" "php -r \"echo class_exists('Barryvdh\\\DomPDF\\\Facade\\\Pdf') ? 'OK' : 'NOK';\" | grep -q 'OK'"
check_status "Excel export fonctionnel" "php -r \"echo class_exists('Maatwebsite\\\Excel\\\Facades\\\Excel') ? 'OK' : 'NOK';\" | grep -q 'OK'"

# Artisan et commandes
check_status "Artisan fonctionnel" "php artisan --version"
check_status "Cache optimization" "php artisan config:cache"
check_status "Permissions cache reset" "php artisan permission:cache-reset"

# 9. AUDIT CONFORMITÉ LÉGALE
echo_log "\n${PURPLE}═══════════════════════════════════════════════════════════════${NC}"
echo_log "${PURPLE}                    ⚖️  AUDIT CONFORMITÉ LÉGALE                ${NC}"
echo_log "${PURPLE}═══════════════════════════════════════════════════════════════${NC}\n"

# Pages légales Loi 25 Québec
check_status "Politique confidentialité accessible" "curl -s -o /dev/null -w '%{http_code}' $DOMAIN/politique-confidentialite | grep -q '200'"
check_status "Conditions utilisation accessible" "curl -s -o /dev/null -w '%{http_code}' $DOMAIN/conditions-utilisation | grep -q '200'"
check_status "Page contact accessible" "curl -s -o /dev/null -w '%{http_code}' $DOMAIN/contact | grep -q '200'"
check_status "Contrôleur Legal présent" "[ -f app/Http/Controllers/LegalController.php ]"

# 10. AUDIT PERFORMANCE ET MONITORING
echo_log "\n${PURPLE}═══════════════════════════════════════════════════════════════${NC}"
echo_log "${PURPLE}                   ⚡ AUDIT PERFORMANCE & MONITORING           ${NC}"
echo_log "${PURPLE}═══════════════════════════════════════════════════════════════${NC}\n"

# Performance
RESPONSE_TIME=$(curl -o /dev/null -s -w '%{time_total}' $DOMAIN)
check_status "Temps réponse < 1s ($RESPONSE_TIME s)" "echo '$RESPONSE_TIME < 1.0' | bc -l | grep -q '1'"

# Logs et monitoring
check_status "Logs Laravel présents" "[ -f storage/logs/laravel.log ]"
check_status "Logs Nginx présents" "[ -f /var/log/nginx/access.log ]"
check_status "ActivityLog actif" "mysql -u $DB_USER -p$DB_PASS $DB_NAME -e 'SELECT COUNT(*) FROM activity_log' | tail -1 | grep -v 'COUNT'"

# Espace disque
DISK_USAGE=$(df /home/studiosdb | awk 'NR==2 {print $5}' | sed 's/%//')
check_status "Espace disque < 80% ($DISK_USAGE%)" "[ $DISK_USAGE -lt 80 ]"

# 11. GÉNÉRATION RAPPORT FINAL
echo_log "\n${PURPLE}═══════════════════════════════════════════════════════════════${NC}"
echo_log "${PURPLE}                      📊 RAPPORT FINAL D'AUDIT                 ${NC}"
echo_log "${PURPLE}═══════════════════════════════════════════════════════════════${NC}\n"

# Calcul du score
SCORE=$((PASSED_CHECKS * 100 / TOTAL_CHECKS))

echo_log "${CYAN}📈 RÉSULTATS GLOBAUX:${NC}"
echo_log "   🔍 Tests effectués: $TOTAL_CHECKS"
echo_log "   ✅ Succès: $PASSED_CHECKS"
echo_log "   ❌ Échecs: $FAILED_CHECKS"
echo_log "   ⚠️  Avertissements: $WARNING_CHECKS"
echo_log "   📊 Score global: $SCORE%"

# Détermination du statut
if [ $SCORE -ge 95 ]; then
    STATUS_COLOR=$GREEN
    STATUS="🥇 EXCELLENT - PRODUCTION READY"
elif [ $SCORE -ge 85 ]; then
    STATUS_COLOR=$YELLOW
    STATUS="🥈 BON - Corrections mineures recommandées"
elif [ $SCORE -ge 70 ]; then
    STATUS_COLOR=$YELLOW
    STATUS="🥉 MOYEN - Corrections importantes nécessaires"
else
    STATUS_COLOR=$RED
    STATUS="❌ CRITIQUE - Intervention urgente requise"
fi

echo_log "\n${STATUS_COLOR}🎯 STATUT FINAL: $STATUS${NC}"

# Recommandations basées sur le score
echo_log "\n${CYAN}💡 RECOMMANDATIONS:${NC}"
if [ $FAILED_CHECKS -gt 0 ]; then
    echo_log "   🔧 Corriger les $FAILED_CHECKS échecs identifiés"
fi
if [ $WARNING_CHECKS -gt 0 ]; then
    echo_log "   ⚠️  Examiner les $WARNING_CHECKS avertissements"
fi
echo_log "   📋 Planifier audit mensuel"
echo_log "   💾 Effectuer sauvegarde complète"
echo_log "   🔄 Vérifier mises à jour sécurité"

# Footer
echo_log "\n${PURPLE}═══════════════════════════════════════════════════════════════${NC}"
echo_log "${CYAN}🥋 StudiosUnisDB v3.8.3-FINAL - Audit terminé${NC}"
echo_log "${CYAN}📅 $(date)${NC}"
echo_log "${CYAN}📝 Rapport sauvegardé: $LOG_FILE${NC}"
echo_log "${PURPLE}═══════════════════════════════════════════════════════════════${NC}"

# Sortie avec code approprié
if [ $SCORE -ge 95 ]; then
    exit 0
elif [ $SCORE -ge 70 ]; then
    exit 1
else
    exit 2
fi
