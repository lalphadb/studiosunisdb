#!/bin/bash

# StudiosDB Quick Test Script
# Purpose: Teste rapidement si l'application fonctionne

echo "=== StudiosDB Quick Test ==="

# 1. Check Laravel is configured
echo -n "Laravel configuration: "
php artisan about | grep "Laravel Framework" | head -1

# 2. Check database connection
echo -n "Database connection: "
php artisan tinker --execute="echo DB::connection()->getPdo() ? 'OK' : 'FAILED';" 2>/dev/null

# 3. Check compiled assets
echo -n "Assets compiled: "
if [ -f "public/build/.vite/manifest.json" ]; then
    echo "OK"
else
    echo "MISSING - Run: npm run build"
fi

# 4. Check authentication routes
echo -n "Auth routes: "
php artisan route:list | grep -c "login\|dashboard" | xargs -I {} sh -c 'if [ {} -gt 0 ]; then echo "OK"; else echo "MISSING"; fi'

# 5. Check required directories
echo -n "Storage writable: "
if [ -w "storage" ] && [ -w "bootstrap/cache" ]; then
    echo "OK"
else
    echo "FAILED - Fix permissions"
fi

# 6. Start server and test
echo ""
echo "Starting temporary test server..."
timeout 5 php artisan serve --host=127.0.0.1 --port=8002 > /dev/null 2>&1 &
SERVER_PID=$!
sleep 2

echo "Testing endpoints..."
echo -n "Home page: "
curl -s -o /dev/null -w "%{http_code}" http://127.0.0.1:8002/ | xargs -I {} sh -c 'if [ {} -eq 200 ] || [ {} -eq 302 ]; then echo "OK (HTTP {})"; else echo "FAILED (HTTP {})"; fi'

echo -n "Dashboard: "
curl -s -o /dev/null -w "%{http_code}" http://127.0.0.1:8002/dashboard | xargs -I {} sh -c 'if [ {} -eq 200 ] || [ {} -eq 302 ]; then echo "OK (HTTP {})"; else echo "FAILED (HTTP {})"; fi'

# Kill test server
kill $SERVER_PID 2>/dev/null

echo ""
echo "=== Test complete ==="
echo "To start the server permanently, run: bash start-server.sh"
