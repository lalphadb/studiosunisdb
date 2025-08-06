#!/bin/bash

echo "🔍 DIAGNOSTIC FINAL - IDENTIFICATION PRÉCISE DU PROBLÈME"
echo "========================================================"

cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro

# 1. NETTOYAGE COMPLET
echo "🧹 Nettoyage complet..."
php artisan optimize:clear
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 2. VÉRIFICATION SYNTAXE
echo "🐘 Vérification syntaxe..."
php -l app/Http/Controllers/DashboardController.php
if [ $? -ne 0 ]; then
    echo "❌ ERREUR PHP - ARRÊT"
    exit 1
fi

# 3. COMPILATION
echo "🎨 Compilation..."
npm run build

# 4. PERMISSIONS
echo "🔐 Permissions..."
sudo chown -R studiosdb:studiosdb .
sudo chown -R www-data:www-data storage bootstrap/cache

# 5. CACHE CONFIG
echo "⚡ Cache config..."
php artisan config:cache
php artisan route:cache

# 6. REDÉMARRAGE
echo "⚙️ Redémarrage..."
sudo systemctl restart php8.3-fpm nginx

# 7. TESTS SYSTÉMATIQUES
echo ""
echo "🧪 TESTS SYSTÉMATIQUES:"
echo "======================="

sleep 2

echo "Test 1 - JSON Laravel:"
curl -s http://studiosdb.local/test-json | jq . 2>/dev/null || curl -s http://studiosdb.local/test-json

echo ""
echo "Test 2 - HTML Laravel:"
curl -s http://studiosdb.local/test-html | head -2

echo ""
echo "Test 3 - Dashboard Status:"
HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" http://studiosdb.local/dashboard)
echo "Code HTTP Dashboard: $HTTP_CODE"

if [ "$HTTP_CODE" = "200" ]; then
    echo "✅ DASHBOARD FONCTIONNE !"
elif [ "$HTTP_CODE" = "500" ]; then
    echo "❌ ERREUR 500 - Problème serveur"
elif [ "$HTTP_CODE" = "404" ]; then
    echo "❌ ERREUR 404 - Route non trouvée"
else
    echo "❌ ERREUR $HTTP_CODE"
fi

echo ""
echo "🎯 TESTS À EFFECTUER MANUELLEMENT:"
echo "=================================="
echo "1. http://studiosdb.local/test-json"
echo "2. http://studiosdb.local/test-html"
echo "3. http://studiosdb.local/test-inertia"
echo "4. http://studiosdb.local/test-dashboard"
echo "5. http://studiosdb.local/dashboard"
echo ""
echo "📋 CHAQUE TEST RÉVÈLE:"
echo "• test-json: Laravel de base fonctionne"
echo "• test-html: PHP + routes fonctionnent"
echo "• test-inertia: Vue.js + Inertia fonctionnent"
echo "• test-dashboard: Dashboard simplifié fonctionne"
echo "• dashboard: Version finale"

echo ""
echo "🚨 SI UN TEST ÉCHOUE, ON AURA L'ERREUR EXACTE !"