#!/bin/bash

# =============================================================================
# TEST COMPLET STUDIOSDB V5 - APRÈS DÉMARRAGE SERVEUR
# =============================================================================

echo "🧪 TESTS AUTOMATIQUES STUDIOSDB V5"
echo "=================================="
echo "Date: $(date)"
echo ""

# Test 1: Serveur Laravel actif
echo "📋 Test 1: Serveur Laravel"
if pgrep -f "php artisan serve" > /dev/null; then
    echo "✅ Serveur Laravel actif"
    ps aux | grep "php artisan serve" | grep -v grep
else
    echo "❌ Serveur Laravel inactif"
fi

echo ""

# Test 2: Port 8000 ouvert
echo "📋 Test 2: Port 8000"
if lsof -Pi :8000 -sTCP:LISTEN -t >/dev/null 2>&1; then
    echo "✅ Port 8000 ouvert"
    lsof -Pi :8000 -sTCP:LISTEN
else
    echo "❌ Port 8000 fermé"
fi

echo ""

# Test 3: URLs principales
echo "📋 Test 3: URLs principales"

URLS=(
    "http://127.0.0.1:8000/dashboard"
    "http://127.0.0.1:8000/login"
    "http://127.0.0.1:8000/debug"
    "http://127.0.0.1:8000/test"
)

for url in "${URLS[@]}"; do
    HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" "$url" 2>/dev/null || echo "000")
    if [ "$HTTP_CODE" = "200" ]; then
        echo "✅ $url - OK ($HTTP_CODE)"
    elif [ "$HTTP_CODE" = "302" ]; then
        echo "🔄 $url - Redirection ($HTTP_CODE)"
    else
        echo "❌ $url - Erreur ($HTTP_CODE)"
    fi
done

echo ""

# Test 4: Vérification contenu dashboard
echo "📋 Test 4: Contenu Dashboard"
DASHBOARD_CONTENT=$(curl -s "http://127.0.0.1:8000/dashboard" 2>/dev/null || echo "ERROR")
if echo "$DASHBOARD_CONTENT" | grep -q "StudiosDB"; then
    echo "✅ Dashboard contient 'StudiosDB'"
elif echo "$DASHBOARD_CONTENT" | grep -q "login"; then
    echo "🔄 Dashboard redirige vers login (normal)"
else
    echo "❌ Dashboard contenu inattendu"
fi

echo ""

# Test 5: Logs serveur
echo "📋 Test 5: Logs serveur"
if [ -f "/tmp/laravel_server.log" ]; then
    echo "✅ Log serveur disponible"
    echo "📄 Dernières lignes:"
    tail -3 /tmp/laravel_server.log 2>/dev/null | sed 's/^/   /'
else
    echo "❌ Log serveur introuvable"
fi

echo ""

# Résumé
echo "📊 RÉSUMÉ DES TESTS"
echo "=================="

SERVEUR_OK=$(pgrep -f "php artisan serve" > /dev/null && echo "OK" || echo "FAIL")
PORT_OK=$(lsof -Pi :8000 -sTCP:LISTEN -t >/dev/null 2>&1 && echo "OK" || echo "FAIL")
DASHBOARD_OK=$(curl -s -o /dev/null -w "%{http_code}" "http://127.0.0.1:8000/dashboard" 2>/dev/null | grep -E "200|302" >/dev/null && echo "OK" || echo "FAIL")

echo "Serveur Laravel: $SERVEUR_OK"
echo "Port 8000: $PORT_OK"  
echo "Dashboard: $DASHBOARD_OK"

if [ "$SERVEUR_OK" = "OK" ] && [ "$PORT_OK" = "OK" ] && [ "$DASHBOARD_OK" = "OK" ]; then
    echo ""
    echo "🎉 TOUS LES TESTS RÉUSSIS !"
    echo "🌐 Accédez à: http://studiosdb.local:8000/dashboard"
    echo "👤 Ou connectez-vous via: http://studiosdb.local:8000/login"
else
    echo ""
    echo "⚠️ CERTAINS TESTS ONT ÉCHOUÉ"
    echo "Vérifiez les logs: tail -f /tmp/laravel_server.log"
fi

echo ""
echo "✅ Tests terminés - $(date)"
