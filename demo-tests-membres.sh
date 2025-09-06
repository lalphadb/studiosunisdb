#!/bin/bash

# =================================================================
# 🎭 DÉMONSTRATION TESTS MEMBRES - MODE SERVEUR
# =================================================================

echo "🎭 Démonstration Tests Module Membres StudiosDB"
echo "================================================"

# Vérifier si le serveur Laravel tourne
echo "🔍 Vérification serveur Laravel..."
if curl -s http://localhost:8000 > /dev/null 2>&1; then
    echo "✅ Serveur Laravel actif"
else
    echo "⚡ Démarrage serveur Laravel..."
    php artisan serve --host=localhost --port=8000 &
    LARAVEL_PID=$!
    echo "🎯 Serveur démarré (PID: $LARAVEL_PID)"
    sleep 3
fi

echo ""
echo "🎭 Lancement des tests de démonstration..."
echo ""

# Lancer les tests en mode headless avec rapport détaillé
npm run test -- --config=playwright-headless.config.js membres-demo --project=chromium --reporter=list

echo ""
echo "✅ Tests terminés !"
echo ""
echo "📁 Résultats disponibles dans:"
echo "   - test-results/ (captures d'écran)"
echo "   - playwright-report/ (rapport HTML)"
echo ""

# Tuer le serveur si on l'a démarré
if [ ! -z "$LARAVEL_PID" ]; then
    echo "🛑 Arrêt du serveur Laravel..."
    kill $LARAVEL_PID 2>/dev/null
fi

echo "🎯 Démonstration terminée !"
