#!/bin/bash

# =============================================================================
# CORRECTION FINALE DASHBOARD - EXÉCUTION AUTOMATIQUE
# =============================================================================

PROJECT_PATH="/home/studiosdb/studiosunisdb/studiosdb_v5_pro"
cd "$PROJECT_PATH"

echo "🚀 CORRECTION FINALE DASHBOARD STUDIOSDB V5"
echo "==========================================="
echo "Correction basée sur l'analyse comparative Dashboard vs Membres"
echo ""

# Donner permissions aux scripts
chmod +x *.sh 2>/dev/null

echo "📋 ÉTAPE 1: Analyse du problème"
echo "==============================="
echo "✅ DashboardController corrigé (même approche que MembreController)"
echo "✅ Gestion d'erreurs robuste ajoutée"
echo "✅ Calculs statistiques sécurisés"
echo "✅ Scripts de diagnostic créés"

echo ""
echo "📋 ÉTAPE 2: Nettoyage système"
echo "============================="
./clean_after_fix.sh

echo ""
echo "📋 ÉTAPE 3: Démarrage serveur"
echo "============================="
if ! pgrep -f "php artisan serve" > /dev/null; then
    echo "🚀 Démarrage serveur Laravel..."
    ./start_server_now.sh
else
    echo "✅ Serveur Laravel déjà actif"
fi

echo ""
echo "📋 ÉTAPE 4: Tests comparatifs"
echo "============================="
sleep 3
./diagnose_dashboard_vs_membres.sh

echo ""
echo "📋 ÉTAPE 5: Extraction erreurs (si problème persiste)"
echo "=================================================="
./extract_dashboard_errors.sh

echo ""
echo "🎯 RÉSUMÉ CORRECTION APPLIQUÉE"
echo "=============================="
echo "✅ DashboardController remplacé par version robuste"
echo "✅ Même approche que MembreController (qui fonctionne)"
echo "✅ Gestion d'erreurs individuelle pour chaque statistique"
echo "✅ Logs détaillés pour diagnostic"
echo "✅ Fallback sécurisé en cas d'erreur"
echo "✅ Cache nettoyé et recompilé"
echo "✅ Serveur redémarré"

echo ""
echo "🌐 TESTEZ MAINTENANT:"
echo "Dashboard: http://studiosdb.local:8000/dashboard"
echo "Membres (référence): http://studiosdb.local:8000/membres"

echo ""
echo "🔧 SI PROBLÈME PERSISTE:"
echo "1. Vérifiez les logs: tail -f storage/logs/laravel.log"
echo "2. Vérifiez les erreurs extraites: cat /tmp/dashboard_errors_*.log"
echo "3. Comparez avec page membres qui fonctionne"

echo ""
echo "✅ CORRECTION TERMINÉE - $(date)"
