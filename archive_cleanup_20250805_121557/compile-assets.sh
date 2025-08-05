#!/bin/bash

echo "🎨 COMPILATION ASSETS + SERVEUR"
echo "==============================="

cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro

# 1. Vérifier Node/NPM
echo "1. Vérification Node/NPM..."
node --version 2>/dev/null || echo "❌ Node non installé"
npm --version 2>/dev/null || echo "❌ NPM non installé"

# 2. Nettoyer et installer
echo "2. Installation dépendances..."
if [ -d "node_modules" ]; then
    echo "Suppression node_modules existant..."
    rm -rf node_modules
fi

if [ -f "package-lock.json" ]; then
    rm -f package-lock.json
fi

npm install --no-audit --no-fund

# 3. Compilation
echo "3. Compilation assets..."
npm run build

# 4. Vérification build
echo "4. Vérification build..."
if [ -f "public/build/manifest.json" ]; then
    echo "✅ Build réussi"
    ls -la public/build/
    echo "Contenu manifest:"
    head -10 public/build/manifest.json
else
    echo "❌ Build échoué"
    echo "Tentative dev build..."
    npm run dev &
    DEV_PID=$!
    sleep 5
    kill $DEV_PID 2>/dev/null
fi

# 5. Permissions assets
echo "5. Permissions assets..."
sudo chown -R www-data:www-data public/build/
sudo chmod -R 755 public/build/

# 6. Test final avec les 3 serveurs
echo "6. Tests multi-serveurs..."

echo "Test Nginx (studiosdb.local):"
curl -s -I http://studiosdb.local/dashboard 2>/dev/null | head -1

echo "Test localhost (Nginx):"
curl -s -I http://localhost/dashboard 2>/dev/null | head -1

echo "Test serveur dev (port 8000):"
curl -s -I http://localhost:8000/dashboard 2>/dev/null | head -1

echo ""
echo "COMPILATION TERMINÉE"