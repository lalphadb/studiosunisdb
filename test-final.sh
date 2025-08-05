#!/bin/bash

echo "TEST FINAL STUDIOSDB V5"
echo "======================="

cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro

# 1. Test écriture log
echo "1. Test log Laravel..."
php artisan tinker --execute="Log::info('Test StudiosDB OK');" 2>/dev/null
if [ $? -eq 0 ]; then
    echo "✅ Log Laravel OK"
else
    echo "❌ Log Laravel KO"
fi

# 2. Test assets
echo "2. Test assets..."
if [ -f "public/build/manifest.json" ]; then
    echo "✅ Assets compilés"
else
    echo "❌ Assets manquants"
fi

# 3. Test routes
echo "3. Test routes..."
php artisan route:list | grep dashboard > /dev/null
if [ $? -eq 0 ]; then
    echo "✅ Routes OK"
else
    echo "❌ Routes KO"
fi

# 4. Test curl
echo "4. Test HTTP..."
HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" http://studiosdb.local/dashboard 2>/dev/null)
echo "Code HTTP: $HTTP_CODE"

if [ "$HTTP_CODE" = "200" ]; then
    echo "✅ DASHBOARD FONCTIONNE !"
elif [ "$HTTP_CODE" = "500" ]; then
    echo "❌ Erreur 500 - Voir logs"
elif [ "$HTTP_CODE" = "000" ]; then
    echo "❌ Connexion impossible"
else
    echo "❌ Erreur $HTTP_CODE"
fi

# 5. Dernières erreurs
echo "5. Dernières erreurs..."
echo "--- Logs Laravel ---"
tail -5 storage/logs/laravel.log 2>/dev/null | grep -i error || echo "Pas d'erreurs Laravel"

echo "--- Logs PHP-FPM ---"
sudo tail -5 /var/log/php8.3-fpm.log 2>/dev/null | grep -i error || echo "Pas d'erreurs PHP-FPM"

echo "--- Logs Nginx ---"
sudo tail -5 /var/log/nginx/error.log 2>/dev/null | grep -i error || echo "Pas d'erreurs Nginx"

echo ""
echo "TEST TERMINE"
echo "Testez maintenant: http://studiosdb.local/dashboard"