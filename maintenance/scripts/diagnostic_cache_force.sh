#!/bin/bash

echo "🔧 DIAGNOSTIC FINAL - CACHE FORCÉ"
echo "================================="
echo ""

# Attendre démarrage
sleep 2

# Vérifier les nouveaux assets
echo "📦 ASSETS ACTUELS"
echo "=================="
if [ -f "public/build/assets/app-Cq779qrS.js" ]; then
    echo "✅ JS: app-Cq779qrS.js ($(du -h public/build/assets/app-Cq779qrS.js | cut -f1))"
else
    echo "❌ JS manquant"
fi

if [ -f "public/build/assets/app-DwFTuiu4.css" ]; then
    echo "✅ CSS: app-DwFTuiu4.css ($(du -h public/build/assets/app-DwFTuiu4.css | cut -f1))"
else
    echo "❌ CSS manquant"
fi

echo ""
echo "📋 MANIFEST VITE"
echo "================"
cat public/build/.vite/manifest.json | jq '.' 2>/dev/null || cat public/build/.vite/manifest.json

echo ""
echo "🌐 TESTS ACCÈS"
echo "=============="

# Test login direct
if curl -s http://localhost:8001/login | grep -q "StudiosDB"; then
    echo "✅ Login accessible"
else
    echo "❌ Login inaccessible"
fi

# Test dashboard direct (sans auth)
dashboard_response=$(curl -s -w "%{http_code}" http://localhost:8001/dashboard -o /dev/null)
if [ "$dashboard_response" = "302" ]; then
    echo "✅ Dashboard redirige vers login (normal)"
elif [ "$dashboard_response" = "200" ]; then
    echo "⚠️  Dashboard accessible sans auth"
else
    echo "❌ Dashboard erreur: $dashboard_response"
fi

echo ""
echo "🎯 SOLUTION FORCÉE"
echo "=================="
echo "1. 🗑️  Cache navigateur FORCÉ à vider (meta tags ajoutés)"
echo "2. 🔄 Nouveaux hashs générés: Cq779qrS et DwFTuiu4"
echo "3. 🧹 Tous les caches Laravel vidés"
echo "4. 🔒 Headers anti-cache ajoutés"

echo ""
echo "💡 ACCÈS AU SYSTÈME"
echo "==================="
echo "🧹 Page de nettoyage: http://localhost:8001/cache-cleared.php"
echo "🔐 Login: http://localhost:8001/login"
echo "📧 Email: louis@4lb.ca"
echo "🔑 Mot de passe: password123"

echo ""
echo "🆘 SI PROBLÈME PERSISTE:"
echo "========================"
echo "1. Ouvrez une fenêtre de navigation privée"
echo "2. Ou videz complètement le cache (Ctrl+Shift+Delete)"
echo "3. Utilisez un autre navigateur pour tester"

echo ""
echo "✅ DIAGNOSTIC TERMINÉ"
echo ""
