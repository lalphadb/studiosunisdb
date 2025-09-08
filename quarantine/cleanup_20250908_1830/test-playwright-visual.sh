#!/bin/bash

# =================================================================
# 🎭 SCRIPT TESTS PLAYWRIGHT VISUELS - STUDIOSDB MODULE MEMBRES
# =================================================================
# Lancement facile des tests Playwright en mode visuel temps réel
# =================================================================

echo "🎭 Tests Playwright Visuels - Module Membres StudiosDB"
echo "===================================================="

# Couleurs
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Vérifier que le serveur Laravel tourne
echo -e "${BLUE}🔍 Vérification serveur Laravel...${NC}"
if curl -s http://localhost:8000 > /dev/null; then
    echo -e "${GREEN}✅ Serveur Laravel actif sur http://localhost:8000${NC}"
else
    echo -e "${RED}❌ Serveur Laravel non accessible${NC}"
    echo -e "${YELLOW}💡 Lancez 'php artisan serve' dans un autre terminal${NC}"
    exit 1
fi

echo ""
echo -e "${BLUE}🎯 Modes de test disponibles :${NC}"
echo ""
echo "1) 🖥️  Mode HEADED (fenêtre visible)"
echo "2) 🎮 Mode UI (panneau interactif)"
echo "3) 🔍 Mode INSPECTOR (pas à pas + DOM)"
echo "4) 🐌 Mode SLOW MOTION (super lent)"
echo "5) 📱 Mode MOBILE (responsive)"
echo "6) 🎨 Test COMPLET (tous les tests)"
echo "7) 🧪 Test PERSONNALISÉ (commande libre)"
echo ""

read -p "Choisissez un mode (1-7): " choice

case $choice in
    1)
        echo -e "${GREEN}🖥️ Lancement mode HEADED (fenêtre visible)${NC}"
        npx playwright test membres-visual --headed --project=chromium
        ;;
    2)
        echo -e "${GREEN}🎮 Lancement mode UI (panneau interactif)${NC}"
        echo -e "${YELLOW}💡 Une interface web va s'ouvrir pour contrôler les tests${NC}"
        npx playwright test membres-visual --ui
        ;;
    3)
        echo -e "${GREEN}🔍 Lancement mode INSPECTOR (pas à pas)${NC}"
        echo -e "${YELLOW}💡 Utilisez les contrôles pour avancer pas à pas${NC}"
        PWDEBUG=1 npx playwright test membres-visual --project=chromium
        ;;
    4)
        echo -e "${GREEN}🐌 Lancement mode SLOW MOTION (super lent)${NC}"
        npx playwright test membres-visual --headed --project=chromium --workers=1 --timeout=0 --retries=0
        ;;
    5)
        echo -e "${GREEN}📱 Lancement mode MOBILE (responsive)${NC}"
        npx playwright test membres-visual --headed --project="Mobile Chrome"
        ;;
    6)
        echo -e "${GREEN}🎨 Lancement test COMPLET${NC}"
        npx playwright test membres-visual --headed --project=chromium --reporter=html
        echo -e "${BLUE}📊 Rapport HTML généré dans playwright-report/${NC}"
        ;;
    7)
        echo -e "${YELLOW}🧪 Mode PERSONNALISÉ${NC}"
        echo "Commandes disponibles :"
        echo "  npx playwright test membres-visual --headed"
        echo "  npx playwright test membres-visual --ui"
        echo "  PWDEBUG=1 npx playwright test membres-visual"
        echo "  npx playwright test membres-visual --trace=on"
        echo ""
        read -p "Entrez votre commande : " custom_command
        eval $custom_command
        ;;
    *)
        echo -e "${RED}❌ Choix invalide${NC}"
        exit 1
        ;;
esac

echo ""
echo -e "${GREEN}✅ Tests terminés !${NC}"
echo -e "${BLUE}📁 Résultats disponibles dans : test-results/${NC}"
echo -e "${BLUE}📊 Rapports HTML dans : playwright-report/${NC}"

# Proposer d'ouvrir les traces si elles existent
if ls test-results/*.zip 1> /dev/null 2>&1; then
    echo ""
    read -p "🔍 Voulez-vous ouvrir les traces de debug ? (y/n): " open_trace
    if [[ $open_trace == "y" || $open_trace == "Y" ]]; then
        npx playwright show-trace test-results/*.zip
    fi
fi

echo -e "${YELLOW}💡 Pour relancer : ./test-playwright-visual.sh${NC}"
