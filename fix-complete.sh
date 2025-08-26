#!/bin/bash
# StudiosDB v6 - Fix COMPLET Navigation & Auth
# Date: 2025-08-23

echo "================================================="
echo "   StudiosDB v6 - FIX COMPLET NAVIGATION"
echo "================================================="

# Couleurs
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo -e "\n${YELLOW}ÉTAPE 1: Arrêt des serveurs existants${NC}"
echo "--------------------------------"
killall php 2>/dev/null
killall node 2>/dev/null
echo -e "${GREEN}✓${NC} Serveurs arrêtés"

echo -e "\n${YELLOW}ÉTAPE 2: Nettoyage complet${NC}"
echo "--------------------------------"
php artisan optimize:clear
rm -rf bootstrap/cache/*.php
rm -rf storage/framework/sessions/*
rm -rf storage/framework/cache/data/*
echo -e "${GREEN}✓${NC} Cache et sessions nettoyés"

echo -e "\n${YELLOW}ÉTAPE 3: Création utilisateur admin${NC}"
echo "--------------------------------"
php fix-auth-navigation.php

echo -e "\n${YELLOW}ÉTAPE 4: Compilation des assets${NC}"
echo "--------------------------------"
npm run build
echo -e "${GREEN}✓${NC} Assets compilés"

echo -e "\n${YELLOW}ÉTAPE 5: Régénération des caches${NC}"
echo "--------------------------------"
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo -e "${GREEN}✓${NC} Caches régénérés"

echo -e "\n${YELLOW}ÉTAPE 6: Démarrage des serveurs${NC}"
echo "--------------------------------"

# Démarrer Laravel en arrière-plan
echo "Démarrage du serveur Laravel..."
php artisan serve --host=localhost --port=8000 > /dev/null 2>&1 &
LARAVEL_PID=$!
echo -e "${GREEN}✓${NC} Laravel démarré (PID: $LARAVEL_PID)"

# Attendre que Laravel soit prêt
sleep 3

# Démarrer Vite en arrière-plan
echo "Démarrage de Vite..."
npm run dev > /dev/null 2>&1 &
VITE_PID=$!
echo -e "${GREEN}✓${NC} Vite démarré (PID: $VITE_PID)"

# Attendre que tout soit prêt
sleep 3

echo ""
echo "================================================="
echo -e "${GREEN}✅ SYSTÈME PRÊT !${NC}"
echo "================================================="
echo ""
echo -e "${BLUE}📌 CONNEXION:${NC}"
echo "   1. Ouvrir: http://localhost:8000/login"
echo "   2. Email: admin@studiosdb.ca"
echo "   3. Mot de passe: AdminStudios2025!"
echo ""
echo -e "${BLUE}📊 PAGES DISPONIBLES APRÈS CONNEXION:${NC}"
echo "   • Dashboard : http://localhost:8000/dashboard"
echo "   • Membres : http://localhost:8000/membres"
echo "   • Cours : http://localhost:8000/cours"
echo "   • Présences : http://localhost:8000/presences/tablette"
echo ""
echo -e "${YELLOW}⚠️ IMPORTANT:${NC}"
echo "   • Utilisez localhost:8000 (pas 127.0.0.1)"
echo "   • Si erreur persiste: navigation privée"
echo "   • Pour arrêter: Ctrl+C"
echo ""
echo "================================================="
echo ""
echo -e "${GREEN}Les serveurs tournent. Appuyez sur Ctrl+C pour arrêter.${NC}"

# Garder le script actif
trap "kill $LARAVEL_PID $VITE_PID 2>/dev/null; echo -e '\n${RED}Serveurs arrêtés${NC}'; exit" INT
wait
