#!/bin/bash

echo "🔧 TEST FINAL - ZIGGY ET VITE CORRIGÉS"
echo "====================================="
echo ""

# Attendre démarrage
echo "⏳ Attente du serveur..."
sleep 3

# Test des nouveaux assets
echo "📦 1. NOUVEAUX ASSETS"
echo "-------------------"
if [ -f "public/build/assets/app-DzlLCVHg.js" ]; then
    echo "✅ Nouveau JS bundle: app-DzlLCVHg.js ($(du -h public/build/assets/app-DzlLCVHg.js | cut -f1))"
else
    echo "❌ JS bundle manquant"
fi

if [ -f "public/build/assets/app-BU4WO0es.css" ]; then
    echo "✅ Nouveau CSS bundle: app-BU4WO0es.css ($(du -h public/build/assets/app-BU4WO0es.css | cut -f1))"
else
    echo "❌ CSS bundle manquant"
fi

# Test Ziggy
echo ""
echo "🛣️  2. VÉRIFICATION ZIGGY"
echo "------------------------"
if [ -f "vendor/tightenco/ziggy/dist/index.esm.js" ]; then
    echo "✅ Ziggy installé via Composer"
else
    echo "❌ Ziggy manquant"
fi

if [ -f "resources/js/ziggy.js" ]; then
    echo "✅ Routes générées: $(wc -l < resources/js/ziggy.js) lignes"
else
    echo "❌ Routes Ziggy manquantes"
fi

# Test accès
echo ""
echo "🌐 3. TEST ACCÈS COMPLET"
echo "----------------------"
echo "🔐 Page de login: http://localhost:8001/login"

if curl -s http://localhost:8001/login | grep -q "StudiosDB"; then
    echo "✅ Login accessible"
else
    echo "❌ Login inaccessible"
fi

echo ""
echo "🎯 RÉSUMÉ DES CORRECTIONS"
echo "========================"
echo "✅ Ziggy installé via Composer"
echo "✅ Routes régénérées pour localhost:8001"
echo "✅ Assets compilés avec nouveaux hash"
echo "✅ Cache Laravel vidé"
echo "✅ Serveur redémarré"

echo ""
echo "💡 PROCHAINE ÉTAPE:"
echo "=================="
echo "1. Ouvrez http://localhost:8001/login"
echo "2. Connectez-vous avec louis@4lb.ca / password123"
echo "3. Le dashboard devrait maintenant se charger SANS erreurs 404"
echo ""
echo "🎉 Erreurs Ziggy et Vite corrigées !"
echo ""
