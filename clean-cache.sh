#!/bin/bash
echo "🧹 NETTOYAGE CACHE LARAVEL"

# Clear all caches
php artisan optimize:clear
php artisan config:clear  
php artisan route:clear
php artisan view:clear

# Update session with default values
php artisan tinker --execute="
App\Models\Cours::whereNull('session')->update(['session' => 'automne']);
echo 'Cours mis à jour avec session par défaut';
"

echo "✅ Cache nettoyé et cours mis à jour"
