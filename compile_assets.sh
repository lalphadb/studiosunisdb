#!/bin/bash
echo "⚡ Compilation assets..."

# Build pour production
npm run build

# Cache optimisations
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Fix permissions
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true

echo "✅ Assets compilés et optimisés"
