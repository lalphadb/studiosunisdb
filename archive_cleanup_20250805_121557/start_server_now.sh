#!/bin/bash

# =============================================================================
# DÉMARRAGE AUTOMATIQUE SERVEUR STUDIOSDB V5 - SCRIPT INTELLIGENT
# =============================================================================

PROJECT_PATH="/home/studiosdb/studiosunisdb/studiosdb_v5_pro"
cd "$PROJECT_PATH"

echo "🚀 DÉMARRAGE SERVEUR STUDIOSDB V5"
echo "=================================="
echo "Diagnostic automatique terminé - Démarrage serveur..."
echo ""

# Nettoyage processus existants
echo "🧹 Nettoyage processus existants..."
pkill -f "php artisan serve" 2>/dev/null && echo "✅ Processus Laravel arrêtés" || echo "❌ Aucun processus Laravel"
pkill -f "vite" 2>/dev/null && echo "✅ Processus Vite arrêtés" || echo "❌ Aucun processus Vite"

# Attente libération ports
sleep 2

# Vérification port 8000
echo "🔍 Vérification port 8000..."
if lsof -Pi :8000 -sTCP:LISTEN -t >/dev/null 2>&1; then
    echo "⚠️ Port 8000 occupé, libération forcée..."
    lsof -ti:8000 | xargs kill -9 2>/dev/null || true
    sleep 2
fi

# Démarrage serveur Laravel
echo "🚀 Démarrage serveur Laravel..."
echo "Commande: php artisan serve --host=0.0.0.0 --port=8000"
echo ""

# Démarrage en arrière-plan avec log
nohup php artisan serve --host=0.0.0.0 --port=8000 > /tmp/laravel_server.log 2>&1 &
LARAVEL_PID=$!

echo "📝 PID serveur Laravel: $LARAVEL_PID"
echo "📋 Log serveur: /tmp/laravel_server.log"

# Attente démarrage
echo "⏳ Attente démarrage serveur (5 secondes)..."
sleep 5

# Vérification démarrage
if kill -0 $LARAVEL_PID 2>/dev/null; then
    echo "✅ SERVEUR LARAVEL DÉMARRÉ AVEC SUCCÈS !"
    echo ""
    echo "🌐 URLs disponibles:"
    echo "   - Dashboard: http://studiosdb.local:8000/dashboard"
    echo "   - Login: http://studiosdb.local:8000/login"  
    echo "   - Debug: http://studiosdb.local:8000/debug"
    echo "   - Membres: http://studiosdb.local:8000/membres"
    echo ""
    
    # Test HTTP immédiat
    echo "🧪 Test HTTP automatique..."
    sleep 2
    
    HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" "http://127.0.0.1:8000/dashboard" 2>/dev/null || echo "000")
    echo "Code HTTP dashboard: $HTTP_CODE"
    
    if [ "$HTTP_CODE" = "200" ]; then
        echo "🎉 SUCCÈS TOTAL ! Dashboard accessible"
    elif [ "$HTTP_CODE" = "302" ]; then
        echo "🔄 Redirection (normal si pas connecté)"
        # Test page debug
        DEBUG_CODE=$(curl -s -o /dev/null -w "%{http_code}" "http://127.0.0.1:8000/debug" 2>/dev/null || echo "000")
        echo "Code HTTP debug: $DEBUG_CODE"
        if [ "$DEBUG_CODE" = "200" ]; then
            echo "✅ Application Laravel fonctionnelle"
        fi
    else
        echo "⚠️ Code HTTP: $HTTP_CODE - Vérifiez les logs"
    fi
    
    echo ""
    echo "📋 COMMANDES UTILES:"
    echo "# Voir logs serveur en temps réel:"
    echo "tail -f /tmp/laravel_server.log"
    echo ""
    echo "# Arrêter le serveur:"
    echo "kill $LARAVEL_PID"
    echo ""
    echo "# Redémarrer serveur:"
    echo "./start_server_now.sh"
    
else
    echo "❌ ÉCHEC DÉMARRAGE SERVEUR"
    echo "📋 Dernières lignes du log:"
    tail -10 /tmp/laravel_server.log 2>/dev/null || echo "Pas de log disponible"
    exit 1
fi

echo ""
echo "✅ SERVEUR PRÊT ! Testez maintenant dans votre navigateur !"
echo "🎯 URL PRINCIPALE: http://studiosdb.local:8000/dashboard"
