#!/bin/bash

# Quick Test Script pour StudiosDB
# ==================================

PROJECT_DIR="/home/studiosdb/studiosunisdb"
cd $PROJECT_DIR

GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

echo -e "${BLUE}═══════════════════════════════════════${NC}"
echo -e "${BLUE}   TESTS RAPIDES STUDIOSDB${NC}"
echo -e "${BLUE}═══════════════════════════════════════${NC}"
echo ""

# 1. Telescope
echo -e "${YELLOW}1. Test Telescope:${NC}"
if php artisan route:list | grep -q telescope; then
    echo -e "${GREEN}✅ Telescope OK${NC}"
    echo "   URL: http://127.0.0.1:8001/telescope"
else
    echo "❌ Telescope non trouvé"
fi

# 2. Debugbar
echo -e "\n${YELLOW}2. Test Debugbar:${NC}"
if grep -q "APP_DEBUG=true" .env; then
    echo -e "${GREEN}✅ Debugbar activé${NC}"
    echo "   Visible en bas de chaque page"
else
    echo "❌ Debugbar désactivé (APP_DEBUG=false)"
fi

# 3. PHPStan (simplifié)
echo -e "\n${YELLOW}3. Test PHPStan:${NC}"
if [ -f "vendor/bin/phpstan" ]; then
    echo -e "${GREEN}✅ PHPStan installé${NC}"
    echo "   Pour analyser: php vendor/bin/phpstan analyse app/Http/Controllers --level=0"
else
    echo "❌ PHPStan non installé"
fi

# 4. Backup
echo -e "\n${YELLOW}4. Test Backup:${NC}"
echo "Création d'un backup de test..."
php artisan backup:run --only-db --disable-notifications 2>/dev/null
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✅ Backup créé avec succès${NC}"
    # Afficher le dernier backup
    LAST_BACKUP=$(ls -t storage/app/Studios-Unis-St‑Émile/*.zip 2>/dev/null | head -1)
    if [ ! -z "$LAST_BACKUP" ]; then
        SIZE=$(du -h "$LAST_BACKUP" | cut -f1)
        echo "   Dernier backup: $SIZE"
    fi
else
    echo "❌ Erreur lors du backup"
fi

# 5. Vérifier les services
echo -e "\n${YELLOW}5. Services actifs:${NC}"
ps aux | grep -q "[a]rtisan serve" && echo -e "${GREEN}✅ Laravel Server${NC}" || echo "❌ Laravel Server"
ps aux | grep -q "[v]ite" && echo -e "${GREEN}✅ Vite${NC}" || echo "❌ Vite"

echo -e "\n${BLUE}═══════════════════════════════════════${NC}"
echo -e "${BLUE}   COMMANDES UTILES${NC}"
echo -e "${BLUE}═══════════════════════════════════════${NC}"
echo ""
echo "📌 Accès rapide:"
echo "  • Telescope: firefox http://127.0.0.1:8001/telescope"
echo "  • Application: firefox http://127.0.0.1:8001"
echo ""
echo "🔧 Commandes:"
echo "  • Backup DB: php artisan backup:run --only-db"
echo "  • Backup complet: php artisan backup:run"
echo "  • Liste backups: php artisan backup:list"
echo "  • Nettoyer Telescope: php artisan telescope:clear"
echo "  • PHPStan simple: php vendor/bin/phpstan analyse app/Http/Controllers --level=0"
echo ""
