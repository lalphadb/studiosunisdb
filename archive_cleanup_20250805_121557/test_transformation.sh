#!/bin/bash

# Test de validation de la transformation ultra-professionnelle
# StudiosDB v5.2.0

echo "🥋 Test de Validation - StudiosDB v5 Pro Ultra"
echo "=============================================="
echo ""

# Couleurs pour les messages
GREEN='\033[0;32m'
RED='\033[0;31m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Variables
BASE_DIR="/home/studiosdb/studiosunisdb/studiosdb_v5_pro"
SUCCESS=0
ERRORS=0

# Fonction pour afficher les résultats
print_result() {
    if [ $1 -eq 0 ]; then
        echo -e "${GREEN}✅ $2${NC}"
        ((SUCCESS++))
    else
        echo -e "${RED}❌ $2${NC}"
        ((ERRORS++))
    fi
}

echo -e "${BLUE}📦 Vérification des fichiers critiques...${NC}"

# Test 1: Vérifier que DashboardUltraPro.vue existe
if [ -f "$BASE_DIR/resources/js/Pages/DashboardUltraPro.vue" ]; then
    print_result 0 "DashboardUltraPro.vue présent"
else
    print_result 1 "DashboardUltraPro.vue manquant"
fi

# Test 2: Vérifier les composants modernes
for component in "ModernStatsCard.vue" "ModernProgressBar.vue" "ModernActionCard.vue"; do
    if [ -f "$BASE_DIR/resources/js/Components/$component" ]; then
        print_result 0 "Component $component présent"
    else
        print_result 1 "Component $component manquant"
    fi
done

# Test 3: Vérifier que le contrôleur pointe vers la bonne vue
if grep -q "DashboardUltraPro" "$BASE_DIR/app/Http/Controllers/DashboardController.php"; then
    print_result 0 "DashboardController configuré pour DashboardUltraPro"
else
    print_result 1 "DashboardController non configuré"
fi

echo ""
echo -e "${BLUE}🔧 Vérification des dépendances...${NC}"

# Test 4: Vérifier package.json pour les nouvelles dépendances
cd "$BASE_DIR"
if npm list @heroicons/vue > /dev/null 2>&1; then
    print_result 0 "@heroicons/vue installé"
else
    print_result 1 "@heroicons/vue manquant"
fi

if npm list chart.js > /dev/null 2>&1; then
    print_result 0 "chart.js installé"
else
    print_result 1 "chart.js manquant"
fi

if npm list vue-chartjs > /dev/null 2>&1; then
    print_result 0 "vue-chartjs installé"
else
    print_result 1 "vue-chartjs manquant"
fi

echo ""
echo -e "${BLUE}🏗️ Test de compilation...${NC}"

# Test 5: Vérifier que la compilation fonctionne
if npm run build > /dev/null 2>&1; then
    print_result 0 "Compilation Vite réussie"
else
    print_result 1 "Erreur de compilation Vite"
fi

# Test 6: Vérifier que les assets existent
if [ -d "$BASE_DIR/public/build" ]; then
    print_result 0 "Assets de build générés"
else
    print_result 1 "Assets de build manquants"
fi

echo ""
echo -e "${BLUE}📊 Analyse de la taille du projet...${NC}"

# Test 7: Calculer la taille du projet
PROJECT_SIZE=$(du -sh "$BASE_DIR" | cut -f1)
echo -e "${YELLOW}📏 Taille du projet: $PROJECT_SIZE${NC}"

# Test 8: Compter les composants Vue restants
COMPONENT_COUNT=$(find "$BASE_DIR/resources/js/Components" -name "*.vue" 2>/dev/null | wc -l)
echo -e "${YELLOW}🧩 Composants Vue: $COMPONENT_COUNT${NC}"

# Test 9: Vérifier la suppression des anciens fichiers
OLD_SCRIPTS=$(find "$BASE_DIR" -name "*.sh" -not -path "*/vendor/*" -not -path "*/node_modules/*" | wc -l)
if [ $OLD_SCRIPTS -lt 10 ]; then
    print_result 0 "Cleanup des scripts effectué (restants: $OLD_SCRIPTS)"
else
    print_result 1 "Trop de scripts encore présents ($OLD_SCRIPTS)"
fi

echo ""
echo -e "${BLUE}🚀 Vérification du serveur Laravel...${NC}"

# Test 10: Vérifier si le serveur peut démarrer
if timeout 3 php artisan serve --port=8001 > /dev/null 2>&1; then
    print_result 0 "Serveur Laravel démarre correctement"
else
    # Le serveur peut être déjà en cours d'exécution sur un autre port
    if pgrep -f "artisan serve" > /dev/null; then
        print_result 0 "Serveur Laravel déjà en cours d'exécution"
    else
        print_result 1 "Problème de démarrage du serveur Laravel"
    fi
fi

echo ""
echo "=============================================="
echo -e "${GREEN}✅ Tests réussis: $SUCCESS${NC}"
echo -e "${RED}❌ Tests échoués: $ERRORS${NC}"

if [ $ERRORS -eq 0 ]; then
    echo ""
    echo -e "${GREEN}🎉 TRANSFORMATION ULTRA-PROFESSIONNELLE VALIDÉE !${NC}"
    echo -e "${GREEN}🥋 StudiosDB v5.2.0 est prêt pour la production${NC}"
    echo ""
    echo -e "${BLUE}📱 Pour tester l'interface:${NC}"
    echo -e "${YELLOW}   php artisan serve${NC}"
    echo -e "${YELLOW}   Puis ouvrir: http://localhost:8000${NC}"
    exit 0
else
    echo ""
    echo -e "${RED}⚠️  Certains tests ont échoué. Vérifiez les erreurs ci-dessus.${NC}"
    exit 1
fi
