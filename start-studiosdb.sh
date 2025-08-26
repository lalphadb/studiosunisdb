#!/bin/bash

echo "🚀 Démarrage de StudiosDB v5 Pro"
echo "================================="
echo ""

# Couleurs
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# 1. Vérifier et nettoyer les caches
echo "1️⃣ Nettoyage des caches..."
php artisan optimize:clear

# 2. Vérifier la syntaxe des fichiers corrigés
echo -e "\n2️⃣ Vérification de la syntaxe PHP..."
php -l app/Http/Requests/Membres/StoreMembreRequest.php > /dev/null 2>&1
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✅ StoreMembreRequest.php OK${NC}"
else
    echo -e "${RED}❌ Erreur dans StoreMembreRequest.php${NC}"
    exit 1
fi

php -l app/Http/Requests/Membres/UpdateMembreRequest.php > /dev/null 2>&1
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✅ UpdateMembreRequest.php OK${NC}"
else
    echo -e "${RED}❌ Erreur dans UpdateMembreRequest.php${NC}"
    exit 1
fi

# 3. Vérifier si Vite est actif
echo -e "\n3️⃣ Vérification de Vite..."
curl -s http://127.0.0.1:5173/@vite/client > /dev/null 2>&1
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✅ Vite est déjà actif${NC}"
else
    echo -e "${YELLOW}⚠️ Vite n'est pas actif${NC}"
    echo "Démarrage de Vite..."
    npm run dev > vite.log 2>&1 &
    sleep 3
    curl -s http://127.0.0.1:5173/@vite/client > /dev/null 2>&1
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}✅ Vite démarré avec succès${NC}"
    else
        echo -e "${RED}❌ Impossible de démarrer Vite${NC}"
    fi
fi

# 4. Démarrer Laravel
echo -e "\n4️⃣ Démarrage de Laravel..."
echo -e "${YELLOW}Laravel va démarrer. Gardez ce terminal ouvert.${NC}"
echo -e "${GREEN}➡️ Ouvrez http://127.0.0.1:8000/dashboard dans votre navigateur${NC}"
echo ""
echo "================================="
echo "Appuyez Ctrl+C pour arrêter le serveur"
echo "================================="
echo ""

# Démarrer Laravel (restera au premier plan)
php artisan serve
