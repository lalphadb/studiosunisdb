#!/bin/bash

echo "DIAGNOSTIC STUDIOSDB V5 PRO"
echo "=========================="

cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro

# 1. Nettoyage cache
echo "1. Nettoyage cache..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear

# 2. Verification NPM
echo "2. Verification NPM..."
which npm
npm --version
node --version

# 3. Installation dependances si necessaire
echo "3. Installation NPM..."
npm install

# 4. Compilation assets
echo "4. Compilation assets..."
npm run build

# 5. Permissions
echo "5. Correction permissions..."
sudo chown -R studiosdb:studiosdb .
sudo chown -R www-data:www-data storage bootstrap/cache public/build
sudo chmod -R 755 storage bootstrap/cache public/build

# 6. Tests
echo "6. Tests..."
php artisan route:list | grep dashboard
ls -la public/build/ 2>/dev/null || echo "Pas de dossier build"

# 7. Redemarrage
echo "7. Redemarrage services..."
sudo systemctl restart php8.3-fpm
sudo systemctl reload nginx

echo "DIAGNOSTIC TERMINE"
echo "Test maintenant: http://studiosdb.local/dashboard"