#!/bin/bash

# System Monitor Tool pour StudiosDB (Version simplifiée)
# ========================================================

PROJECT_DIR="/home/studiosdb/studiosunisdb"
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

function header() {
    echo -e "${BLUE}═══════════════════════════════════════${NC}"
    echo -e "${BLUE}   $1${NC}"
    echo -e "${BLUE}═══════════════════════════════════════${NC}"
}

function disk_usage() {
    header "💾 UTILISATION DISQUE"
    
    echo "Espace total:"
    df -h / | tail -1 | awk '{print "  Total: "$2" | Utilisé: "$3" ("$5") | Libre: "$4}'
    
    echo -e "\nDossiers principaux:"
    du -sh $PROJECT_DIR 2>/dev/null | awk '{print "  Projet: "$1}'
    du -sh $PROJECT_DIR/storage 2>/dev/null | awk '{print "  Storage: "$1}'
    du -sh $PROJECT_DIR/node_modules 2>/dev/null | awk '{print "  Node_modules: "$1}'
    du -sh $PROJECT_DIR/vendor 2>/dev/null | awk '{print "  Vendor: "$1}'
}

function process_status() {
    header "⚙️ PROCESSUS ACTIFS"
    
    echo "Processus Laravel/PHP:"
    ps aux | grep -E "artisan|php-fpm" | grep -v grep | wc -l | awk '{print "  Processus PHP: "$1}'
    ps aux | grep -E "vite|node" | grep -v grep | wc -l | awk '{print "  Processus Node/Vite: "$1}'
    
    echo -e "\nPorts actifs:"
    echo "  Vérifiez manuellement avec: netstat -tlnp"
}

function laravel_status() {
    header "🚀 ÉTAT LARAVEL"
    
    cd $PROJECT_DIR
    
    # Version
    echo "Version:"
    php artisan --version 2>/dev/null | awk '{print "  "$0}'
    
    # Cache status
    echo -e "\nÉtat des caches:"
    [ -f bootstrap/cache/config.php ] && echo -e "  ${GREEN}✅${NC} Config cache" || echo -e "  ${YELLOW}⚠️${NC} Config non caché"
    [ -f bootstrap/cache/routes-v7.php ] && echo -e "  ${GREEN}✅${NC} Routes cache" || echo -e "  ${YELLOW}⚠️${NC} Routes non cachées"
    [ -d storage/framework/views ] && [ "$(ls -A storage/framework/views)" ] && echo -e "  ${GREEN}✅${NC} Views cache" || echo -e "  ${YELLOW}⚠️${NC} Views non cachées"
    
    # Test de connexion DB
    echo -e "\nConnexion base de données:"
    php artisan tinker --execute="try { \DB::connection()->getPdo(); echo '  ✅ Connexion OK'; } catch (\Exception \$e) { echo '  ❌ Erreur de connexion'; }" 2>/dev/null
}

function logs_check() {
    header "📝 DERNIERS LOGS"
    
    echo "Laravel logs (10 dernières lignes):"
    if [ -f "$PROJECT_DIR/storage/logs/laravel.log" ]; then
        tail -10 "$PROJECT_DIR/storage/logs/laravel.log" | sed 's/^/  /'
    else
        echo "  Aucun log Laravel trouvé"
    fi
}

function security_check() {
    header "🔒 VÉRIFICATIONS SÉCURITÉ"
    
    echo "Fichier .env:"
    if [ -f "$PROJECT_DIR/.env" ]; then
        if grep -q "APP_DEBUG=true" "$PROJECT_DIR/.env"; then
            echo -e "  ${YELLOW}⚠️${NC} Mode DEBUG activé"
        else
            echo -e "  ${GREEN}✅${NC} Mode DEBUG désactivé"
        fi
        
        if grep -q "APP_ENV=production" "$PROJECT_DIR/.env"; then
            echo -e "  ${GREEN}✅${NC} Environnement PRODUCTION"
        else
            echo -e "  ${YELLOW}⚠️${NC} Environnement non-production"
        fi
    fi
    
    echo -e "\nPermissions:"
    ls -la $PROJECT_DIR/.env | awk '{print "  .env: "$1" "$3":"$4}'
    ls -ld $PROJECT_DIR/storage | awk '{print "  storage: "$1" "$3":"$4}'
}

function quick_health() {
    header "🏥 SANTÉ RAPIDE DU SYSTÈME"
    
    # Disk
    echo -n "Disque: "
    df -h / | tail -1 | awk '{print $5" utilisé"}'
    
    # Laravel
    echo -n "Laravel: "
    cd $PROJECT_DIR && php artisan --version 2>/dev/null | awk '{print $3}' || echo "Erreur"
    
    # Database
    echo -n "Database: "
    cd $PROJECT_DIR && php artisan tinker --execute="try { \DB::connection()->getPdo(); echo 'Connectée'; } catch (\Exception \$e) { echo 'Déconnectée'; }" 2>/dev/null
    
    # Processus
    echo -n "Processus actifs: "
    ps aux | grep -E "artisan|vite|php" | grep -v grep | wc -l
}

# Menu principal
case "${1:-menu}" in
    disk)
        disk_usage
        ;;
    process)
        process_status
        ;;
    laravel)
        laravel_status
        ;;
    logs)
        logs_check
        ;;
    security)
        security_check
        ;;
    health)
        quick_health
        ;;
    all)
        quick_health
        echo ""
        process_status
        echo ""
        disk_usage
        echo ""
        laravel_status
        echo ""
        logs_check
        echo ""
        security_check
        ;;
    *)
        header "📊 SYSTEM MONITOR - MENU"
        echo "Usage: ./monitor.sh [commande]"
        echo ""
        echo "Commandes disponibles:"
        echo "  health    - Vérification rapide"
        echo "  disk      - Utilisation du disque"
        echo "  process   - État des processus"
        echo "  laravel   - État de Laravel"
        echo "  logs      - Vérifier les logs"
        echo "  security  - Vérifications de sécurité"
        echo "  all       - Rapport complet"
        echo ""
        echo "Exemple: ./monitor.sh health"
        ;;
esac
