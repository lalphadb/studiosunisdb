#!/bin/bash

echo "🔍 VÉRIFICATION SYSTÈME STUDIOSDB V5"
echo "====================================="

# Version PHP
echo -e "\n📦 PHP Version:"
php -v | head -n 1

# Version Laravel
echo -e "\n🚀 Laravel Version:"
php artisan --version

# État Base de données
echo -e "\n🗄️ Connexion MySQL:"
php artisan db:show

# Packages Composer
echo -e "\n📚 Packages principaux:"
composer show | grep -E "laravel|spatie|stancl"

# État NPM
echo -e "\n📦 NPM & Node:"
node -v
npm -v

# État des migrations
echo -e "\n🔄 Migrations:"
php artisan migrate:status | head -20

# État des routes
echo -e "\n🛣️ Routes principales:"
php artisan route:list --columns=method,uri,name | grep -E "membres|cours|dashboard"

# Permissions
echo -e "\n🔐 Permissions fichiers:"
ls -la storage/app/public
ls -la bootstrap/cache

# État Redis
echo -e "\n💾 Redis:"
redis-cli ping

# État Nginx
echo -e "\n🌐 Nginx:"
sudo nginx -t

echo -e "\n✅ Vérification terminée!"
