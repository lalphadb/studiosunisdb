#!/bin/bash

echo "🎯 Lancement StudiosDB v5 Pro (local dev)"

# Lancer Vite
echo "🚀 Démarrage de Vite..."
npm run dev &

# Lancer Laravel
sleep 3
echo "🧩 Démarrage de Laravel..."
php artisan serve

