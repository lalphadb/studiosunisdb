#!/bin/bash
echo "📦 Installation dépendances Laravel 11..."

# Install NPM dependencies
npm install

# Install Composer dependencies
composer install --optimize-autoloader

# Clear all caches
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

echo "✅ Dépendances installées"
