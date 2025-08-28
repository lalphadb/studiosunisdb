#!/bin/bash

# StudiosDB - Quick Fix & Start
# Purpose: Solution rapide - compile et démarre

echo "=== StudiosDB Quick Fix ==="
echo ""

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

# 1. Kill existing
pkill -f "artisan serve" 2>/dev/null
pkill -f "vite" 2>/dev/null
rm -f public/hot

echo "🧹 Nettoyage..."
php artisan optimize:clear > /dev/null 2>&1

echo "🔨 Compilation des assets..."
npm run build

if [ -f "public/build/.vite/manifest.json" ]; then
    echo -e "${GREEN}✓${NC} Build réussi!"
    
    echo "🚀 Démarrage du serveur..."
    php artisan serve --host=127.0.0.1 --port=8001 &
    SERVER_PID=$!
    
    sleep 2
    
    echo ""
    echo "════════════════════════════════════════════"
    echo -e "${GREEN}✅ Application démarrée!${NC}"
    echo "════════════════════════════════════════════"
    echo ""
    echo "📍 URL: http://127.0.0.1:8001"
    echo "📍 Login: http://127.0.0.1:8001/login"
    echo ""
    echo "Pour arrêter: Ctrl+C"
    
    trap "kill $SERVER_PID 2>/dev/null; exit" INT
    wait $SERVER_PID
else
    echo -e "${YELLOW}⚠${NC} Erreur de compilation!"
    echo "Essayez: npm install && npm run build"
fi
