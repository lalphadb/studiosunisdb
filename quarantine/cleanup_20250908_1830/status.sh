#!/bin/bash

# StudiosDB Status Monitor
# Purpose: Affiche l'état actuel de l'application

clear
echo "╔════════════════════════════════════════════════════════════╗"
echo "║              StudiosDB - Moniteur d'état                  ║"
echo "╚════════════════════════════════════════════════════════════╝"
echo ""

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to check status
check_status() {
    local name=$1
    local command=$2
    local expected=$3
    
    result=$(eval $command 2>/dev/null)
    
    if [[ "$result" == *"$expected"* ]]; then
        echo -e "${GREEN}✓${NC} $name: OK"
        return 0
    else
        echo -e "${RED}✗${NC} $name: FAILED"
        return 1
    fi
}

# System checks
echo "📊 État du système:"
echo "────────────────────"

# PHP Version
php_version=$(php -v | head -1 | cut -d' ' -f2)
echo -e "${GREEN}✓${NC} PHP: $php_version"

# Node Version
node_version=$(node -v 2>/dev/null || echo "Non installé")
echo -e "${GREEN}✓${NC} Node: $node_version"

# NPM Version
npm_version=$(npm -v 2>/dev/null || echo "Non installé")
echo -e "${GREEN}✓${NC} NPM: $npm_version"

echo ""
echo "🔧 Configuration Laravel:"
echo "────────────────────"

# Laravel Version
laravel_version=$(php artisan --version 2>/dev/null | cut -d' ' -f3 || echo "Erreur")
echo -e "${GREEN}✓${NC} Laravel: $laravel_version"

# APP_ENV
app_env=$(grep "^APP_ENV=" .env 2>/dev/null | cut -d'=' -f2 || echo "Non défini")
if [ "$app_env" = "production" ]; then
    echo -e "${GREEN}✓${NC} Environnement: $app_env"
else
    echo -e "${YELLOW}⚠${NC} Environnement: $app_env (non-production)"
fi

# APP_DEBUG
app_debug=$(grep "^APP_DEBUG=" .env 2>/dev/null | cut -d'=' -f2 || echo "Non défini")
if [ "$app_debug" = "false" ]; then
    echo -e "${GREEN}✓${NC} Debug: Désactivé"
else
    echo -e "${YELLOW}⚠${NC} Debug: Activé (désactiver en production)"
fi

# APP_KEY
app_key=$(grep "^APP_KEY=" .env 2>/dev/null | cut -d'=' -f2)
if [ -n "$app_key" ] && [ "$app_key" != "" ]; then
    echo -e "${GREEN}✓${NC} APP_KEY: Définie"
else
    echo -e "${RED}✗${NC} APP_KEY: Manquante"
fi

echo ""
echo "💾 Base de données:"
echo "────────────────────"

# Database connection
db_test=$(php artisan tinker --execute="echo DB::connection()->getPdo() ? 'connected' : 'failed';" 2>&1 | grep -E "connected|failed" | head -1)
if [ "$db_test" = "connected" ]; then
    echo -e "${GREEN}✓${NC} Connexion: Établie"
    
    # Count tables
    table_count=$(php artisan tinker --execute="echo count(DB::select('SHOW TABLES'));" 2>&1 | grep -E "^[0-9]+$" | head -1)
    echo -e "${GREEN}✓${NC} Tables: $table_count"
    
    # Check migrations
    migration_status=$(php artisan migrate:status 2>&1 | grep -c "Ran" || echo "0")
    echo -e "${GREEN}✓${NC} Migrations exécutées: $migration_status"
else
    echo -e "${RED}✗${NC} Connexion: Échouée"
fi

echo ""
echo "📦 Assets:"
echo "────────────────────"

# Check compiled assets
if [ -f "public/build/.vite/manifest.json" ]; then
    echo -e "${GREEN}✓${NC} Assets compilés: OUI"
    
    # Get build time
    build_time=$(stat -c %y "public/build/.vite/manifest.json" 2>/dev/null | cut -d'.' -f1)
    echo -e "${GREEN}✓${NC} Dernière compilation: $build_time"
else
    echo -e "${RED}✗${NC} Assets compilés: NON"
fi

# Check hot file
if [ -f "public/hot" ]; then
    echo -e "${YELLOW}⚠${NC} Mode HMR: Actif (dev)"
else
    echo -e "${GREEN}✓${NC} Mode HMR: Inactif (prod)"
fi

echo ""
echo "🌐 Serveurs:"
echo "────────────────────"

# Check Laravel server
laravel_pid=$(pgrep -f "artisan serve" | head -1)
if [ -n "$laravel_pid" ]; then
    laravel_port=$(ps aux | grep "$laravel_pid" | grep -oP "port=\K[0-9]+" | head -1)
    echo -e "${GREEN}✓${NC} Laravel: Actif (PID: $laravel_pid, Port: ${laravel_port:-8000})"
    
    # Test endpoint
    if curl -s -o /dev/null -w "%{http_code}" "http://127.0.0.1:${laravel_port:-8000}/" | grep -q "200\|302"; then
        echo -e "${GREEN}✓${NC} Endpoint /: Accessible"
    else
        echo -e "${RED}✗${NC} Endpoint /: Inaccessible"
    fi
else
    echo -e "${RED}✗${NC} Laravel: Arrêté"
fi

# Check Vite server
vite_pid=$(pgrep -f "vite" | head -1)
if [ -n "$vite_pid" ]; then
    echo -e "${GREEN}✓${NC} Vite: Actif (PID: $vite_pid)"
else
    echo -e "${YELLOW}⚠${NC} Vite: Arrêté (normal en production)"
fi

echo ""
echo "📁 Permissions:"
echo "────────────────────"

# Check storage permissions
if [ -w "storage" ]; then
    echo -e "${GREEN}✓${NC} storage/: Écriture OK"
else
    echo -e "${RED}✗${NC} storage/: Pas d'écriture"
fi

# Check cache permissions
if [ -w "bootstrap/cache" ]; then
    echo -e "${GREEN}✓${NC} bootstrap/cache/: Écriture OK"
else
    echo -e "${RED}✗${NC} bootstrap/cache/: Pas d'écriture"
fi

echo ""
echo "📝 Logs récents:"
echo "────────────────────"

# Check for recent errors
recent_errors=$(tail -100 storage/logs/laravel.log 2>/dev/null | grep -c "ERROR" || echo "0")
if [ "$recent_errors" -eq "0" ]; then
    echo -e "${GREEN}✓${NC} Aucune erreur récente"
else
    echo -e "${YELLOW}⚠${NC} $recent_errors erreurs dans les 100 dernières lignes"
    
    # Show last error
    last_error=$(tail -100 storage/logs/laravel.log 2>/dev/null | grep "ERROR" | tail -1 | cut -c1-80)
    if [ -n "$last_error" ]; then
        echo "   Dernière: $last_error..."
    fi
fi

echo ""
echo "────────────────────────────────────────────────────────────"
echo ""
echo "📌 Actions recommandées:"

# Recommendations based on status
if [ ! -f "public/build/.vite/manifest.json" ]; then
    echo "  • Compiler les assets: npm run build"
fi

if [ -z "$laravel_pid" ]; then
    echo "  • Démarrer le serveur: bash start-server.sh"
fi

if [ "$recent_errors" -gt "0" ]; then
    echo "  • Vérifier les logs: tail -50 storage/logs/laravel.log"
fi

if [ "$app_debug" = "true" ] && [ "$app_env" = "production" ]; then
    echo "  • Désactiver debug en production: APP_DEBUG=false dans .env"
fi

echo ""
echo "💡 Commande rapide: bash quickstart.sh"
echo ""
