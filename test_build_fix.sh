#!/bin/bash

echo "🔧 STUDIOSDB V5 PRO - TEST DE BUILD CORRIGÉ"
echo "=========================================="

cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro

# Nettoyage caches
echo "🧹 Nettoyage caches..."
rm -rf public/build
rm -rf node_modules/.vite
rm -rf node_modules/.vite-temp

# Test build
echo "🔨 Test build Vite..."
npm run build

# Vérification résultat
if [ -d "public/build" ] && [ -f "public/build/manifest.json" ]; then
    echo "✅ BUILD RÉUSSI!"
    echo "📁 Contenu généré:"
    ls -la public/build/
    if [ -d "public/build/assets" ]; then
        echo "📁 Assets générés:"
        ls -la public/build/assets/ | head -5
    fi
else
    echo "❌ Échec du build"
    echo "🔍 Diagnostic:"
    [ -d "public/build" ] && echo "- Dossier build créé" || echo "- Pas de dossier build"
    [ -f "public/build/manifest.json" ] && echo "- Manifest présent" || echo "- Pas de manifest"
fi

echo ""
echo "🧪 Test configuration Laravel..."
php artisan config:check 2>/dev/null && echo "✅ Configuration Laravel OK" || echo "⚠️ Problèmes config Laravel"

echo ""
echo "🎯 INSTRUCTIONS FINALES:"
echo "Si le build est réussi, lance:"
echo "php artisan serve --host=0.0.0.0 --port=8000"
