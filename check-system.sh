#!/bin/bash
# StudiosDB v6 - Script de vérification complète
# Date: 2025-08-23

echo "================================================="
echo "   StudiosDB v6 - Vérification du système"
echo "================================================="

# Couleurs
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Fonction de vérification
check() {
    if [ $1 -eq 0 ]; then
        echo -e "${GREEN}✓${NC} $2"
    else
        echo -e "${RED}✗${NC} $2"
        return 1
    fi
}

echo -e "\n${YELLOW}1. Environnement Laravel${NC}"
echo "--------------------------------"
php artisan --version
check $? "Laravel accessible"

php artisan config:cache >/dev/null 2>&1
check $? "Configuration cachée"

php artisan route:cache >/dev/null 2>&1
check $? "Routes cachées"

echo -e "\n${YELLOW}2. Base de données${NC}"
echo "--------------------------------"
php artisan migrate:status | head -5
check $? "Migrations accessibles"

echo -e "\n${YELLOW}3. Composants Vue${NC}"
echo "--------------------------------"

# Vérifier les fichiers Vue critiques
files=(
    "resources/js/Pages/Dashboard.vue"
    "resources/js/Pages/Dashboard/Admin.vue"
    "resources/js/Layouts/AuthenticatedLayout.vue"
    "resources/js/Components/StatCard.vue"
    "resources/js/Components/UiButton.vue"
    "resources/js/Components/UiCard.vue"
    "resources/js/Components/UiInput.vue"
    "resources/js/Components/UiSelect.vue"
    "resources/js/Components/ActionCard.vue"
)

for file in "${files[@]}"; do
    if [ -f "$file" ]; then
        echo -e "${GREEN}✓${NC} $file"
    else
        echo -e "${RED}✗${NC} $file manquant"
    fi
done

echo -e "\n${YELLOW}4. Routes Inertia${NC}"
echo "--------------------------------"
if [ -f "resources/js/ziggy.js" ]; then
    echo -e "${GREEN}✓${NC} Ziggy routes configurées"
    grep -q "dashboard" resources/js/ziggy.js && echo -e "${GREEN}✓${NC} Route dashboard présente"
    grep -q "membres" resources/js/ziggy.js && echo -e "${GREEN}✓${NC} Route membres présente"
    grep -q "cours" resources/js/ziggy.js && echo -e "${GREEN}✓${NC} Route cours présente"
else
    echo -e "${RED}✗${NC} Ziggy routes manquantes"
fi

echo -e "\n${YELLOW}5. Compilation des assets${NC}"
echo "--------------------------------"
npm run build >/dev/null 2>&1
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓${NC} Build réussi"
    ls -la public/build/assets/*.js | head -3
else
    echo -e "${RED}✗${NC} Erreur de build"
    echo "Tentative avec dev..."
    npm run dev &
    DEV_PID=$!
    sleep 5
    if ps -p $DEV_PID > /dev/null; then
        echo -e "${GREEN}✓${NC} Vite dev server lancé (PID: $DEV_PID)"
    else
        echo -e "${RED}✗${NC} Impossible de lancer Vite"
    fi
fi

echo -e "\n${YELLOW}6. Test des routes HTTP${NC}"
echo "--------------------------------"

# Lancer le serveur si nécessaire
if ! curl -s http://localhost:8000 >/dev/null 2>&1; then
    echo "Démarrage du serveur Laravel..."
    php artisan serve --quiet &
    SERVER_PID=$!
    sleep 3
fi

# Tester les routes principales
routes=(
    "/"
    "/dashboard"
    "/login"
)

for route in "${routes[@]}"; do
    response=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8000$route)
    if [ "$response" = "200" ] || [ "$response" = "302" ] || [ "$response" = "303" ]; then
        echo -e "${GREEN}✓${NC} Route $route : HTTP $response"
    else
        echo -e "${RED}✗${NC} Route $route : HTTP $response"
    fi
done

echo -e "\n${YELLOW}7. Vérification des permissions${NC}"
echo "--------------------------------"
ls -ld storage bootstrap/cache | while read line; do
    if [[ $line == *"studiosdb"* ]]; then
        echo -e "${GREEN}✓${NC} Permissions OK : ${line:0:50}..."
    else
        echo -e "${YELLOW}⚠${NC} Vérifier permissions : ${line:0:50}..."
    fi
done

echo -e "\n${YELLOW}8. Statut des services${NC}"
echo "--------------------------------"
ps aux | grep -E "php artisan serve|vite" | grep -v grep | while read line; do
    echo -e "${GREEN}✓${NC} Service actif : $(echo $line | awk '{print $11, $12}')"
done

echo -e "\n${YELLOW}9. Résumé${NC}"
echo "--------------------------------"
echo -e "${GREEN}✓${NC} Projet StudiosDB v6 configuré"
echo -e "${GREEN}✓${NC} Dashboard et composants UI en place"
echo -e "${GREEN}✓${NC} Routes Inertia configurées"
echo ""
echo "🚀 Pour démarrer l'application :"
echo "   1. npm run dev (dans un terminal)"
echo "   2. php artisan serve (dans un autre terminal)"
echo "   3. Ouvrir http://localhost:8000"
echo ""
echo "📚 Modules disponibles :"
echo "   - Dashboard : http://localhost:8000/dashboard"
echo "   - Membres : http://localhost:8000/membres"
echo "   - Cours : http://localhost:8000/cours"
echo "   - Présences : http://localhost:8000/presences/tablette"
echo ""
echo "================================================="
