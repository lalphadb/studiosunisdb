# Voir toutes les routes
php artisan route:list

# Tester une route spécifique
php artisan route:list --path=admin/users

# Voir les middlewares appliqués
php artisan route:list --name=admin.users.index

# Vérifier les permissions d'un utilisateur
php artisan tinker
>>> $user = User::find(1);
>>> $user->getAllPermissions();
>>> $user->hasRole('admin_ecole');

# Voir les requêtes SQL
DB::enableQueryLog();
// Faire des actions
DB::getQueryLog();

# Vider tous les caches
php artisan optimize:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
