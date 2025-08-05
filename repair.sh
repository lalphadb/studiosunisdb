#!/bin/bash

echo "🚨 RÉPARATION URGENTE STUDIOSDB V5 PRO"
echo "====================================="

cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro

# 1. NETTOYAGE CACHE COMPLET
echo "🧹 Nettoyage cache..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear

# 2. COMPILATION ASSETS
echo "🎨 Compilation assets..."
npm run build
if [ $? -ne 0 ]; then
    echo "❌ Erreur compilation - mode dev..."
    npm run dev &
fi

# 3. PERMISSIONS SYSTÈME
echo "🔐 Permissions..."
sudo chown -R studiosdb:studiosdb .
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 755 storage bootstrap/cache

# 4. REDÉMARRAGE SERVICES
echo "⚙️ Redémarrage services..."
sudo systemctl restart php8.3-fpm
sudo systemctl reload nginx
sudo systemctl restart redis-server

# 5. VÉRIFICATION
echo "✅ Vérification..."
sleep 2
curl -I http://studiosdb.local/dashboard 2>/dev/null | head -1

echo ""
echo "🎯 RÉPARATION TERMINÉE !"
echo "🌐 Testez: http://studiosdb.local/dashboard"
echo "👤 Login: louis@4lb.ca"