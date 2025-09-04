#!/bin/bash

# StudiosDB Development Server
# Purpose: Lance le serveur en mode développement avec hot reload

echo "=== StudiosDB Development Mode ==="
echo "Stopping any existing servers..."

# Kill existing processes
pkill -f "artisan serve" 2>/dev/null
pkill -f "vite" 2>/dev/null

echo "Clearing caches..."
php artisan optimize:clear

echo "Starting Vite dev server..."
npm run dev -- --host=127.0.0.1 --port=5173 &
VITE_PID=$!

echo "Waiting for Vite to start..."
sleep 3

echo "Starting Laravel server..."
php artisan serve --host=127.0.0.1 --port=8001 &
LARAVEL_PID=$!

echo ""
echo "=== Development servers started ==="
echo "Laravel: http://127.0.0.1:8001"
echo "Vite HMR: http://127.0.0.1:5173"
echo "Dashboard: http://127.0.0.1:8001/dashboard"
echo ""
echo "Press Ctrl+C to stop all servers"

# Trap Ctrl+C to kill both processes
trap "kill $VITE_PID $LARAVEL_PID 2>/dev/null; rm -f public/hot; echo 'Servers stopped'; exit" INT

# Wait for both processes
wait
