#!/bin/bash
# audit_studiosdb.sh
# Audit ultra-professionnel pour StudiosDB v5 Pro
# Basé sur README.md (Laravel 12.21.x, multi-tenant, etc.)
# Date: August 01, 2025
# Usage: ./audit_studiosdb.sh [full|quick] (default: full)

MODE=${1:-full}
REPORT_FILE="audit_report_$(date +%Y%m%d).txt"
echo "🎯 Audit StudiosDB v5 Pro - Mode: $MODE" | tee "$REPORT_FILE"
echo "📅 Date: $(date)" | tee -a "$REPORT_FILE"
echo "======================================" | tee -a "$REPORT_FILE"

# Fonctions utilitaires
check_command() {
    command -v "$1" >/dev/null 2>&1 || { echo "❌ $1 non installé" | tee -a "$REPORT_FILE"; exit 1; }
}
check_file() {
    [ -f "$1" ] && echo "✅ $1 présent" | tee -a "$REPORT_FILE" || echo "❌ $1 manquant" | tee -a "$REPORT_FILE"
}
check_dir() {
    [ -d "$1" ] && echo "✅ $1 présent" | tee -a "$REPORT_FILE" || echo "❌ $1 manquant" | tee -a "$REPORT_FILE"
}
check_grep() {
    grep -q "$2" "$1" && echo "✅ $3 OK dans $1" | tee -a "$REPORT_FILE" || echo "❌ $3 manquant dans $1" | tee -a "$REPORT_FILE"
}

# Pré-requis
check_command "composer"
check_command "php"
check_command "npm"
check_command "grep"
check_command "curl"

# Phase 1: Versions & Dépendances
echo "🔍 Phase 1: Versions & Dépendances" | tee -a "$REPORT_FILE"
php_version=$(php -v | head -n1 | cut -d" " -f2)
if [[ "$php_version" == 8.3* ]]; then
    echo "✅ PHP: $php_version (conforme)" | tee -a "$REPORT_FILE"
else
    echo "❌ PHP: $php_version (requis: 8.3+)" | tee -a "$REPORT_FILE"
fi
laravel_version=$(composer show laravel/framework | grep versions | cut -d: -f2 | tr -d ' ')
if [[ "$laravel_version" == v12.21* ]]; then
    echo "✅ Laravel: $laravel_version (conforme)" | tee -a "$REPORT_FILE"
else
    echo "❌ Laravel: $laravel_version (requis: 12.21.x)" | tee -a "$REPORT_FILE"
fi
check_grep "composer.json" '"vue": "^3"' "Vue 3"
check_grep "composer.json" '"stancl/tenancy"' "Multi-tenant (Stancl/Tenancy)"

# Phase 2: Structure Projet
echo "🔍 Phase 2: Structure Projet" | tee -a "$REPORT_FILE"
check_file ".env"
check_file "composer.json"
check_file "package.json"
check_dir "app/Models"
check_dir "app/Http/Controllers"
check_dir "resources/js/Pages"
check_dir "database/migrations"
check_dir "routes"
check_file "app/Providers/TenancyServiceProvider.php"  # Multi-tenant
check_file "config/tenancy.php"  # Config multi-tenant

# Phase 3: Sécurité
echo "🔍 Phase 3: Sécurité" | tee -a "$REPORT_FILE"
check_grep "app/Http/Middleware/VerifyCsrfToken.php" "protected" "CSRF protection"
check_grep "app/Providers/AppServiceProvider.php" "Hash::needsRehash" "Hashed passwords"
check_grep "app/Http/Kernel.php" "throttle" "Rate limiting (anti-brute force)"
check_dir "app/Policies"  # Rôles granulaires
check_grep "app/Models/User.php" "HasRoles" "Rôles (e.g., Spatie/Permission)"
check_grep "config/app.php" "'debug' => false" "APP_DEBUG=false (prod ready)"
check_grep ".env" "APP_ENV=production" "Environnement production"

# Phase 4: Relations & DB
echo "🔍 Phase 4: Relations & DB" | tee -a "$REPORT_FILE"
check_grep "app/Models/Membre.php" "hasMany" "Relations Eloquent (e.g., Paiements)"
check_grep "app/Models/Membre.php" "belongsTo" "Relations Eloquent (e.g., Ceinture)"
check_grep "app/Models/Cours.php" "belongsToMany" "Relations many-to-many (e.g., Membres)"
check_grep "database/migrations/*membres_table.php" "foreign" "Foreign keys"
check_grep "database/migrations/*" "index" "Index DB pour performance"
check_grep "config/database.php" "redis" "Redis cache configuré"
php artisan migrate:status >/dev/null 2>&1 && echo "✅ Migrations OK" | tee -a "$REPORT_FILE" || echo "❌ Migrations échouées" | tee -a "$REPORT_FILE"

# Phase 5: Modules
echo "🔍 Phase 5: Modules" | tee -a "$REPORT_FILE"
modules=("Membres" "Cours" "Presences" "Paiements" "Ceintures" "Statistiques")
for module in "${modules[@]}"; do
    check_file "app/Http/Controllers/${module}Controller.php"
    check_file "app/Models/${module%.php}.php"  # Model sans s si singulier
    check_dir "resources/js/Pages/${module}"
    check_grep "routes/web.php" "${module,,}" "Routes pour $module"
done
check_file "resources/js/Pages/Presences/Tablette.vue"  # Interface tablette
check_grep "app/Http/Controllers/PaiementController.php" "facture" "Génération factures"
check_grep "app/Models/Ceinture.php" "progression" "Système ceintures"

# Phase 6: Tests & Performance
echo "🔍 Phase 6: Tests & Performance" | tee -a "$REPORT_FILE"
if php artisan test --coverage >/dev/null 2>&1; then
    coverage=$(grep "Code Coverage" phpunit.xml | cut -d: -f2 | tr -d ' %')
    [ "$coverage" -ge 84 ] && echo "✅ Coverage tests: $coverage% (conforme)" | tee -a "$REPORT_FILE" || echo "❌ Coverage tests: $coverage% (<84%)" | tee -a "$REPORT_FILE"
else
    echo "❌ Tests échoués ou non configurés" | tee -a "$REPORT_FILE"
fi
check_grep "config/cache.php" "redis" "Redis pour performance"
curl -s -o /dev/null -w "%{time_total}" "http://localhost:8000/dashboard" | awk '{print "⏱️ Temps dashboard: " $1 "s (cible <0.015s)"}' | tee -a "$REPORT_FILE"

echo "======================================" | tee -a "$REPORT_FILE"
echo "✅ Audit terminé - Rapport: $REPORT_FILE" | tee -a "$REPORT_FILE"
