#!/bin/bash

# =============================================================================
# STUDIOSDB V5 PRO - TEST POST MISE À JOUR LARAVEL 12.x
# Vérifications complètes après mise à jour
# =============================================================================

echo "🧪 TEST POST MISE À JOUR LARAVEL 12.x"
echo "====================================="

cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro

# Variables
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

PASSED=0
FAILED=0

# Fonction de test
run_test() {
    local test_name="$1"
    local command="$2"
    local expected="$3"
    
    echo -n "🔍 $test_name... "
    
    if eval "$command" >/dev/null 2>&1; then
        echo -e "${GREEN}✅ PASS${NC}"
        ((PASSED++))
    else
        echo -e "${RED}❌ FAIL${NC}"
        ((FAILED++))
        if [ ! -z "$expected" ]; then
            echo "   Attendu: $expected"
        fi
    fi
}

echo -e "\n${BLUE}📋 TESTS LARAVEL 12.x - STUDIOSDB V5 PRO${NC}"

# =============================================================================
# TESTS SYSTÈME
# =============================================================================

echo -e "\n${YELLOW}🖥️  TESTS SYSTÈME${NC}"

# Test PHP version
PHP_VERSION=$(php -r "echo PHP_VERSION;")
echo -n "🔍 Version PHP ($PHP_VERSION)... "
if php -r "exit(version_compare(PHP_VERSION, '8.2.0', '>=') ? 0 : 1);"; then
    echo -e "${GREEN}✅ PASS${NC}"
    ((PASSED++))
else
    echo -e "${RED}❌ FAIL (PHP 8.2+ requis)${NC}"
    ((FAILED++))
fi

# Test Composer
run_test "Composer disponible" "command -v composer"

# Test Laravel version
LARAVEL_VERSION=$(php artisan --version 2>/dev/null | grep -o 'Laravel Framework [0-9.]*' || echo "Indisponible")
echo -n "🔍 Version Laravel ($LARAVEL_VERSION)... "
if [[ $LARAVEL_VERSION == *"12."* ]]; then
    echo -e "${GREEN}✅ PASS${NC}"
    ((PASSED++))
else
    echo -e "${RED}❌ FAIL (Laravel 12.x attendu)${NC}"
    ((FAILED++))
fi

# =============================================================================
# TESTS CONFIGURATION
# =============================================================================

echo -e "\n${YELLOW}⚙️  TESTS CONFIGURATION${NC}"

run_test "Configuration Laravel" "php artisan config:check"
run_test "Clé d'application générée" "grep -q '^APP_KEY=base64:' .env"
run_test "Environnement configuré" "grep -q '^APP_ENV=' .env"
run_test "Base de données configurée" "grep -q '^DB_CONNECTION=' .env"

# =============================================================================
# TESTS BASE DE DONNÉES
# =============================================================================

echo -e "\n${YELLOW}🗄️  TESTS BASE DE DONNÉES${NC}"

run_test "Connexion base de données" "php artisan tinker --execute=\"DB::select('SELECT 1');\""
run_test "Tables migrations" "php artisan migrate:status | grep -q 'Migration name'"

# =============================================================================
# TESTS DÉPENDANCES
# =============================================================================

echo -e "\n${YELLOW}📦 TESTS DÉPENDANCES${NC}"

run_test "Vendor généré" "[ -d vendor ]"
run_test "Autoload généré" "[ -f vendor/autoload.php ]"
run_test "Node modules installés" "[ -d node_modules ]"
run_test "Package-lock présent" "[ -f package-lock.json ]"

# =============================================================================
# TESTS ASSETS
# =============================================================================

echo -e "\n${YELLOW}🎨 TESTS ASSETS${NC}"

run_test "Build directory exists" "[ -d public/build ]"
run_test "Manifest Vite généré" "[ -f public/build/manifest.json ]"
run_test "Assets JS compilés" "find public/build -name '*.js' | grep -q '.'"
run_test "Assets CSS compilés" "find public/build -name '*.css' | grep -q '.'"

# =============================================================================
# TESTS CACHE
# =============================================================================

echo -e "\n${YELLOW}⚡ TESTS CACHE${NC}"

run_test "Cache config généré" "[ -f bootstrap/cache/config.php ]"
run_test "Cache routes généré" "[ -f bootstrap/cache/routes-v7.php ]"
run_test "Storage link" "[ -L public/storage ]"

# =============================================================================
# TESTS FONCTIONNALITÉS LARAVEL
# =============================================================================

echo -e "\n${YELLOW}🚀 TESTS FONCTIONNALITÉS${NC}"

run_test "Artisan disponible" "php artisan list | grep -q 'Available commands'"
run_test "Routes chargées" "php artisan route:list | grep -q 'GET'"
run_test "Sanctum installé" "php artisan vendor:publish --help | grep -q sanctum"
run_test "Inertia configuré" "grep -q inertiajs composer.json"

# =============================================================================
# TESTS STUDIOSDB SPÉCIFIQUES
# =============================================================================

echo -e "\n${YELLOW}🥋 TESTS STUDIOSDB SPÉCIFIQUES${NC}"

run_test "Modèle User existe" "[ -f app/Models/User.php ]"
run_test "Controller Dashboard" "[ -f app/Http/Controllers/DashboardController.php ]"
run_test "Middleware Inertia" "grep -q Inertia app/Http/Kernel.php"
run_test "Vue layout principal" "[ -f resources/js/Layouts/AuthenticatedLayout.vue ]"

# =============================================================================
# TEST SERVEUR DEVELOPMENT
# =============================================================================

echo -e "\n${YELLOW}🌐 TEST SERVEUR${NC}"

echo -n "🔍 Lancement serveur test... "

# Démarrer serveur en background
timeout 10 php artisan serve --port=8001 >/dev/null 2>&1 &
SERVER_PID=$!

# Attendre 3 secondes
sleep 3

# Tester connexion
if curl -s -o /dev/null -w "%{http_code}" http://localhost:8001 | grep -q "200\|302"; then
    echo -e "${GREEN}✅ PASS${NC}"
    ((PASSED++))
else
    echo -e "${RED}❌ FAIL${NC}"
    ((FAILED++))
fi

# Arrêter serveur
kill $SERVER_PID 2>/dev/null || true

# =============================================================================
# RÉSULTATS FINAUX
# =============================================================================

echo -e "\n${BLUE}📊 RÉSULTATS FINAUX${NC}"
echo "==================="

TOTAL=$((PASSED + FAILED))
SUCCESS_RATE=$((PASSED * 100 / TOTAL))

echo "Total tests: $TOTAL"
echo -e "Tests réussis: ${GREEN}$PASSED${NC}"
echo -e "Tests échoués: ${RED}$FAILED${NC}"
echo "Taux de réussite: $SUCCESS_RATE%"

echo -e "\n${BLUE}🎯 RECOMMANDATIONS${NC}"

if [ $FAILED -eq 0 ]; then
    echo -e "${GREEN}🎉 EXCELLENT! Tous les tests sont passés!${NC}"
    echo "✅ Votre StudiosDB v5 Pro Laravel 12.x est entièrement fonctionnel"
    echo ""
    echo "🚀 Commandes de lancement:"
    echo "   php artisan serve --host=0.0.0.0 --port=8000"
    echo "   npm run dev (optionnel)"
elif [ $FAILED -le 3 ]; then
    echo -e "${YELLOW}⚠️  ATTENTION: Quelques tests ont échoué${NC}"
    echo "🔧 Vérifiez les éléments marqués FAIL ci-dessus"
    echo "💡 L'application peut fonctionner mais avec des limitations"
else
    echo -e "${RED}🚨 PROBLÈME: Plusieurs tests ont échoué${NC}"
    echo "🆘 Considérez un rollback avec: ./rollback_laravel12.sh"
    echo "🔧 Ou corrigez les problèmes avant de continuer"
fi

echo ""
echo "📋 Pour plus de détails:"
echo "   tail -f storage/logs/laravel.log"
echo "   php artisan config:check"
echo ""
echo -e "${BLUE}Tests terminés!${NC}"
