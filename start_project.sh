#!/bin/bash

# 🚀 SCRIPT DÉMARRAGE RAPIDE STUDIOSUNISDB

PROJECT_ROOT="/home/studiosdb/studiosunisdb"
cd "$PROJECT_ROOT"

echo "🚀 DÉMARRAGE STUDIOSUNISDB"
echo "========================="

# Vérifier les dépendances
echo "📦 Vérification dépendances..."
if [ ! -d "vendor" ]; then
    echo "Installing Composer dependencies..."
    composer install
fi

if [ ! -d "node_modules" ]; then
    echo "Installing NPM dependencies..."
    npm install
fi

# Générer clé app si nécessaire
if ! grep -q "APP_KEY=" .env || [ "$(grep APP_KEY= .env | cut -d'=' -f2)" = "" ]; then
    echo "🔑 Génération clé application..."
    php artisan key:generate
fi

# Vérifier DB
echo "🗄️ Vérification base de données..."
if ! mysql -u root -pLkmP0km1 -e "USE studiosdb; SELECT 1;" >/dev/null 2>&1; then
    echo "❌ Problème connexion DB"
    exit 1
fi

# Migrations si nécessaire
pending=$(php artisan migrate:status | grep "Pending" | wc -l)
if [ $pending -gt 0 ]; then
    echo "📊 Exécution migrations en attente..."
    php artisan migrate
fi

# Lancer les serveurs
echo ""
echo "🚀 Démarrage des serveurs..."

# Laravel en arrière-plan
nohup php artisan serve --host=0.0.0.0 --port=8000 > /tmp/laravel.log 2>&1 &
LARAVEL_PID=$!

# Vite en arrière-plan
nohup npm run dev > /tmp/vite.log 2>&1 &
VITE_PID=$!

echo "✅ Laravel démarré (PID: $LARAVEL_PID)"
echo "✅ Vite démarré (PID: $VITE_PID)"
echo ""
echo "🌐 URLs disponibles:"
echo "   Dashboard Admin: http://localhost:8000/admin"
echo "   Login: http://localhost:8000/login"
echo ""
echo "👤 Comptes test:"
echo "   SuperAdmin: louis@4lb.ca / password123"
echo "   Admin École: lalpha@4lb.ca / B0bby2111"
echo ""
echo "🛑 Pour arrêter:"
echo "   kill $LARAVEL_PID $VITE_PID"
echo ""
echo "📊 Logs temps réel:"
echo "   Laravel: tail -f /tmp/laravel.log"
echo "   Vite: tail -f /tmp/vite.log" 
