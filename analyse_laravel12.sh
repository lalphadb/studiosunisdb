#!/bin/bash

cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro

echo "🚀 ANALYSE LARAVEL 12.20 STUDIOSDB"
echo "=================================="

echo "📦 DÉTAILS VERSION"
php artisan --version
php -r "echo 'PHP Version: ' . PHP_VERSION . PHP_EOL;"

echo ""
echo "🔧 NOUVELLES FONCTIONNALITÉS LARAVEL 12"
php artisan list | grep -E "(make:|migrate:|queue:|cache:|route:)"

echo ""
echo "⚙️ CONFIGURATION SYSTÈME"
php artisan config:show app 2>/dev/null || php artisan env

echo ""
echo "🗄️ SCHEMA BASE DE DONNÉES"
php artisan db:show 2>/dev/null || echo "Commande db:show non disponible"

echo ""
echo "📊 ÉTAT CACHE & OPTIMISATION"
php artisan optimize:status 2>/dev/null || echo "Commande optimize:status non disponible"

echo ""
echo "🔍 PACKAGES COMPOSER INSTALLÉS"
composer show | grep -E "(laravel|inertia|spatie|stancl)"

echo ""
echo "🎯 SANITY CHECK PROJET"
php artisan route:list --name=dashboard 2>/dev/null || echo "Route dashboard non trouvée"
php artisan tinker --execute="User::count()" 2>/dev/null || echo "Modèle User non accessible"
