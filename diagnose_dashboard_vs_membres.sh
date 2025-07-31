#!/bin/bash

# =============================================================================
# TEST COMPARATIF DASHBOARD vs MEMBRES - DIAGNOSTIC SPÉCIFIQUE
# =============================================================================

PROJECT_PATH="/home/studiosdb/studiosunisdb/studiosdb_v5_pro"
cd "$PROJECT_PATH"

echo "🔍 TEST COMPARATIF DASHBOARD vs MEMBRES"
echo "========================================"
echo "Diagnostic spécifique pour identifier pourquoi Membres fonctionne et Dashboard non"
echo ""

# Fonction pour tester une URL
test_url() {
    local url="$1"
    local name="$2"
    
    echo "🧪 Test: $name"
    echo "URL: $url"
    
    # Test HTTP code
    HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" "$url" 2>/dev/null || echo "000")
    echo "HTTP Code: $HTTP_CODE"
    
    # Test contenu
    CONTENT=$(curl -s "$url" 2>/dev/null | head -c 200)
    if echo "$CONTENT" | grep -q "<!DOCTYPE html"; then
        echo "✅ HTML valide détecté"
    elif echo "$CONTENT" | grep -q "StudiosDB\|Dashboard\|Membres"; then
        echo "✅ Contenu application détecté"
    elif [ ${#CONTENT} -eq 0 ]; then
        echo "❌ Contenu vide (page blanche)"
    else
        echo "⚠️ Contenu inattendu: ${CONTENT:0:50}..."
    fi
    
    # Test temps de réponse
    RESPONSE_TIME=$(curl -o /dev/null -s -w "%{time_total}" "$url" 2>/dev/null || echo "0")
    echo "Temps réponse: ${RESPONSE_TIME}s"
    echo ""
}

# Variables d'URL (détection automatique du port)
if lsof -Pi :8000 -sTCP:LISTEN -t >/dev/null 2>&1; then
    BASE_URL="http://127.0.0.1:8000"
    echo "✅ Serveur détecté sur port 8000"
elif lsof -Pi :80 -sTCP:LISTEN -t >/dev/null 2>&1; then
    BASE_URL="http://studiosdb.local"
    echo "✅ Serveur détecté sur port 80 (Nginx)"
else
    echo "❌ Aucun serveur détecté, tentative sur port 8000 par défaut"
    BASE_URL="http://127.0.0.1:8000"
fi

echo "Base URL: $BASE_URL"
echo ""

# Tests comparatifs
echo "📊 TESTS COMPARATIFS"
echo "===================="

test_url "$BASE_URL/membres" "PAGE MEMBRES (QUI FONCTIONNE)"
test_url "$BASE_URL/dashboard" "PAGE DASHBOARD (PROBLÉMATIQUE)"
test_url "$BASE_URL/login" "PAGE LOGIN (RÉFÉRENCE)"
test_url "$BASE_URL/debug" "PAGE DEBUG (RÉFÉRENCE)"

# Tests de routes spécifiques
echo "🛣️ TESTS ROUTES SPÉCIFIQUES"
echo "=========================="

echo "📋 Vérification routes définies:"
php artisan route:list | grep -E "(dashboard|membres)" | while read route; do
    echo "  $route"
done

echo ""

# Test des contrôleurs individuels
echo "🎯 TESTS CONTRÔLEURS"
echo "==================="

echo "📝 Test syntaxe DashboardController:"
if php -l app/Http/Controllers/DashboardController.php >/dev/null 2>&1; then
    echo "✅ DashboardController - Syntaxe OK"
else
    echo "❌ DashboardController - Erreur syntaxe:"
    php -l app/Http/Controllers/DashboardController.php
fi

echo ""
echo "📝 Test syntaxe MembreController:"
if php -l app/Http/Controllers/MembreController.php >/dev/null 2>&1; then
    echo "✅ MembreController - Syntaxe OK"
else
    echo "❌ MembreController - Erreur syntaxe:"
    php -l app/Http/Controllers/MembreController.php
fi

echo ""

# Test des vues
echo "🎨 TESTS VUES"
echo "============"

DASHBOARD_VUE="resources/js/Pages/Dashboard/Admin.vue"
MEMBRES_VUE="resources/js/Pages/Membres/IndexNew.vue"

echo "📄 Vue Dashboard:"
if [ -f "$DASHBOARD_VUE" ]; then
    echo "✅ $DASHBOARD_VUE existe"
    echo "Taille: $(stat -c%s "$DASHBOARD_VUE") bytes"
else
    echo "❌ $DASHBOARD_VUE manquante"
fi

echo ""
echo "📄 Vue Membres:"
if [ -f "$MEMBRES_VUE" ]; then
    echo "✅ $MEMBRES_VUE existe"
    echo "Taille: $(stat -c%s "$MEMBRES_VUE") bytes"
else
    echo "❌ $MEMBRES_VUE manquante"
fi

echo ""

# Test base de données
echo "🗄️ TESTS BASE DE DONNÉES"
echo "======================="

echo "📊 Test connexion DB et tables:"
php artisan tinker --execute="
try {
    echo 'DB Connection: OK' . PHP_EOL;
    echo 'Membres count: ' . App\Models\Membre::count() . PHP_EOL;
    echo 'Users count: ' . App\Models\User::count() . PHP_EOL;
} catch(Exception \$e) {
    echo 'DB Error: ' . \$e->getMessage() . PHP_EOL;
}
"

echo ""

# Analyse des logs en temps réel
echo "📋 ANALYSE LOGS EN TEMPS RÉEL"
echo "============================"

LOG_FILE="storage/logs/laravel.log"
if [ -f "$LOG_FILE" ]; then
    echo "📄 Dernières erreurs (10 lignes):"
    tail -10 "$LOG_FILE" | grep -i "error\|exception\|fatal" || echo "Aucune erreur récente détectée"
    
    echo ""
    echo "🎯 Erreurs liées au Dashboard:"
    grep -i "dashboard" "$LOG_FILE" | tail -5 || echo "Aucune erreur Dashboard spécifique"
    
    echo ""
    echo "🔍 Erreurs Inertia.js:"
    grep -i "inertia" "$LOG_FILE" | tail -3 || echo "Aucune erreur Inertia détectée"
else
    echo "❌ Fichier log introuvable"
fi

echo ""

# Test en direct avec curl verbose
echo "🔬 TEST DÉTAILLÉ DASHBOARD"
echo "========================="

echo "📡 Test HTTP détaillé Dashboard:"
curl -v -s "$BASE_URL/dashboard" > /tmp/dashboard_response.html 2>/tmp/dashboard_headers.txt &
CURL_PID=$!

sleep 3
if kill -0 $CURL_PID 2>/dev/null; then
    echo "⚠️ Requête Dashboard timeout (>3s)"
    kill $CURL_PID 2>/dev/null
else
    echo "✅ Requête Dashboard terminée"
fi

# Analyse de la réponse
if [ -f "/tmp/dashboard_response.html" ]; then
    RESPONSE_SIZE=$(stat -c%s "/tmp/dashboard_response.html")
    echo "Taille réponse: $RESPONSE_SIZE bytes"
    
    if [ $RESPONSE_SIZE -eq 0 ]; then
        echo "❌ RÉPONSE VIDE - PAGE BLANCHE CONFIRMÉE"
    elif [ $RESPONSE_SIZE -lt 100 ]; then
        echo "⚠️ Réponse très courte, contenu:"
        cat /tmp/dashboard_response.html
    else
        echo "✅ Réponse non vide"
        echo "Début du contenu:"
        head -3 /tmp/dashboard_response.html
    fi
fi

echo ""

# Résumé diagnostic
echo "📊 RÉSUMÉ DIAGNOSTIC"
echo "==================="

MEMBRES_OK=$(curl -s -o /dev/null -w "%{http_code}" "$BASE_URL/membres" | grep -E "200|302" >/dev/null && echo "✅" || echo "❌")
DASHBOARD_OK=$(curl -s -o /dev/null -w "%{http_code}" "$BASE_URL/dashboard" | grep -E "200|302" >/dev/null && echo "✅" || echo "❌")
CONTROLLER_OK=$(php -l app/Http/Controllers/DashboardController.php >/dev/null 2>&1 && echo "✅" || echo "❌")

echo "Page Membres: $MEMBRES_OK"
echo "Page Dashboard: $DASHBOARD_OK"  
echo "Contrôleur Dashboard: $CONTROLLER_OK"
echo "Serveur Laravel: $(pgrep -f 'php artisan serve' >/dev/null && echo '✅' || echo '❌')"

if [ "$MEMBRES_OK" = "✅" ] && [ "$DASHBOARD_OK" = "❌" ]; then
    echo ""
    echo "🎯 CONCLUSION: Problème spécifique au Dashboard identifié !"
    echo "   → Membres fonctionne = Serveur/Framework OK"
    echo "   → Dashboard blanc = Problème dans DashboardController ou vue"
    echo ""
    echo "🔧 ACTIONS RECOMMANDÉES:"
    echo "1. Vérifier les logs: tail -f storage/logs/laravel.log"
    echo "2. Tester le nouveau contrôleur installé"
    echo "3. Redémarrer le serveur Laravel"
elif [ "$MEMBRES_OK" = "✅" ] && [ "$DASHBOARD_OK" = "✅" ]; then
    echo ""
    echo "🎉 PROBLÈME RÉSOLU ! Dashboard fonctionne maintenant"
else
    echo ""
    echo "⚠️ Problème plus général détecté"
fi

echo ""
echo "✅ Diagnostic comparatif terminé - $(date)"
echo "📋 Fichiers de diagnostic:"
echo "   - /tmp/dashboard_response.html"
echo "   - /tmp/dashboard_headers.txt"
