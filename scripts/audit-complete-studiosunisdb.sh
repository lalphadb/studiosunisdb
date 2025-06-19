#!/bin/bash
echo "🔍 === AUDIT COMPLET StudiosUnisDB v3.9.1 ==="
echo "📅 Date: $(date)"
echo "👤 Utilisateur: $(whoami)"
echo "📁 Répertoire: $(pwd)"
echo ""

# Couleurs pour l'affichage
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Fonction pour afficher les résultats
show_result() {
    if [ $1 -eq 0 ]; then
        echo -e "${GREEN}✅ $2${NC}"
    else
        echo -e "${RED}❌ $2${NC}"
    fi
}

show_warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

show_info() {
    echo -e "${BLUE}ℹ️  $1${NC}"
}

cd /home/studiosdb/studiosunisdb/

echo "🔍 === 1. ENVIRONNEMENT SYSTÈME ==="
echo "OS: $(lsb_release -d | cut -f2)"
echo "PHP Version: $(php -v | head -n1)"
echo "Composer Version: $(composer --version)"
echo "Laravel Version: $(php artisan --version)"
echo "Node Version: $(node -v 2>/dev/null || echo 'Non installé')"
echo "NPM Version: $(npm -v 2>/dev/null || echo 'Non installé')"
echo ""

echo "🔍 === 2. STRUCTURE PROJET ==="
echo "Vérification des dossiers critiques..."
[ -d "app" ] && show_result 0 "Dossier app/" || show_result 1 "Dossier app/ manquant"
[ -d "database" ] && show_result 0 "Dossier database/" || show_result 1 "Dossier database/ manquant"
[ -d "resources" ] && show_result 0 "Dossier resources/" || show_result 1 "Dossier resources/ manquant"
[ -d "storage" ] && show_result 0 "Dossier storage/" || show_result 1 "Dossier storage/ manquant"
[ -d "public" ] && show_result 0 "Dossier public/" || show_result 1 "Dossier public/ manquant"

echo ""
echo "Vérification des fichiers critiques..."
[ -f ".env" ] && show_result 0 "Fichier .env" || show_result 1 "Fichier .env manquant"
[ -f "composer.json" ] && show_result 0 "Fichier composer.json" || show_result 1 "Fichier composer.json manquant"
[ -f "artisan" ] && show_result 0 "Fichier artisan" || show_result 1 "Fichier artisan manquant"

echo ""
echo "🔍 === 3. PERMISSIONS FICHIERS ==="
echo "Vérification des permissions..."
[ -w "storage" ] && show_result 0 "storage/ accessible en écriture" || show_result 1 "storage/ non accessible en écriture"
[ -w "bootstrap/cache" ] && show_result 0 "bootstrap/cache/ accessible en écriture" || show_result 1 "bootstrap/cache/ non accessible en écriture"

echo ""
echo "🔍 === 4. CONFIGURATION LARAVEL ==="
echo "Vérification du fichier .env..."
if grep -q "APP_KEY=" .env && [ "$(grep APP_KEY= .env | cut -d'=' -f2)" != "" ]; then
    show_result 0 "APP_KEY configurée"
else
    show_result 1 "APP_KEY manquante ou vide"
fi

if grep -q "DB_DATABASE=" .env; then
    show_result 0 "Configuration base de données présente"
else
    show_result 1 "Configuration base de données manquante"
fi

echo ""
echo "🔍 === 5. DÉPENDANCES COMPOSER ==="
echo "Vérification des packages critiques..."
composer show laravel/framework > /dev/null 2>&1 && show_result 0 "Laravel Framework installé" || show_result 1 "Laravel Framework manquant"
composer show spatie/laravel-permission > /dev/null 2>&1 && show_result 0 "Spatie Permission installé" || show_result 1 "Spatie Permission manquant"

echo ""
echo "🔍 === 6. BASE DE DONNÉES ==="
echo "Test de connexion base de données..."
php artisan migrate:status > /dev/null 2>&1 && show_result 0 "Connexion DB OK" || show_result 1 "Problème connexion DB"

echo ""
echo "🔍 === 7. PROVIDERS ET SERVICES ==="
echo "Vérification des Service Providers..."
[ -f "app/Providers/AppServiceProvider.php" ] && show_result 0 "AppServiceProvider" || show_result 1 "AppServiceProvider manquant"
[ -f "app/Providers/EventServiceProvider.php" ] && show_result 0 "EventServiceProvider" || show_result 1 "EventServiceProvider manquant"
[ -f "app/Providers/AuthServiceProvider.php" ] && show_result 0 "AuthServiceProvider" || show_result 1 "AuthServiceProvider manquant"

echo ""
echo "🔍 === 8. ROUTES ==="
echo "Vérification des fichiers de routes..."
[ -f "routes/web.php" ] && show_result 0 "routes/web.php" || show_result 1 "routes/web.php manquant"
[ -f "routes/admin.php" ] && show_result 0 "routes/admin.php" || show_result 1 "routes/admin.php manquant"

echo ""
echo "🔍 === 9. CONTRÔLEURS ==="
echo "Vérification des contrôleurs admin..."
[ -f "app/Http/Controllers/Admin/DashboardController.php" ] && show_result 0 "DashboardController" || show_result 1 "DashboardController manquant"
[ -f "app/Http/Controllers/Admin/MembreController.php" ] && show_result 0 "MembreController" || show_result 1 "MembreController manquant"
[ -f "app/Http/Controllers/Admin/PresenceController.php" ] && show_result 0 "PresenceController" || show_result 1 "PresenceController manquant"

echo ""
echo "🔍 === 10. MODÈLES ==="
echo "Vérification des modèles..."
[ -f "app/Models/User.php" ] && show_result 0 "User Model" || show_result 1 "User Model manquant"
[ -f "app/Models/Ecole.php" ] && show_result 0 "Ecole Model" || show_result 1 "Ecole Model manquant"
[ -f "app/Models/Membre.php" ] && show_result 0 "Membre Model" || show_result 1 "Membre Model manquant"

echo ""
echo "🔍 === 11. VUES ==="
echo "Vérification des vues critiques..."
[ -f "resources/views/layouts/admin.blade.php" ] && show_result 0 "Layout admin" || show_result 1 "Layout admin manquant"
[ -d "resources/views/admin" ] && show_result 0 "Dossier vues admin" || show_result 1 "Dossier vues admin manquant"

echo ""
echo "🔍 === 12. ASSETS ==="
echo "Vérification des assets..."
[ -f "resources/css/app.css" ] && show_result 0 "CSS principal" || show_result 1 "CSS principal manquant"
[ -f "resources/js/app.js" ] && show_result 0 "JS principal" || show_result 1 "JS principal manquant"
[ -f "vite.config.js" ] && show_result 0 "Configuration Vite" || show_result 1 "Configuration Vite manquante"

echo ""
echo "🔍 === 13. TESTS ARTISAN ==="
echo "Test des commandes Artisan critiques..."
php artisan config:check > /dev/null 2>&1 && show_result 0 "Configuration Laravel OK" || show_result 1 "Problème configuration Laravel"
php artisan route:list > /dev/null 2>&1 && show_result 0 "Routes OK" || show_result 1 "Problème routes"

echo ""
echo "🔍 === 14. LOGS ET ERREURS ==="
if [ -f "storage/logs/laravel.log" ]; then
    error_count=$(grep -c "ERROR" storage/logs/laravel.log 2>/dev/null || echo "0")
    if [ "$error_count" -gt 0 ]; then
        show_warning "Erreurs trouvées dans les logs: $error_count"
    else
        show_result 0 "Aucune erreur dans les logs"
    fi
else
    show_warning "Fichier de log non trouvé"
fi

echo ""
echo "🔍 === RÉSUMÉ AUDIT ==="
echo "Audit terminé le $(date)"
echo ""
echo "📋 ACTIONS RECOMMANDÉES:"
echo "1. Exécuter le script de réparation automatique"
echo "2. Nettoyer et réinstaller les dépendances"
echo "3. Régénérer les caches et optimisations"
echo "4. Tester l'application complètement"
echo ""
