#!/bin/bash

# =============================================================================
# DIAGNOSTIC FINAL PAGE BLANCHE - TESTS ISOLÉS
# =============================================================================

PROJECT_PATH="/home/studiosdb/studiosunisdb/studiosdb_v5_pro"
cd "$PROJECT_PATH"

echo "🔬 DIAGNOSTIC FINAL PAGE BLANCHE DASHBOARD"
echo "=========================================="
echo "Tests isolés pour identifier la couche problématique"
echo ""

# Ajouter les routes de test si pas déjà présentes
echo "🛣️ Ajout des routes de test..."

# Vérifier si les routes de test existent déjà
if ! grep -q "dashboard-json" routes/web.php; then
    cat >> routes/web.php << 'EOT'

// ============================================================================= 
// ROUTES DE TEST DIAGNOSTIC PAGE BLANCHE - À SUPPRIMER APRÈS DIAGNOSTIC
// =============================================================================

// Test 1: JSON pur (Laravel seul)
Route::get('/dashboard-json', [App\Http\Controllers\DashboardSimpleController::class, 'json'])
    ->middleware(['auth'])
    ->name('dashboard.json.test');

// Test 2: HTML pur (Laravel + Blade)  
Route::get('/dashboard-html', [App\Http\Controllers\DashboardSimpleController::class, 'html'])
    ->middleware(['auth'])
    ->name('dashboard.html.test');

// Test 3: Inertia + Vue minimal (Laravel + Inertia + Vue)
Route::get('/dashboard-simple', [App\Http\Controllers\DashboardSimpleController::class, 'simple'])
    ->middleware(['auth'])
    ->name('dashboard.simple.test');
EOT
    echo "✅ Routes de test ajoutées"
else
    echo "✅ Routes de test déjà présentes"
fi

# Nettoyage cache
echo "🧹 Nettoyage cache..."
php artisan route:clear
php artisan config:clear
php artisan view:clear
php artisan route:cache

# Compilation assets
echo "⚡ Compilation assets..."
npm run build > /dev/null 2>&1

echo ""
echo "🧪 TESTS À EFFECTUER MAINTENANT"
echo "==============================="
echo ""

# Détection automatique du serveur
if lsof -Pi :8001 -sTCP:LISTEN -t >/dev/null 2>&1; then
    BASE_URL="http://127.0.0.1:8001"
    echo "✅ Serveur détecté sur port 8001"
elif lsof -Pi :8000 -sTCP:LISTEN -t >/dev/null 2>&1; then
    BASE_URL="http://127.0.0.1:8000"
    echo "✅ Serveur détecté sur port 8000"
else
    BASE_URL="http://127.0.0.1:8001"
    echo "⚠️ Aucun serveur détecté, utilisation port 8001 par défaut"
fi

echo "Base URL: $BASE_URL"
echo ""

# Tests automatiques
echo "🔬 TESTS AUTOMATIQUES"
echo "===================="

test_url() {
    local url="$1"
    local name="$2"
    local expected="$3"
    
    echo "🧪 Test: $name"
    echo "URL: $url"
    
    HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" "$url" 2>/dev/null || echo "000")
    echo "HTTP Code: $HTTP_CODE"
    
    if [ "$HTTP_CODE" = "200" ]; then
        echo "✅ SUCCÈS - $name fonctionne"
    elif [ "$HTTP_CODE" = "302" ]; then
        echo "🔄 REDIRECTION - Probablement vers login (normal si pas connecté)"
    elif [ "$HTTP_CODE" = "500" ]; then
        echo "❌ ERREUR SERVEUR 500 - $name a un problème"
    elif [ "$HTTP_CODE" = "000" ]; then
        echo "❌ CONNEXION IMPOSSIBLE - Serveur arrêté ou port incorrect"
    else
        echo "⚠️ CODE INATTENDU: $HTTP_CODE"
    fi
    echo ""
}

# Test 1: JSON pur (Laravel seul)
test_url "$BASE_URL/dashboard-json" "JSON PUR (Laravel seul)" "200"

# Test 2: HTML pur (Laravel + Blade)
test_url "$BASE_URL/dashboard-html" "HTML PUR (Laravel + Blade)" "200"

# Test 3: Inertia + Vue minimal
test_url "$BASE_URL/dashboard-simple" "INERTIA + VUE MINIMAL" "200"

# Test 4: Dashboard original (problématique)
test_url "$BASE_URL/dashboard" "DASHBOARD ORIGINAL (Problématique)" "200"

# Test 5: Page membres (référence qui marche)
test_url "$BASE_URL/membres" "PAGE MEMBRES (Référence qui marche)" "200"

echo "📊 INTERPRÉTATION DES RÉSULTATS"
echo "==============================="
echo ""
echo "🎯 Si JSON PUR marche (200) :"
echo "   → Laravel + PHP + Base de données = OK"
echo ""
echo "🎯 Si HTML PUR marche (200) :"
echo "   → Laravel + Blade + Authentification = OK"
echo ""  
echo "🎯 Si INERTIA + VUE MINIMAL marche (200) :"
echo "   → Laravel + Inertia + Vue + Assets = OK"
echo "   → Le problème est dans Dashboard/Admin.vue ou DashboardController"
echo ""
echo "🎯 Si DASHBOARD ORIGINAL ne marche pas (blanc/500) :"
echo "   → Problème spécifique dans le code du dashboard"
echo ""
echo "🎯 Si PAGE MEMBRES marche (200) :"
echo "   → Confirmation que le système global fonctionne"

echo ""
echo "🌐 LIENS DE TEST DIRECTS"
echo "======================="
echo "1. JSON Test: $BASE_URL/dashboard-json"
echo "2. HTML Test: $BASE_URL/dashboard-html" 
echo "3. Vue Test: $BASE_URL/dashboard-simple"
echo "4. Dashboard Original: $BASE_URL/dashboard"
echo "5. Membres (référence): $BASE_URL/membres"

echo ""
echo "📋 PROCHAINES ÉTAPES"
echo "==================="
echo "1. Testez ces 5 URLs dans votre navigateur"
echo "2. Notez lesquelles fonctionnent et lesquelles sont blanches"
echo "3. Regardez la console du navigateur (F12) pour les erreurs JavaScript"
echo "4. Communiquez-moi les résultats pour diagnostic final"

echo ""
echo "🔧 POUR VOIR LES LOGS EN TEMPS RÉEL :"
echo "tail -f storage/logs/laravel.log"

echo ""
echo "✅ DIAGNOSTIC PRÉPARÉ - Testez maintenant les URLs ci-dessus !"
