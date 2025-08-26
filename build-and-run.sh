#!/bin/bash

# StudiosDB - Build après correction
# Purpose: Recompiler après correction de l'erreur de syntaxe

echo "=== Compilation après correction ==="
echo ""

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

# 1. Arrêter les serveurs
pkill -f "artisan serve" 2>/dev/null
rm -f public/hot

echo "1. Nettoyage..."
rm -rf public/build
php artisan optimize:clear > /dev/null 2>&1
echo -e "${GREEN}✓${NC} Cache nettoyé"

echo ""
echo "2. Compilation des assets..."
npm run build

# Vérifier si la compilation a réussi
if [ $? -eq 0 ]; then
    echo ""
    echo -e "${GREEN}✅ BUILD RÉUSSI!${NC}"
    
    # Vérifier le manifest
    if [ -f "public/build/.vite/manifest.json" ]; then
        echo ""
        echo "Vérification du manifest:"
        echo -n "  • Fichiers JS: "
        ls public/build/assets/*.js 2>/dev/null | wc -l
        echo -n "  • Fichiers CSS: "
        ls public/build/assets/*.css 2>/dev/null | wc -l
    fi
    
    echo ""
    echo "3. Démarrage du serveur..."
    php artisan serve --host=127.0.0.1 --port=8001 &
    SERVER_PID=$!
    
    sleep 2
    
    echo ""
    echo "════════════════════════════════════════════════════"
    echo -e "${GREEN}✅ APPLICATION PRÊTE!${NC}"
    echo "════════════════════════════════════════════════════"
    echo ""
    echo "📍 URL: http://127.0.0.1:8001"
    echo "📍 Dashboard: http://127.0.0.1:8001/dashboard"
    echo ""
    echo "Pour arrêter: Ctrl+C ou kill $SERVER_PID"
    
    trap "kill $SERVER_PID 2>/dev/null; exit" INT
    wait $SERVER_PID
else
    echo ""
    echo -e "${RED}✗ ERREUR DE BUILD!${NC}"
    echo ""
    echo "Vérifiez les erreurs ci-dessus."
    exit 1
fi
