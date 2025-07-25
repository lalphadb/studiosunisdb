#!/bin/bash

# 🚀 SCRIPT DE REPRISE RAPIDE - StudiosDB v5 Pro
# Usage: ./reprise_rapide.sh

echo "🥋 STUDIOSDB V5 PRO - REPRISE DE SESSION"
echo "======================================="

# Navigation vers le projet
cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro

echo "📁 Répertoire actuel: $(pwd)"

# Vérification de l'environnement
echo "🔍 Vérification environnement..."
if [ ! -f ".env" ]; then
    echo "❌ Fichier .env manquant"
    cp .env.example .env
    php artisan key:generate
    echo "✅ .env créé et clé générée"
fi

# Installation dependencies si nécessaire
if [ ! -d "vendor" ]; then
    echo "📦 Installation dependencies PHP..."
    composer install
fi

if [ ! -d "node_modules" ]; then
    echo "📦 Installation dependencies Node..."
    npm install
fi

# Nettoyage cache
echo "🧹 Nettoyage cache..."
php artisan optimize:clear
php artisan config:clear
php artisan view:clear

# Build assets
echo "🔨 Compilation assets..."
npm run build

# Démarrage serveur
echo ""
echo "🚀 SERVEUR PRÊT À DÉMARRER"
echo "=========================="
echo "Commandes disponibles:"
echo ""
echo "1. Serveur développement:"
echo "   php artisan serve --host=0.0.0.0 --port=8000"
echo ""
echo "2. Watch assets (nouveau terminal):"
echo "   npm run dev"
echo ""
echo "3. Dashboard URL:"
echo "   http://localhost:8000/dashboard"
echo ""
echo "4. Logs en temps réel:"
echo "   tail -f storage/logs/laravel.log"
echo ""
echo "5. Tests dashboard simple:"
echo "   # Modifier DashboardController.php ligne 27:"
echo "   # return Inertia::render('DashboardSimple', ..."
echo ""

# Affichage des fichiers importants modifiés
echo "📄 FICHIERS RÉCEMMENT MODIFIÉS:"
echo "- resources/js/Layouts/AuthenticatedLayout.vue"
echo "- resources/js/Pages/Dashboard.vue" 
echo "- resources/js/Pages/DashboardSimple.vue"
echo "- app/Http/Controllers/DashboardController.php"
echo ""

echo "✅ ENVIRONNEMENT PRÊT !"
echo "Vous pouvez maintenant reprendre le développement."
