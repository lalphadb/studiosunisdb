#!/bin/bash

# StudiosDB - Compilation complète des assets
# Purpose: Recompiler tous les assets après modifications

echo "
╔══════════════════════════════════════════════════════════════╗
║          StudiosDB - Compilation Assets Complète            ║
╚══════════════════════════════════════════════════════════════╝
"

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
BLUE='\033[0;34m'
NC='\033[0m'

echo -e "${YELLOW}Recompilation complète des assets...${NC}"
echo ""

# 1. Arrêt complet
echo "1. Arrêt des processus..."
pkill -f "artisan serve" 2>/dev/null
pkill -f "vite" 2>/dev/null
pkill -f "npm" 2>/dev/null
rm -f public/hot
echo -e "${GREEN}✓${NC} Processus arrêtés"

# 2. Nettoyage complet
echo ""
echo "2. Nettoyage complet..."
rm -rf public/build
rm -rf node_modules/.vite
rm -rf storage/framework/views/*
php artisan optimize:clear > /dev/null 2>&1
echo -e "${GREEN}✓${NC} Nettoyage terminé"

# 3. Vérification des fichiers critiques
echo ""
echo "3. Vérification des fichiers Vue..."
files_ok=true

# Fichiers à vérifier
declare -a vue_files=(
    "resources/js/Pages/Dashboard/Admin.vue"
    "resources/js/Pages/Auth/Login.vue"
    "resources/js/Pages/Welcome.vue"
    "resources/js/Pages/Membres/Index.vue"
    "resources/js/Pages/Membres/Create.vue"
    "resources/js/Pages/Membres/Edit.vue"
)

for file in "${vue_files[@]}"; do
    if [ -f "$file" ]; then
        echo -e "  ${GREEN}✓${NC} $(basename $file)"
    else
        echo -e "  ${RED}✗${NC} $(basename $file) MANQUANT!"
        files_ok=false
    fi
done

if [ "$files_ok" = false ]; then
    echo ""
    echo -e "${RED}ATTENTION: Des fichiers sont manquants!${NC}"
    echo "Vérifiez votre code source."
    echo ""
fi

# 4. Installation des dépendances si nécessaire
echo ""
echo "4. Vérification des dépendances..."
if [ ! -d "node_modules" ] || [ ! -f "node_modules/.bin/vite" ]; then
    echo "Installation des dépendances npm..."
    npm install
    echo -e "${GREEN}✓${NC} Dépendances installées"
else
    echo -e "${GREEN}✓${NC} Dépendances déjà installées"
fi

# 5. Build de production
echo ""
echo "5. Compilation en mode production..."
echo -e "${BLUE}Cette étape peut prendre 30-60 secondes...${NC}"
echo ""

# Capture la sortie de npm run build
build_output=$(npm run build 2>&1)
build_status=$?

if [ $build_status -eq 0 ]; then
    echo -e "${GREEN}✓${NC} Build réussi!"
    
    # Vérifier le manifest
    if [ -f "public/build/.vite/manifest.json" ]; then
        echo ""
        echo "Vérification du manifest:"
        
        # Vérifier les fichiers critiques dans le manifest
        if grep -q "Dashboard/Admin.vue" public/build/.vite/manifest.json; then
            echo -e "  ${GREEN}✓${NC} Dashboard/Admin.vue compilé"
        else
            echo -e "  ${YELLOW}⚠${NC} Dashboard/Admin.vue absent du manifest"
        fi
        
        if grep -q "Auth/Login.vue" public/build/.vite/manifest.json; then
            echo -e "  ${GREEN}✓${NC} Auth/Login.vue compilé"
        else
            echo -e "  ${YELLOW}⚠${NC} Auth/Login.vue absent du manifest"
        fi
        
        # Statistiques du build
        echo ""
        echo "Statistiques du build:"
        echo -n "  • Fichiers JS: "
        ls public/build/assets/*.js 2>/dev/null | wc -l
        echo -n "  • Fichiers CSS: "
        ls public/build/assets/*.css 2>/dev/null | wc -l
        echo -n "  • Taille totale: "
        du -sh public/build 2>/dev/null | cut -f1
    else
        echo -e "${RED}✗${NC} Manifest non créé!"
    fi
else
    echo -e "${RED}✗${NC} Erreur de compilation!"
    echo ""
    echo "Détails de l'erreur:"
    echo "$build_output" | tail -20
    echo ""
    echo "Solutions possibles:"
    echo "  1. rm -rf node_modules package-lock.json"
    echo "  2. npm install"
    echo "  3. npm run build"
    exit 1
fi

# 6. Optimisation Laravel
echo ""
echo "6. Optimisation Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo -e "${GREEN}✓${NC} Caches optimisés"

# 7. Démarrage du serveur
echo ""
echo "7. Démarrage du serveur..."
php artisan serve --host=127.0.0.1 --port=8001 &
SERVER_PID=$!

sleep 3

# 8. Tests
echo ""
echo "8. Tests d'accès..."

# Test Dashboard
response=$(curl -s -o /dev/null -w "%{http_code}" http://127.0.0.1:8001/dashboard)
if [ "$response" = "302" ]; then
    echo -e "${GREEN}✓${NC} Dashboard accessible (redirection login)"
elif [ "$response" = "200" ]; then
    echo -e "${GREEN}✓${NC} Dashboard accessible (déjà connecté)"
else
    echo -e "${RED}✗${NC} Dashboard inaccessible (HTTP $response)"
fi

# Test Login
response=$(curl -s -o /dev/null -w "%{http_code}" http://127.0.0.1:8001/login)
if [ "$response" = "200" ]; then
    echo -e "${GREEN}✓${NC} Login accessible"
else
    echo -e "${YELLOW}⚠${NC} Login HTTP $response"
fi

echo ""
echo "════════════════════════════════════════════════════════════════"
echo -e "${GREEN}✅ COMPILATION TERMINÉE!${NC}"
echo "════════════════════════════════════════════════════════════════"
echo ""
echo -e "${BLUE}Application accessible:${NC}"
echo "  🌐 http://127.0.0.1:8001"
echo ""
echo "Pages disponibles:"
echo "  • Dashboard: http://127.0.0.1:8001/dashboard"
echo "  • Login: http://127.0.0.1:8001/login"
echo "  • Membres: http://127.0.0.1:8001/membres"
echo ""
echo -e "${YELLOW}Commandes utiles:${NC}"
echo "  • Arrêter: kill $SERVER_PID"
echo "  • Mode dev: npm run dev"
echo "  • Rebuild: npm run build"
echo ""

# Garder le serveur actif
trap "kill $SERVER_PID 2>/dev/null; echo 'Serveur arrêté'; exit" INT
wait $SERVER_PID
