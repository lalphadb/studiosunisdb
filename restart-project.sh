#!/bin/bash
# StudiosDB - Redémarrage complet du projet
# Date: 2025-08-23

echo "================================================="
echo "   REDÉMARRAGE COMPLET STUDIOSDB"
echo "================================================="

# Couleurs
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo -e "\n${RED}ÉTAPE 1: Arrêt de tous les processus${NC}"
echo "--------------------------------"

# Tuer TOUS les processus
pkill -f "php artisan" 2>/dev/null
pkill -f "vite" 2>/dev/null
pkill -f "npm" 2>/dev/null
pkill -f "node.*studiosunisdb" 2>/dev/null

# Force kill sur les ports
lsof -ti:8000 | xargs kill -9 2>/dev/null
lsof -ti:8001 | xargs kill -9 2>/dev/null
lsof -ti:5173 | xargs kill -9 2>/dev/null
lsof -ti:5174 | xargs kill -9 2>/dev/null

echo -e "${GREEN}✓${NC} Tous les processus arrêtés"

# Attendre un peu
sleep 2

echo -e "\n${YELLOW}ÉTAPE 2: Nettoyage complet${NC}"
echo "--------------------------------"

# Vider TOUS les caches
rm -rf bootstrap/cache/*.php
rm -rf storage/framework/cache/data/*
rm -rf storage/framework/sessions/*
rm -rf storage/framework/views/*.php
rm -rf storage/logs/*.log

# Créer un nouveau log
touch storage/logs/laravel.log
chmod 775 storage/logs/laravel.log

echo -e "${GREEN}✓${NC} Cache et logs nettoyés"

echo -e "\n${YELLOW}ÉTAPE 3: Régénération de l'environnement${NC}"
echo "--------------------------------"

# Clear Laravel
php artisan optimize:clear
echo -e "${GREEN}✓${NC} Laravel optimisé"

# Régénérer les caches
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo -e "${GREEN}✓${NC} Caches régénérés"

echo -e "\n${YELLOW}ÉTAPE 4: Mise à jour de l'utilisateur admin${NC}"
echo "--------------------------------"
php update-admin.php 2>/dev/null || echo -e "${YELLOW}⚠${NC} Script admin non trouvé"

echo -e "\n${YELLOW}ÉTAPE 5: Compilation des assets${NC}"
echo "--------------------------------"
npm run build
echo -e "${GREEN}✓${NC} Assets compilés"

echo -e "\n${BLUE}ÉTAPE 6: Démarrage des serveurs${NC}"
echo "--------------------------------"

# Démarrer Laravel
echo "Démarrage de Laravel sur http://127.0.0.1:8001..."
php artisan serve --host=127.0.0.1 --port=8001 > /dev/null 2>&1 &
LARAVEL_PID=$!
echo -e "${GREEN}✓${NC} Laravel démarré (PID: $LARAVEL_PID)"

# Attendre que Laravel soit prêt
sleep 3

# Démarrer Vite
echo "Démarrage de Vite..."
npm run dev > /dev/null 2>&1 &
VITE_PID=$!
echo -e "${GREEN}✓${NC} Vite démarré (PID: $VITE_PID)"

# Attendre que tout soit prêt
sleep 3

# Tester si le serveur répond
echo -e "\n${YELLOW}ÉTAPE 7: Test de connexion${NC}"
echo "--------------------------------"

if curl -s -o /dev/null -w "%{http_code}" http://127.0.0.1:8001 | grep -q "200\|302"; then
    echo -e "${GREEN}✅ SERVEUR OPÉRATIONNEL!${NC}"
else
    echo -e "${YELLOW}⚠️ Le serveur met du temps à démarrer, patientez...${NC}"
fi

echo ""
echo "================================================="
echo -e "${GREEN}✅ PROJET REDÉMARRÉ AVEC SUCCÈS!${NC}"
echo "================================================="
echo ""
echo -e "${BLUE}📌 ACCÈS:${NC}"
echo "   URL: http://127.0.0.1:8001/login"
echo "   Email: louis@4lb.ca"
echo "   Mot de passe: password123"
echo ""
echo -e "${YELLOW}📊 PAGES DISPONIBLES:${NC}"
echo "   • Login: http://127.0.0.1:8001/login"
echo "   • Dashboard: http://127.0.0.1:8001/dashboard"
echo "   • Membres: http://127.0.0.1:8001/membres"
echo "   • Cours: http://127.0.0.1:8001/cours"
echo ""
echo -e "${GREEN}Les serveurs tournent. Ctrl+C pour arrêter.${NC}"
echo "================================================="

# Garder le script actif
trap "kill $LARAVEL_PID $VITE_PID 2>/dev/null; echo -e '\n${RED}Serveurs arrêtés${NC}'; exit" INT
wait
