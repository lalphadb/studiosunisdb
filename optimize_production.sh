#!/bin/bash
echo "🚀 OPTIMISATION PRODUCTION StudiosUnisDB v4.1.8.3"

# 1. Vérifier l'environnement
echo "1️⃣ Vérification environnement..."
php -v | head -1
composer --version | head -1

# 2. Nettoyer TOUS les caches
echo "2️⃣ Nettoyage complet des caches..."
php artisan optimize:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan permission:cache-reset

# 3. Optimiser pour la production
echo "3️⃣ Optimisation production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 4. Optimiser Composer (sans dev dependencies)
echo "4️⃣ Optimisation Composer..."
composer install --optimize-autoloader --no-dev --no-interaction

# 5. Optimiser assets front-end
echo "5️⃣ Compilation assets..."
npm ci --production
npm run build

# 6. Optimiser base de données
echo "6️⃣ Optimisation base de données..."
php artisan migrate --force
php artisan db:seed --class=CeintureSeeder --force

# 7. Permissions fichiers sécurisées
echo "7️⃣ Sécurisation permissions fichiers..."
chmod -R 755 storage bootstrap/cache
chmod -R 644 .env.production

# 8. Test final
echo "8️⃣ Tests finaux..."
php artisan about

echo "✅ OPTIMISATION TERMINÉE - Prêt pour Production !"
echo "📋 Prochaines étapes :"
echo "   - Copier .env.production vers .env"
echo "   - Configurer serveur web (Nginx/Apache)"
echo "   - Configurer SSL/HTTPS"
echo "   - Configurer backup automatique"
