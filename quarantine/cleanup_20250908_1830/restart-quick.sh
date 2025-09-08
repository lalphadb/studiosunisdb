#!/bin/bash
# Kill tout et redémarre immédiatement

echo "🛑 Arrêt de tous les processus..."
pkill -f "php artisan"
pkill -f "vite"
pkill -f "npm"
pkill -f "node"
lsof -ti:8000 | xargs kill -9 2>/dev/null
lsof -ti:8001 | xargs kill -9 2>/dev/null
lsof -ti:5173 | xargs kill -9 2>/dev/null

sleep 2

echo "🧹 Nettoyage..."
php artisan optimize:clear

echo "🚀 Démarrage Laravel..."
php artisan serve --host=127.0.0.1 --port=8001 &

echo "📦 Démarrage Vite..."
npm run dev &

sleep 3

echo ""
echo "✅ PROJET REDÉMARRÉ!"
echo ""
echo "📌 Ouvrir: http://127.0.0.1:8001/login"
echo "📧 Email: louis@4lb.ca"
echo "🔑 Pass: password123"
echo ""
echo "Appuyez sur Ctrl+C pour arrêter"

# Attendre
wait
