#!/bin/bash

# StudiosDB - Fix Vite Manifest Error
# Purpose: Corriger l'erreur de manifest Vite manquant

echo "
╔══════════════════════════════════════════════════════════════╗
║          StudiosDB - Correction Erreur Vite Manifest        ║
╚══════════════════════════════════════════════════════════════╝
"

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
BLUE='\033[0;34m'
NC='\033[0m'

echo -e "${YELLOW}Correction de l'erreur Vite manifest...${NC}"
echo ""

# 1. Arrêter les serveurs
echo "1. Arrêt des serveurs existants..."
pkill -f "artisan serve" 2>/dev/null
pkill -f "vite" 2>/dev/null
rm -f public/hot 2>/dev/null
echo -e "${GREEN}✓${NC} Serveurs arrêtés"

# 2. Nettoyer les anciens builds
echo ""
echo "2. Nettoyage des anciens builds..."
rm -rf public/build 2>/dev/null
rm -rf node_modules/.vite 2>/dev/null
rm -rf storage/framework/views/* 2>/dev/null
echo -e "${GREEN}✓${NC} Anciens builds supprimés"

# 3. Nettoyer tous les caches Laravel
echo ""
echo "3. Nettoyage des caches Laravel..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
php artisan optimize:clear
echo -e "${GREEN}✓${NC} Caches nettoyés"

# 4. Vérifier les fichiers Auth
echo ""
echo "4. Vérification des fichiers Auth..."
if [ -f "resources/js/Pages/Auth/Login.vue" ]; then
    echo -e "${GREEN}✓${NC} Login.vue présent"
else
    echo -e "${RED}✗${NC} Login.vue manquant!"
fi

# 5. Installer les dépendances si nécessaire
echo ""
echo "5. Vérification des dépendances npm..."
if [ ! -d "node_modules" ]; then
    echo "Installation des dépendances..."
    npm install
else
    echo -e "${GREEN}✓${NC} Dépendances npm installées"
fi

# 6. Compiler les assets en production
echo ""
echo "6. Compilation des assets (cela peut prendre un moment)..."
echo ""
npm run build

# Vérifier si la compilation a réussi
if [ -f "public/build/.vite/manifest.json" ]; then
    echo ""
    echo -e "${GREEN}✓${NC} Assets compilés avec succès!"
    
    # Vérifier que Login.vue est dans le manifest
    if grep -q "Auth/Login.vue" public/build/.vite/manifest.json; then
        echo -e "${GREEN}✓${NC} Login.vue présent dans le manifest"
    else
        echo -e "${YELLOW}⚠${NC} Login.vue absent du manifest - vérification..."
    fi
else
    echo -e "${RED}✗${NC} Erreur de compilation!"
    echo "Vérifiez les erreurs ci-dessus"
    exit 1
fi

# 7. Créer les caches de production
echo ""
echo "7. Optimisation pour la production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo -e "${GREEN}✓${NC} Caches de production créés"

# 8. Démarrer le serveur
echo ""
echo "8. Démarrage du serveur..."
php artisan serve --host=127.0.0.1 --port=8001 &
SERVER_PID=$!

sleep 3

# 9. Test de l'application
echo ""
echo "9. Test de l'application..."

# Test page d'accueil
http_code_home=$(curl -s -o /dev/null -w "%{http_code}" http://127.0.0.1:8001/)
if [ "$http_code_home" = "200" ] || [ "$http_code_home" = "302" ]; then
    echo -e "${GREEN}✓${NC} Page d'accueil accessible (HTTP $http_code_home)"
else
    echo -e "${RED}✗${NC} Page d'accueil inaccessible (HTTP $http_code_home)"
fi

# Test page de login
http_code_login=$(curl -s -o /dev/null -w "%{http_code}" http://127.0.0.1:8001/login)
if [ "$http_code_login" = "200" ]; then
    echo -e "${GREEN}✓${NC} Page de login accessible"
elif [ "$http_code_login" = "302" ]; then
    echo -e "${YELLOW}⚠${NC} Page de login redirige (déjà connecté?)"
else
    echo -e "${RED}✗${NC} Page de login inaccessible (HTTP $http_code_login)"
fi

echo ""
echo "════════════════════════════════════════════════════════════════"
echo -e "${GREEN}✅ Correction terminée!${NC}"
echo "════════════════════════════════════════════════════════════════"
echo ""
echo -e "${BLUE}L'application est accessible à:${NC}"
echo -e "${YELLOW}http://127.0.0.1:8001${NC}"
echo ""
echo "Pages disponibles:"
echo "  • Accueil: http://127.0.0.1:8001"
echo "  • Login: http://127.0.0.1:8001/login"
echo "  • Dashboard: http://127.0.0.1:8001/dashboard"
echo ""
echo "Pour arrêter le serveur: Ctrl+C ou kill $SERVER_PID"
echo ""

# Afficher les infos du manifest
echo -e "${BLUE}Informations du build:${NC}"
if [ -f "public/build/.vite/manifest.json" ]; then
    echo -n "Nombre de fichiers compilés: "
    grep -c '"file"' public/build/.vite/manifest.json || echo "0"
    echo -n "Taille du manifest: "
    du -h public/build/.vite/manifest.json | cut -f1
fi

echo ""
echo -e "${YELLOW}Si l'erreur persiste:${NC}"
echo "1. Vérifiez les erreurs de compilation npm ci-dessus"
echo "2. Essayez: npm run dev (mode développement)"
echo "3. Vérifiez le fichier .env (APP_ENV et APP_DEBUG)"
echo ""

# Garder le serveur actif
trap "kill $SERVER_PID 2>/dev/null; echo 'Serveur arrêté'; exit" INT
wait $SERVER_PID
