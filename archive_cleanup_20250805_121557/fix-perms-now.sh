#!/bin/bash

echo "🔒 CORRECTION PERMISSIONS URGENT"
echo "================================"

cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro

echo "🗑️  Suppression logs problématiques..."
sudo rm -f storage/logs/laravel.log 2>/dev/null || true

echo "📁 Création structure..."
mkdir -p storage/logs storage/framework/{cache/data,sessions,views} bootstrap/cache

echo "🔒 Permissions..."
sudo chown -R $USER:$USER .
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

echo "📄 Nouveau log..."
sudo touch storage/logs/laravel.log
sudo chown www-data:www-data storage/logs/laravel.log
sudo chmod 666 storage/logs/laravel.log

echo "🧹 Cache clear..."
php artisan config:clear
php artisan cache:clear

echo ""
echo "✅ PERMISSIONS CORRIGÉES"
echo ""
echo "🚀 REDÉMARRER SERVEUR:"
echo "php artisan serve --host=0.0.0.0 --port=8000"

echo ""
echo "🌐 TESTER:"
echo "http://localhost:8000/login"