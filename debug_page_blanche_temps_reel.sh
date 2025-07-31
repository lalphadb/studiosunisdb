#!/bin/bash

# =============================================================================
# DIAGNOSTIC TEMPS RÉEL - PAGE BLANCHE DASHBOARD
# =============================================================================

PROJECT_PATH="/home/studiosdb/studiosunisdb/studiosdb_v5_pro"
cd "$PROJECT_PATH"

echo "🚨 DIAGNOSTIC TEMPS RÉEL - PAGE BLANCHE PERSISTANTE"
echo "=================================================="
echo "Analyse immédiate des erreurs Dashboard"
echo ""

# 1. Dernières erreurs Laravel
echo "📋 DERNIÈRES ERREURS LARAVEL (20 lignes):"
echo "========================================="
tail -20 storage/logs/laravel.log | grep -E "(ERROR|CRITICAL|Fatal|Exception)" || echo "Aucune erreur critique récente"

echo ""

# 2. Erreurs spécifiques Dashboard
echo "🎯 ERREURS DASHBOARD SPÉCIFIQUES:"
echo "================================"
grep -i "dashboard" storage/logs/laravel.log | tail -10 || echo "Aucune erreur Dashboard spécifique"

echo ""

# 3. Erreurs Inertia.js
echo "⚡ ERREURS INERTIA.JS:"
echo "====================  "
grep -i "inertia" storage/logs/laravel.log | tail -5 || echo "Aucune erreur Inertia"

echo ""

# 4. Test contrôleur direct
echo "🧪 TEST CONTRÔLEUR DASHBOARD DIRECT:"
echo "==================================="
php artisan tinker --execute="
try {
    \$controller = new App\Http\Controllers\DashboardController();
    echo 'Controller instantiation: OK' . PHP_EOL;
    
    \$request = new Illuminate\Http\Request();
    \$request->setUserResolver(function () {
        return App\Models\User::first();
    });
    
    echo 'Request setup: OK' . PHP_EOL;
    
    // Test de la méthode index
    \$result = \$controller->index(\$request);
    echo 'Controller method execution: OK' . PHP_EOL;
    echo 'Result type: ' . get_class(\$result) . PHP_EOL;
    
} catch (Exception \$e) {
    echo 'CONTROLLER ERROR: ' . \$e->getMessage() . PHP_EOL;
    echo 'File: ' . \$e->getFile() . ':' . \$e->getLine() . PHP_EOL;
}
"

echo ""

# 5. Test route dashboard
echo "🛣️ TEST ROUTE DASHBOARD:"
echo "======================="
php artisan route:list | grep dashboard || echo "Route dashboard non trouvée"

echo ""

# 6. Test vue Dashboard
echo "🎨 TEST VUE DASHBOARD:"
echo "====================  "
if [ -f "resources/js/Pages/Dashboard/Admin.vue" ]; then
    echo "✅ Vue Dashboard/Admin.vue existe"
    echo "Taille: $(stat -c%s resources/js/Pages/Dashboard/Admin.vue) bytes"
    echo "Première ligne:"
    head -1 resources/js/Pages/Dashboard/Admin.vue
else
    echo "❌ Vue Dashboard/Admin.vue manquante"
fi

echo ""

# 7. Test assets compilés
echo "📦 TEST ASSETS:"
echo "=============="
if [ -f "public/build/manifest.json" ]; then
    echo "✅ Manifest.json existe"
    cat public/build/manifest.json
else
    echo "❌ Assets non compilés"
fi

echo ""

# 8. Test serveur Laravel
echo "🚀 TEST SERVEUR LARAVEL:"
echo "======================="
if pgrep -f "php artisan serve" > /dev/null; then
    echo "✅ Serveur Laravel actif"
    ps aux | grep "php artisan serve" | grep -v grep
    
    # Test HTTP direct
    echo ""
    echo "🌐 Test HTTP Dashboard:"
    curl -I http://127.0.0.1:8001/dashboard 2>/dev/null || echo "Erreur connexion HTTP"
else
    echo "❌ Serveur Laravel inactif"
fi

echo ""

# 9. Créer dashboard minimal pour test
echo "🔧 CRÉATION DASHBOARD MINIMAL POUR TEST:"
echo "======================================="

cat > app/Http/Controllers/DashboardTestController.php << 'EOC'
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardTestController extends Controller
{
    public function minimal(Request $request)
    {
        try {
            return Inertia::render('DashboardTest', [
                'message' => 'Dashboard Test Fonctionnel',
                'timestamp' => now()->toISOString(),
                'user' => $request->user() ? $request->user()->only(['id', 'name', 'email']) : null,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ], 500);
        }
    }
    
    public function json(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Dashboard Controller fonctionne en JSON',
            'timestamp' => now()->toISOString(),
            'user' => $request->user() ? $request->user()->only(['id', 'name', 'email']) : null,
        ]);
    }
}
EOC

echo "✅ DashboardTestController créé"

# Créer vue de test minimale
cat > resources/js/Pages/DashboardTest.vue << 'EOVUE'
<template>
    <div style="padding: 20px; background: #f0f0f0; min-height: 100vh;">
        <h1 style="color: green; font-size: 24px;">✅ DASHBOARD TEST RÉUSSI !</h1>
        <p><strong>Message:</strong> {{ message }}</p>
        <p><strong>Timestamp:</strong> {{ timestamp }}</p>
        <p><strong>Utilisateur:</strong> {{ user ? user.name : 'Non connecté' }}</p>
        
        <div style="margin-top: 20px; padding: 15px; background: white; border-radius: 8px;">
            <h2>🎯 Test Inertia.js Réussi</h2>
            <p>Si vous voyez cette page, alors:</p>
            <ul>
                <li>✅ Laravel fonctionne</li>
                <li>✅ Inertia.js fonctionne</li>
                <li>✅ Vue.js fonctionne</li>
                <li>✅ Le problème est dans Dashboard/Admin.vue</li>
            </ul>
        </div>
        
        <div style="margin-top: 20px;">
            <a href="/membres" style="background: blue; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px;">Aller aux Membres</a>
            <a href="/dashboard" style="background: red; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; margin-left: 10px;">Dashboard Original</a>
        </div>
    </div>
</template>

<script setup>
defineProps({
    message: String,
    timestamp: String,
    user: Object,
});
</script>
EOVUE

echo "✅ Vue DashboardTest.vue créée"

# Ajouter routes de test
echo ""
echo "🛣️ AJOUT ROUTES DE TEST:"
echo "======================="

cat >> routes/web.php << 'EOT'

// Routes de test Dashboard - DEBUG
Route::get('/dashboard-test', [App\Http\Controllers\DashboardTestController::class, 'minimal'])->name('dashboard.test');
Route::get('/dashboard-json', [App\Http\Controllers\DashboardTestController::class, 'json'])->name('dashboard.json');
EOT

echo "✅ Routes de test ajoutées"

# Nettoyage cache
echo ""
echo "🧹 NETTOYAGE CACHE:"
echo "=================="
php artisan route:clear
php artisan config:clear
php artisan route:cache

echo ""
echo "🎯 TESTS À EFFECTUER MAINTENANT:"
echo "==============================="
echo "1. Dashboard JSON (doit marcher): http://127.0.0.1:8001/dashboard-json"
echo "2. Dashboard Test (doit marcher): http://127.0.0.1:8001/dashboard-test"
echo "3. Dashboard Original (blanc): http://127.0.0.1:8001/dashboard"
echo ""
echo "✅ Si les 2 premiers marchent et le 3ème est blanc,"
echo "   alors le problème est dans Dashboard/Admin.vue ou DashboardController"
echo ""
echo "📋 Testez ces 3 URLs et dites-moi les résultats !"
