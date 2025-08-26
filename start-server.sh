#!/bin/bash

# StudiosDB Server Startup Script
# Purpose: Lance le serveur Laravel et les assets compilés

echo "=== StudiosDB Server Startup ==="
echo "Stopping any existing servers..."

# Kill existing processes
pkill -f "artisan serve" 2>/dev/null
pkill -f "vite" 2>/dev/null
rm -f public/hot 2>/dev/null

echo "Clearing caches..."
php artisan optimize:clear

echo "Building assets for production..."
npm run build

echo "Starting Laravel server..."
php artisan serve --host=127.0.0.1 --port=8001 &

echo ""
echo "=== Server started successfully ==="
echo "Access the application at: http://127.0.0.1:8001"
echo "Dashboard: http://127.0.0.1:8001/dashboard"
echo ""
echo "Press Ctrl+C to stop the server"

# Keep the script running
wait
