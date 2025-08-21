#!/bin/bash

# Script de démarrage StudiosDB v5
echo "🚀 Démarrage StudiosDB v5 Pro"
echo "================================"

# Vérifier l'environnement
if [ ! -f ".env" ]; then
    echo "❌ Fichier .env manquant"
    exit 1
fi

# Clear caches
echo "🧹 Nettoyage des caches..."
php artisan optimize:clear

# Cache config
echo "⚙️ Configuration..."
php artisan config:cache
php artisan route:cache

# Check database
echo "🗄️ Vérification base de données..."
php artisan migrate:status | grep -q "Pending" && echo "⚠️ Migrations en attente" || echo "✅ Migrations à jour"

# Build assets if needed
if [ ! -f "public/build/manifest.json" ]; then
    echo "📦 Build des assets..."
    npm run build
fi

# Start servers
echo ""
echo "✅ Prêt à démarrer!"
echo ""
echo "Lancer dans des terminaux séparés:"
echo "  Terminal 1: php artisan serve"
echo "  Terminal 2: npm run dev"
echo ""
echo "Accès: http://127.0.0.1:8000"
echo "Compte admin: À créer avec php artisan make:admin"
echo ""
