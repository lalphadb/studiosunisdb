#!/bin/bash

# ===================================================================
# Script de démarrage unifié pour StudiosDB
# ===================================================================

echo "🚀 Démarrage de StudiosDB..."
echo "=============================="
echo ""

# Vérifier l'environnement
if [ ! -f .env ]; then
    echo "❌ Fichier .env manquant!"
    echo "   Copiez .env.example vers .env et configurez vos paramètres"
    exit 1
fi

# Nettoyer le cache
echo "🧹 Nettoyage du cache..."
php artisan optimize:clear
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Installer les dépendances si nécessaire
if [ ! -d "vendor" ]; then
    echo "📦 Installation des dépendances PHP..."
    composer install --no-interaction --prefer-dist --optimize-autoloader
fi

if [ ! -d "node_modules" ]; then
    echo "📦 Installation des dépendances NPM..."
    npm install
fi

# Générer la clé d'application si nécessaire
if ! grep -q "^APP_KEY=base64:" .env; then
    echo "🔑 Génération de la clé d'application..."
    php artisan key:generate
fi

# Migrations
echo "🗄️ Exécution des migrations..."
php artisan migrate --force

# Permissions de stockage
echo "🔒 Configuration des permissions..."
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true

# Construction des assets
echo "🔨 Construction des assets..."
npm run build

# Démarrage des services
echo ""
echo "🌐 Démarrage des services..."
echo "=============================="

# Démarrer le serveur PHP en arrière-plan
php artisan serve --host=0.0.0.0 --port=8000 &
PHP_PID=$!

# Démarrer Vite en mode dev (optionnel)
if [ "$1" == "--dev" ]; then
    npm run dev &
    VITE_PID=$!
    echo "✅ Mode développement activé (Vite)"
fi

echo ""
echo "✅ StudiosDB est démarré!"
echo "=============================="
echo "📍 URL: http://localhost:8000"
echo "📍 Admin: admin@studiosdb.com"
echo ""
echo "💡 Commandes utiles:"
echo "   • Arrêter: Ctrl+C"
echo "   • Mode dev: ./start.sh --dev"
echo "   • Tests: php artisan test"
echo "   • Logs: tail -f storage/logs/laravel.log"
echo ""

# Fonction pour arrêter proprement
cleanup() {
    echo ""
    echo "🛑 Arrêt de StudiosDB..."
    kill $PHP_PID 2>/dev/null
    [ ! -z "$VITE_PID" ] && kill $VITE_PID 2>/dev/null
    echo "✅ StudiosDB arrêté"
    exit 0
}

# Capturer Ctrl+C
trap cleanup INT

# Attendre
wait
