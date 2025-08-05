#!/bin/bash

# =============================================================================
# NETTOYAGE COMPLET APRÈS CORRECTION DASHBOARD
# =============================================================================

PROJECT_PATH="/home/studiosdb/studiosunisdb/studiosdb_v5_pro"
cd "$PROJECT_PATH"

echo "🧹 NETTOYAGE COMPLET STUDIOSDB V5"
echo "================================="
echo "Nettoyage après correction DashboardController"
echo ""

# 1. Nettoyage cache Laravel complet
echo "🔄 Nettoyage cache Laravel..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
php artisan clear-compiled

# 2. Nettoyage cache Composer
echo "🔄 Nettoyage cache Composer..."
composer dump-autoload --optimize

# 3. Permissions correctes
echo "🔒 Correction permissions..."
sudo chown -R $USER:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# 4. Recompilation assets
echo "⚡ Recompilation assets..."
npm run build

# 5. Cache optimisé
echo "💾 Reconstruction cache optimisé..."
php artisan config:cache
php artisan route:cache

# 6. Redémarrage serveur Laravel si actif
if pgrep -f "php artisan serve" > /dev/null; then
    echo "🔄 Redémarrage serveur Laravel..."
    pkill -f "php artisan serve"
    sleep 2
    nohup php artisan serve --host=0.0.0.0 --port=8000 > /tmp/laravel_server.log 2>&1 &
    echo "✅ Serveur redémarré"
    sleep 3
else
    echo "ℹ️ Serveur Laravel non actif"
fi

echo ""
echo "✅ NETTOYAGE TERMINÉ"
echo "🎯 Testez maintenant: http://studiosdb.local:8000/dashboard"
