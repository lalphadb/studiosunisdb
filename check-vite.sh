#!/bin/bash

# StudiosDB - Diagnostic rapide Vite
# Purpose: Vérifier rapidement l'état de Vite et des assets

echo "=== Diagnostic rapide StudiosDB ==="
echo ""

# Colors
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

ISSUES=0

echo -e "${BLUE}1. Vérification des fichiers Vue...${NC}"

# Vérifier les fichiers Auth
auth_files=("Login" "Register" "ForgotPassword" "ResetPassword")
for file in "${auth_files[@]}"; do
    if [ -f "resources/js/Pages/Auth/${file}.vue" ]; then
        echo -e "  ${GREEN}✓${NC} ${file}.vue"
    else
        echo -e "  ${RED}✗${NC} ${file}.vue manquant!"
        ((ISSUES++))
    fi
done

echo ""
echo -e "${BLUE}2. Vérification du build...${NC}"

# Vérifier le manifest
if [ -f "public/build/.vite/manifest.json" ]; then
    echo -e "  ${GREEN}✓${NC} Manifest présent"
    
    # Vérifier la taille
    size=$(stat -c%s "public/build/.vite/manifest.json" 2>/dev/null || stat -f%z "public/build/.vite/manifest.json" 2>/dev/null)
    if [ "$size" -gt "100" ]; then
        echo -e "  ${GREEN}✓${NC} Manifest non vide ($size bytes)"
    else
        echo -e "  ${YELLOW}⚠${NC} Manifest trop petit ($size bytes)"
        ((ISSUES++))
    fi
    
    # Vérifier Login.vue dans le manifest
    if grep -q "Login.vue" public/build/.vite/manifest.json 2>/dev/null; then
        echo -e "  ${GREEN}✓${NC} Login.vue dans le manifest"
    else
        echo -e "  ${RED}✗${NC} Login.vue absent du manifest"
        ((ISSUES++))
    fi
else
    echo -e "  ${RED}✗${NC} Manifest absent - build nécessaire!"
    ((ISSUES++))
fi

# Vérifier les assets JS/CSS
if [ -d "public/build/assets" ]; then
    js_count=$(ls public/build/assets/*.js 2>/dev/null | wc -l)
    css_count=$(ls public/build/assets/*.css 2>/dev/null | wc -l)
    echo -e "  ${GREEN}✓${NC} Assets: $js_count JS, $css_count CSS"
else
    echo -e "  ${RED}✗${NC} Dossier assets absent"
    ((ISSUES++))
fi

echo ""
echo -e "${BLUE}3. Vérification du mode...${NC}"

# Vérifier hot reload
if [ -f "public/hot" ]; then
    echo -e "  ${YELLOW}⚠${NC} Mode HMR actif (dev)"
    echo -e "     Supprimez avec: rm public/hot"
    ((ISSUES++))
else
    echo -e "  ${GREEN}✓${NC} Mode production"
fi

# Vérifier APP_ENV
app_env=$(grep "^APP_ENV=" .env 2>/dev/null | cut -d'=' -f2)
app_debug=$(grep "^APP_DEBUG=" .env 2>/dev/null | cut -d'=' -f2)

if [ "$app_env" = "production" ]; then
    echo -e "  ${GREEN}✓${NC} APP_ENV=$app_env"
else
    echo -e "  ${YELLOW}⚠${NC} APP_ENV=$app_env (non production)"
fi

if [ "$app_debug" = "false" ]; then
    echo -e "  ${GREEN}✓${NC} APP_DEBUG=$app_debug"
else
    echo -e "  ${YELLOW}⚠${NC} APP_DEBUG=$app_debug (debug activé)"
fi

echo ""
echo -e "${BLUE}4. État Node/NPM...${NC}"

# Vérifier node_modules
if [ -d "node_modules" ]; then
    module_count=$(ls node_modules | wc -l)
    echo -e "  ${GREEN}✓${NC} node_modules présent ($module_count packages)"
else
    echo -e "  ${RED}✗${NC} node_modules absent - npm install nécessaire!"
    ((ISSUES++))
fi

# Vérifier versions
node_version=$(node -v 2>/dev/null || echo "Non installé")
npm_version=$(npm -v 2>/dev/null || echo "Non installé")
echo -e "  ${GREEN}✓${NC} Node: $node_version"
echo -e "  ${GREEN}✓${NC} NPM: $npm_version"

echo ""
echo "════════════════════════════════════════════════════"

if [ $ISSUES -eq 0 ]; then
    echo -e "${GREEN}✅ Aucun problème détecté!${NC}"
    echo ""
    echo "L'application devrait fonctionner correctement."
    echo "Démarrez avec: php artisan serve --port=8001"
else
    echo -e "${YELLOW}⚠ $ISSUES problème(s) détecté(s)${NC}"
    echo ""
    echo -e "${RED}Solution recommandée:${NC}"
    echo "  bash fix-vite-manifest.sh"
    echo ""
    echo -e "${YELLOW}Ou manuellement:${NC}"
    echo "  npm run build"
    echo "  php artisan serve --port=8001"
fi

echo ""
