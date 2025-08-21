cat > /home/studiosdb/studiosunisdb/deploy_cours_module.sh << 'EOH'
#!/bin/bash

# Script de déploiement du module Cours - StudiosDB v5
# ======================================================

echo "🚀 Déploiement du Module Cours StudiosDB v5"
echo "==========================================="

# Variables
PROJECT_DIR="/home/studiosdb/studiosunisdb"
cd $PROJECT_DIR

# 1. Vérification de l'environnement
echo ""
echo "1️⃣ Vérification de l'environnement..."
php -v | head -1
composer --version | head -1
npm -v | head -1

# 2. Nettoyage des caches
echo ""
echo "2️⃣ Nettoyage des caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 3. Installation des dépendances
echo ""
echo "3️⃣ Installation des dépendances..."
composer install --no-dev --optimize-autoloader
npm install

# 4. Compilation des assets
echo ""
echo "4️⃣ Compilation des assets Vue.js..."
npm run build

# 5. Migrations de la base de données
echo ""
echo "5️⃣ Exécution des migrations..."
php artisan migrate --force

# 6. Seeders pour données de test
echo ""
echo "6️⃣ Création des données de test..."
php artisan db:seed --class=CoursSeeder

# 7. Optimisation
echo ""
echo "7️⃣ Optimisation de l'application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# 8. Permissions
echo ""
echo "8️⃣ Configuration des permissions..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chown -R www-data:www-data storage
chown -R www-data:www-data bootstrap/cache

# 9. Test de l'application
echo ""
echo "9️⃣ Test de l'application..."
php artisan tinker --execute="
use App\Models\Cours;
use App\Models\User;
echo '✅ Modèle Cours: ' . (class_exists('App\Models\Cours') ? 'OK' : 'ERREUR') . PHP_EOL;
echo '✅ Contrôleur: ' . (class_exists('App\Http\Controllers\CoursController') ? 'OK' : 'ERREUR') . PHP_EOL;
echo '✅ Nombre de cours: ' . Cours::count() . PHP_EOL;
echo '✅ Instructeurs: ' . User::role('instructeur')->count() . PHP_EOL;
"

# 10. Rapport final
echo ""
echo "======================================"
echo "✅ MODULE COURS DÉPLOYÉ AVEC SUCCÈS!"
echo "======================================"
echo ""
echo "📊 Statistiques:"
php artisan tinker --execute="
use App\Models\Cours;
echo '  • Cours créés: ' . Cours::count() . PHP_EOL;
echo '  • Cours actifs: ' . Cours::where('actif', true)->count() . PHP_EOL;
echo '  • Places totales: ' . Cours::sum('places_max') . PHP_EOL;
"

echo ""
echo "🔗 URLs disponibles:"
echo "  • Liste des cours: http://studiosdb.local:8000/cours"
echo "  • Nouveau cours: http://studiosdb.local:8000/cours/create"
echo "  • Planning: http://studiosdb.local:8000/planning"
echo ""
echo "👤 Connexion admin:"
echo "  • Email: louis@4lb.ca"
echo "  • Mot de passe: [votre mot de passe]"
echo ""
echo "📝 Documentation: /home/studiosdb/studiosunisdb/module_cours_complet.md"
echo ""
echo "🎉 Module Cours prêt à l'utilisation!"
EOH