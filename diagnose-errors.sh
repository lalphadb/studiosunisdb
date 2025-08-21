#!/bin/bash

echo "🔍 Diagnostic des erreurs StudiosDB..."
echo "====================================="

# Vérifier l'environnement
echo -e "\n📋 Environnement:"
grep "APP_ENV" .env
grep "APP_DEBUG" .env

# Vérifier si Vite est en cours d'exécution
echo -e "\n🌐 Serveur Vite:"
if lsof -i:5173 > /dev/null 2>&1; then
    echo "✅ Vite est actif sur le port 5173"
else
    echo "❌ Vite n'est pas actif - Lancer 'npm run dev' pour le développement"
fi

# Vérifier les assets compilés
echo -e "\n📦 Assets compilés:"
if [ -f "public/build/.vite/manifest.json" ]; then
    echo "✅ Assets compilés trouvés"
    ls -lh public/build/assets/*.js | head -5
else
    echo "❌ Assets non compilés - Lancer 'npm run build'"
fi

# Vérifier les routes
echo -e "\n🛤️ Routes Ziggy:"
if [ -f "resources/js/ziggy.js" ]; then
    echo "✅ Fichier Ziggy présent"
    head -5 resources/js/ziggy.js
else
    echo "❌ Ziggy manquant - Lancer 'php artisan ziggy:generate'"
fi

echo -e "\n====================================="
echo "✨ Diagnostic terminé"
echo ""
echo "📝 Actions recommandées:"
if [ ! -f "public/build/.vite/manifest.json" ]; then
    echo "1. npm run build (compiler les assets)"
fi
if ! lsof -i:5173 > /dev/null 2>&1; then
    echo "2. npm run dev (pour le développement avec HMR)"
fi
echo "3. Rafraîchir le navigateur avec Ctrl+Shift+R"
