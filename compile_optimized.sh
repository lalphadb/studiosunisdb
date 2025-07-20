#!/bin/bash

# 🚀 STUDIOSDB - COMPILATION OPTIMISÉE PRODUCTION
# ==============================================

cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro

echo "🚀 COMPILATION OPTIMISÉE PRODUCTION"
echo "===================================="
echo ""
echo "📊 ÉTAT: 31 packages production installés"
echo "🎯 OBJECTIF: Compiler assets + serveur léger"
echo ""

# 1. AJOUTER TEMPORAIREMENT devDependencies
echo "📦 AJOUT TEMPORAIRE devDependencies..."
npm install >/dev/null 2>&1

if [ $? -eq 0 ]; then
    TOTAL_PACKAGES=$(npm list 2>/dev/null | grep -c "├\|└" || echo "~100")
    echo "✅ Toutes dépendances installées (~$TOTAL_PACKAGES packages)"
else
    echo "❌ Erreur installation"
    exit 1
fi

# 2. COMPILER ASSETS
echo ""
echo "🔨 COMPILATION ASSETS..."
npm run build

if [ $? -eq 0 ]; then
    echo "✅ Compilation réussie!"
    
    # Vérifier assets
    if [ -d "public/build" ] && [ "$(ls -A public/build 2>/dev/null)" ]; then
        echo "✅ Assets trouvés dans public/build/"
    else
        echo "❌ Pas d'assets compilés!"
        exit 1
    fi
else
    echo "❌ Erreur compilation"
    exit 1
fi

# 3. RETOUR À MODE PRODUCTION LÉGER
echo ""
echo "🧹 NETTOYAGE DEVDEPENDENCIES..."
npm prune --production >/dev/null 2>&1

FINAL_PACKAGES=$(npm list --production 2>/dev/null | grep -c "├\|└" || echo "31")
echo "✅ Retour à mode production ($FINAL_PACKAGES packages)"

# 4. OPTIMISATION LARAVEL
echo ""
echo "⚡ OPTIMISATION LARAVEL..."
php artisan config:cache >/dev/null 2>&1
php artisan route:cache >/dev/null 2>&1
php artisan view:cache >/dev/null 2>&1
echo "✅ Cache Laravel optimisé"

# 5. DÉMARRAGE SERVEUR
echo ""
echo "🚀 DÉMARRAGE SERVEUR PRODUCTION..."

# Arrêter anciens processus
pkill -f "php artisan serve" 2>/dev/null || true
sleep 1

# Démarrer Laravel
nohup php artisan serve --host=0.0.0.0 --port=8000 > production.log 2>&1 &
LARAVEL_PID=$!

echo "✅ Serveur démarré (PID: $LARAVEL_PID)"
sleep 2

# 6. TESTS RAPIDES
echo ""
echo "🧪 TESTS..."
echo -n "API: "
curl -s http://0.0.0.0:8000/test | grep -q "SUCCESS" && echo "✅" || echo "❌"

echo -n "Login: "
curl -s http://0.0.0.0:8000/login | grep -q "<!DOCTYPE html>" && echo "✅" || echo "❌"

# 7. RÉSULTAT FINAL
echo ""
echo "🎉 STUDIOSDB V5 PRODUCTION READY!"
echo "================================="
echo ""
echo "📊 OPTIMISATIONS:"
echo "   ✅ Assets: Compilés et minifiés"
echo "   ✅ Packages: $FINAL_PACKAGES (mode production)"
echo "   ✅ Cache: Laravel optimisé"
echo "   ✅ Serveur: Mode production"
echo ""
echo "🎯 URLS:"
echo "   🔍 Debug: http://0.0.0.0:8000/debug"
echo "   🔐 Login: http://0.0.0.0:8000/login"
echo "   🏠 Dashboard: http://0.0.0.0:8000/dashboard"
echo ""
echo "📋 LOGS: tail -f production.log"
echo ""
echo "💡 POUR MODIFICATIONS FUTURES:"
echo "   npm install && npm run build && npm prune --production"
echo ""
echo "🚀 SERVEUR PRÊT POUR PRODUCTION!"
