#!/bin/bash
cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro

echo "🔥 DEBUG FINAL Laravel 12.21.0"

# Clear tous les caches
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Test direct avec artisan serve
echo "Test avec artisan serve..."
timeout 15s php artisan serve --host=127.0.0.1 --port=8003 &
SERVE_PID=$!
sleep 3

# Test route test
echo "Test route /test-membre:"
curl -s http://127.0.0.1:8003/test-membre

echo -e "\nTest route /membres:"
curl -s -I http://127.0.0.1:8003/membres | head -3

# Kill artisan serve
kill $SERVE_PID 2>/dev/null

echo -e "\nErreurs récentes dans logs:"
tail -30 storage/logs/laravel.log | grep -A 10 -B 5 "ERROR\|Exception\|Fatal"

echo -e "\n🔍 DIAGNOSTIC TERMINÉ"
