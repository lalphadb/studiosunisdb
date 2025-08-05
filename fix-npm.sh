#!/bin/bash

echo "CORRECTION NPM ET ASSETS"
echo "========================"

cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro

# 1. Vérifier Node/NPM
echo "1. Versions..."
echo "Node: $(node --version 2>/dev/null || echo 'NON INSTALLE')"
echo "NPM: $(npm --version 2>/dev/null || echo 'NON INSTALLE')"

# 2. Nettoyer node_modules si nécessaire
echo "2. Nettoyage node_modules..."
if [ -d "node_modules" ]; then
    sudo rm -rf node_modules
fi
if [ -f "package-lock.json" ]; then
    sudo rm -f package-lock.json
fi

# 3. Créer les dossiers build
echo "3. Création dossiers..."
sudo mkdir -p public/build/assets
sudo chown -R studiosdb:studiosdb public/build

# 4. Installation NPM
echo "4. Installation NPM..."
npm install --no-audit --no-fund

# 5. Build en mode production
echo "5. Build production..."
npm run build

# 6. Vérification build
echo "6. Vérification..."
if [ -f "public/build/manifest.json" ]; then
    echo "✅ Build réussi"
    ls -la public/build/
else
    echo "❌ Build échoué"
    echo "Tentative build développement..."
    npm run dev &
    sleep 5
    kill %1 2>/dev/null
fi

# 7. Permissions finales
echo "7. Permissions build..."
sudo chown -R www-data:www-data public/build
sudo chmod -R 755 public/build

echo "NPM ET ASSETS CORRIGES"