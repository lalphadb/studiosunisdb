#!/bin/bash
# StudiosDB - Kill tous les processus serveurs
# Date: 2025-08-23

echo "================================================="
echo "   ARRÊT DE TOUS LES SERVEURS STUDIOSDB"
echo "================================================="

# Couleurs
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "\n${YELLOW}Recherche des processus en cours...${NC}\n"

# Afficher les processus avant de les tuer
echo "Processus PHP artisan serve:"
ps aux | grep "php artisan serve" | grep -v grep

echo -e "\nProcessus Vite/Node:"
ps aux | grep -E "vite|npm run dev" | grep -v grep

echo -e "\nProcessus PHP en général:"
ps aux | grep "php" | grep -v grep | head -5

echo -e "\n${RED}Arrêt des processus...${NC}\n"

# Tuer les processus PHP artisan serve
echo "1. Arrêt de PHP artisan serve..."
pkill -f "php artisan serve" 2>/dev/null
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓${NC} PHP artisan serve arrêté"
else
    echo -e "${YELLOW}⚠${NC} Aucun processus PHP artisan serve trouvé"
fi

# Tuer les processus Vite
echo "2. Arrêt de Vite..."
pkill -f "vite" 2>/dev/null
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓${NC} Vite arrêté"
else
    echo -e "${YELLOW}⚠${NC} Aucun processus Vite trouvé"
fi

# Tuer les processus npm run dev
echo "3. Arrêt de npm run dev..."
pkill -f "npm run dev" 2>/dev/null
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓${NC} npm run dev arrêté"
else
    echo -e "${YELLOW}⚠${NC} Aucun processus npm run dev trouvé"
fi

# Tuer les processus node restants liés à Vite
echo "4. Arrêt des processus Node restants..."
pkill -f "node.*vite" 2>/dev/null
pkill -f "node.*studiosunisdb" 2>/dev/null
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓${NC} Processus Node arrêtés"
else
    echo -e "${YELLOW}⚠${NC} Aucun processus Node restant"
fi

# Libérer les ports
echo -e "\n${YELLOW}5. Libération des ports...${NC}"

# Libérer le port 8000 (Laravel)
lsof -ti:8000 | xargs kill -9 2>/dev/null
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓${NC} Port 8000 libéré"
else
    echo -e "${YELLOW}⚠${NC} Port 8000 déjà libre"
fi

# Libérer le port 5173 (Vite)
lsof -ti:5173 | xargs kill -9 2>/dev/null
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓${NC} Port 5173 libéré"
else
    echo -e "${YELLOW}⚠${NC} Port 5173 déjà libre"
fi

echo -e "\n${YELLOW}Vérification finale...${NC}\n"

# Vérifier qu'il ne reste plus de processus
REMAINING_PHP=$(ps aux | grep -c "php artisan serve" | grep -v grep)
REMAINING_VITE=$(ps aux | grep -c "vite" | grep -v grep)
REMAINING_NODE=$(ps aux | grep -c "npm run dev" | grep -v grep)

if [ "$REMAINING_PHP" = "0" ] && [ "$REMAINING_VITE" = "0" ] && [ "$REMAINING_NODE" = "0" ]; then
    echo -e "${GREEN}✅ TOUS LES PROCESSUS ONT ÉTÉ ARRÊTÉS${NC}"
else
    echo -e "${YELLOW}⚠️ Certains processus pourraient encore être actifs${NC}"
    echo "Processus restants:"
    ps aux | grep -E "php artisan|vite|npm run" | grep -v grep
fi

echo ""
echo "================================================="
echo -e "${GREEN}Nettoyage terminé !${NC}"
echo "================================================="
echo ""
echo "Pour redémarrer les serveurs:"
echo "  php artisan serve"
echo "  npm run dev (dans un autre terminal)"
echo ""
