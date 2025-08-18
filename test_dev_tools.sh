#!/bin/bash

# Test des outils de développement StudiosDB
# ===========================================

PROJECT_DIR="/home/studiosdb/studiosunisdb"
cd $PROJECT_DIR

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

echo -e "${BLUE}═══════════════════════════════════════${NC}"
echo -e "${BLUE}   TEST DES OUTILS DE DÉVELOPPEMENT${NC}"
echo -e "${BLUE}═══════════════════════════════════════${NC}"
echo ""

# 1. Laravel Telescope
echo -e "${YELLOW}1. Laravel Telescope${NC}"
if php artisan route:list | grep -q telescope; then
    echo -e "${GREEN}✅ Telescope installé${NC}"
    echo "   URL: http://127.0.0.1:8001/telescope"
else
    echo -e "${RED}❌ Telescope non installé${NC}"
fi

# 2. Laravel Debugbar
echo -e "\n${YELLOW}2. Laravel Debugbar${NC}"
if composer show | grep -q barryvdh/laravel-debugbar; then
    echo -e "${GREEN}✅ Debugbar installé${NC}"
    echo "   Activé automatiquement en mode debug"
    echo "   APP_DEBUG=true dans .env pour activer"
else
    echo -e "${RED}❌ Debugbar non installé${NC}"
fi

# 3. PHPStan
echo -e "\n${YELLOW}3. PHPStan${NC}"
if [ -f "vendor/bin/phpstan" ]; then
    echo -e "${GREEN}✅ PHPStan installé${NC}"
    echo "   Usage: vendor/bin/phpstan analyse"
    
    # Test rapide PHPStan
    echo "   Test rapide..."
    vendor/bin/phpstan analyse --level=0 app/Http/Controllers --no-progress 2>/dev/null && echo -e "   ${GREEN}✅ Analyse OK${NC}" || echo -e "   ${YELLOW}⚠️ Quelques avertissements${NC}"
else
    echo -e "${RED}❌ PHPStan non installé${NC}"
fi

# 4. Laravel Backup
echo -e "\n${YELLOW}4. Laravel Backup${NC}"
if composer show | grep -q spatie/laravel-backup; then
    echo -e "${GREEN}✅ Laravel Backup installé${NC}"
    echo "   Commandes disponibles:"
    php artisan list backup 2>/dev/null | grep backup: | sed 's/^/   /'
else
    echo -e "${RED}❌ Laravel Backup non installé${NC}"
fi

# Vérification des configurations
echo -e "\n${BLUE}═══════════════════════════════════════${NC}"
echo -e "${BLUE}   CONFIGURATIONS${NC}"
echo -e "${BLUE}═══════════════════════════════════════${NC}"

# Config files
echo -e "\n${YELLOW}Fichiers de configuration:${NC}"
[ -f "config/backup.php" ] && echo -e "${GREEN}✅ config/backup.php${NC}" || echo -e "${RED}❌ config/backup.php manquant${NC}"
[ -f "phpstan.neon" ] && echo -e "${GREEN}✅ phpstan.neon${NC}" || echo -e "${RED}❌ phpstan.neon manquant${NC}"

# Résumé
echo -e "\n${BLUE}═══════════════════════════════════════${NC}"
echo -e "${BLUE}   RÉSUMÉ${NC}"
echo -e "${BLUE}═══════════════════════════════════════${NC}"

INSTALLED=0
TOTAL=4

composer show | grep -q laravel/telescope && ((INSTALLED++))
composer show | grep -q barryvdh/laravel-debugbar && ((INSTALLED++))
[ -f "vendor/bin/phpstan" ] && ((INSTALLED++))
composer show | grep -q spatie/laravel-backup && ((INSTALLED++))

echo -e "\nOutils installés: ${GREEN}$INSTALLED/$TOTAL${NC}"

if [ $INSTALLED -eq $TOTAL ]; then
    echo -e "${GREEN}✅ Tous les outils sont installés!${NC}"
else
    echo -e "${YELLOW}⚠️ Certains outils manquent${NC}"
fi

echo -e "\n${BLUE}═══════════════════════════════════════${NC}"
echo -e "${BLUE}   ACTIONS RECOMMANDÉES${NC}"
echo -e "${BLUE}═══════════════════════════════════════${NC}"

echo -e "\n1. Tester Laravel Telescope:"
echo "   Accédez à: http://127.0.0.1:8001/telescope"

echo -e "\n2. Activer Debugbar:"
echo "   Assurez-vous que APP_DEBUG=true dans .env"

echo -e "\n3. Analyser le code avec PHPStan:"
echo "   vendor/bin/phpstan analyse"

echo -e "\n4. Créer un backup:"
echo "   php artisan backup:run"

echo ""
