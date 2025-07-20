#!/bin/bash
cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro
echo "📦 Installation..."
npm install >/dev/null
echo "🔨 Compilation..."
npm run build
echo "🧹 Nettoyage..."
npm prune --production >/dev/null
echo "🚀 Démarrage..."
pkill -f "php artisan serve" 2>/dev/null
php artisan serve --host=0.0.0.0 --port=8000 &
echo "✅ DASHBOARD CORRIGÉ: http://0.0.0.0:8000/dashboard"
