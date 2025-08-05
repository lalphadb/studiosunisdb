#!/bin/bash

echo "🔍 DIAGNOSTIC COMPLET STUDIOSDB V5 PRO"
echo "======================================"

cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro

# 1. NETTOYAGE CACHE COMPLET
echo "🧹 Nettoyage cache complet..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear

# 2. VÉRIFICATION SYNTAXE PHP
echo "🐘 Vérification syntaxe PHP..."
php -l app/Http/Controllers/DashboardController.php
if [ $? -ne 0 ]; then
    echo "❌ ERREUR SYNTAXE PHP DANS DASHBOARDCONTROLLER"
    exit 1
fi

# 3. COMPILATION ASSETS
echo "🎨 Test compilation..."
npm run build
if [ $? -ne 0 ]; then
    echo "❌ ERREUR COMPILATION ASSETS"
    echo "Tentative réparation..."
    npm install
    npm run build
fi

# 4. PERMISSIONS
echo "🔐 Permissions..."
sudo chown -R studiosdb:studiosdb .
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 755 storage bootstrap/cache

# 5. TESTS ROUTES
echo "🛣️ Test routes..."
php artisan route:list | grep dashboard

# 6. TEST BASE DE DONNÉES
echo "🗄️ Test base de données..."
php artisan tinker --execute="
echo 'Utilisateurs: ' . App\Models\User::count();
echo 'Membres: ' . App\Models\Membre::count();
echo 'Auth User: ' . (auth()->user()->name ?? 'Non connecté');
"

# 7. REDÉMARRAGE SERVICES
echo "⚙️ Redémarrage services..."
sudo systemctl restart php8.3-fpm
sudo systemctl reload nginx

# 8. TESTS ENDPOINTS
echo "🌐 Tests endpoints..."
echo "Test JSON:"
curl -s http://studiosdb.local/dashboard-json | head -1

echo "Test HTML:"
curl -s http://studiosdb.local/dashboard-html | head -1

echo "Test Dashboard:"
curl -I http://studiosdb.local/dashboard 2>/dev/null | head -1

# 9. VÉRIFICATION LOGS
echo "📋 Dernières erreurs PHP:"
sudo tail -20 /var/log/php8.3-fpm.log 2>/dev/null | grep ERROR || echo "Pas d'erreurs PHP-FPM"

echo "📋 Dernières erreurs Nginx:"
sudo tail -10 /var/log/nginx/error.log 2>/dev/null | grep error || echo "Pas d'erreurs Nginx"

echo ""
echo "🎯 DIAGNOSTIC TERMINÉ !"
echo "Test maintenant:"
echo "• http://studiosdb.local/dashboard-simple (Vue.js test)"
echo "• http://studiosdb.local/dashboard-json (Laravel test)" 
echo "• http://studiosdb.local/dashboard (Dashboard principal)"