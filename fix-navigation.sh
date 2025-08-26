#!/bin/bash
# Fix navigation StudiosDB v6
# Date: 2025-08-23

echo "================================================="
echo "   StudiosDB v6 - Fix Navigation & Auth"
echo "================================================="

# Couleurs
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "\n${YELLOW}1. Nettoyage du cache${NC}"
echo "--------------------------------"
php artisan optimize:clear
echo -e "${GREEN}✓${NC} Cache vidé"

echo -e "\n${YELLOW}2. Création de l'utilisateur admin${NC}"
echo "--------------------------------"
php create-admin.php

echo -e "\n${YELLOW}3. Reconstruction des assets${NC}"
echo "--------------------------------"
npm run build
echo -e "${GREEN}✓${NC} Assets compilés"

echo -e "\n${YELLOW}4. Régénération du cache${NC}"
echo "--------------------------------"
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo -e "${GREEN}✓${NC} Cache régénéré"

echo -e "\n${YELLOW}5. Test de connexion${NC}"
echo "--------------------------------"
echo "Testez maintenant la connexion avec:"
echo -e "${GREEN}Email:${NC} admin@studiosdb.ca"
echo -e "${GREEN}Mot de passe:${NC} AdminStudios2025!"

echo -e "\n${YELLOW}6. Démarrage des serveurs${NC}"
echo "--------------------------------"
echo "Démarrage du serveur Laravel..."
php artisan serve --host=0.0.0.0 --port=8000 &
SERVER_PID=$!
echo -e "${GREEN}✓${NC} Serveur Laravel démarré (PID: $SERVER_PID)"

echo ""
echo "================================================="
echo -e "${GREEN}✅ FIX APPLIQUÉ AVEC SUCCÈS${NC}"
echo "================================================="
echo ""
echo "📌 ÉTAPES SUIVANTES:"
echo "   1. Ouvrir http://localhost:8000/login"
echo "   2. Se connecter avec admin@studiosdb.ca"
echo "   3. Naviguer vers le Dashboard"
echo ""
echo "📊 PAGES DISPONIBLES:"
echo "   • /login - Page de connexion"
echo "   • /dashboard - Tableau de bord"
echo "   • /membres - Gestion des membres"
echo "   • /cours - Gestion des cours"
echo "   • /presences/tablette - Prise de présences"
echo ""
echo "🔧 EN CAS DE PROBLÈME:"
echo "   • Vérifier les cookies dans le navigateur"
echo "   • Essayer en navigation privée"
echo "   • Consulter: tail -f storage/logs/laravel.log"
echo ""
echo "================================================="
