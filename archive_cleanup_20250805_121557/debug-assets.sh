#!/bin/bash

echo "🔍 DIAGNOSTIC ASSETS - Page Blanche"
echo "==================================="

cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro

# 1. Vérifier les assets
echo "1. Vérification assets build..."
ls -la public/build/ 2>/dev/null || echo "❌ Dossier build manquant"

if [ -f "public/build/manifest.json" ]; then
    echo "✅ Manifest exists"
    echo "Contenu manifest:"
    cat public/build/manifest.json
else
    echo "❌ Pas de manifest.json"
fi

# 2. Vérifier les fichiers CSS/JS
echo ""
echo "2. Fichiers assets..."
find public/build -name "*.css" -o -name "*.js" 2>/dev/null | head -10

# 3. Test URL assets via serveur dev
echo ""
echo "3. Test chargement assets..."
if [ -f "public/build/manifest.json" ]; then
    # Extraire un fichier CSS du manifest
    CSS_FILE=$(grep -o '"assets/[^"]*\.css"' public/build/manifest.json | head -1 | tr -d '"')
    if [ ! -z "$CSS_FILE" ]; then
        echo "Test CSS: $CSS_FILE"
        curl -I "http://localhost:8000/build/$CSS_FILE" 2>/dev/null | head -1
    fi
    
    # Extraire un fichier JS du manifest
    JS_FILE=$(grep -o '"assets/[^"]*\.js"' public/build/manifest.json | head -1 | tr -d '"')
    if [ ! -z "$JS_FILE" ]; then
        echo "Test JS: $JS_FILE"
        curl -I "http://localhost:8000/build/$JS_FILE" 2>/dev/null | head -1
    fi
fi

# 4. Vérifier le layout principal
echo ""
echo "4. Layout principal..."
if [ -f "resources/views/layouts/app.blade.php" ]; then
    echo "✅ Layout app.blade.php existe"
    grep -n "@vite" resources/views/layouts/app.blade.php || echo "❌ Pas de @vite dans layout"
else
    echo "❌ Layout app.blade.php manquant"
fi

# 5. Vérifier les vues d'auth
echo ""
echo "5. Vues d'authentification..."
ls -la resources/views/auth/ 2>/dev/null | head -5

# 6. Test route login directe
echo ""
echo "6. Test route login..."
php artisan route:list | grep login

echo ""
echo "DIAGNOSTIC ASSETS TERMINÉ"