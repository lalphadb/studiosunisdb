#!/bin/bash
echo "🔧 === RÉPARATION AUTOMATIQUE StudiosUnisDB v3.9.1 ==="

cd /home/studiosdb/studiosunisdb/

# 1. Nettoyer complètement
echo "🧹 Nettoyage complet..."
php artisan down
rm -rf vendor/
rm -rf node_modules/
rm -rf bootstrap/cache/*
rm -rf storage/framework/cache/*
rm -rf storage/framework/sessions/*
rm -rf storage/framework/views/*

# 2. Corriger les permissions
echo "🔧 Correction des permissions..."
chmod -R 755 .
chmod -R 775 storage bootstrap/cache
chown -R $USER:$USER .

# 3. Réinstaller Composer
echo "📦 Réinstallation des dépendances Composer..."
composer clear-cache
composer install --no-dev --optimize-autoloader

# 4. Régénérer la clé d'application
echo "🔑 Génération clé application..."
php artisan key:generate --force

# 5. Créer EventServiceProvider s'il manque
if [ ! -f "app/Providers/EventServiceProvider.php" ]; then
    echo "📁 Création EventServiceProvider..."
    cat > app/Providers/EventServiceProvider.php << 'EOF'
<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    public function boot(): void
    {
        //
    }

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
EOF
fi

# 6. Nettoyer tous les caches
echo "🧹 Nettoyage des caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan event:clear

# 7. Optimiser l'application
echo "⚡ Optimisation..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
composer dump-autoload --optimize

# 8. Base de données
echo "🗄️ Configuration base de données..."
php artisan migrate:fresh --force
php artisan db:seed --force

# 9. Créer utilisateur admin
echo "👤 Création utilisateur admin..."
php artisan tinker --execute="
\$user = App\Models\User::firstOrCreate(
    ['email' => 'r00t3d@pm.me'],
    [
        'name' => 'Super Admin StudiosDB',
        'password' => bcrypt('QwerTfc443'),
        'email_verified_at' => now(),
    ]
);

\$role = Spatie\Permission\Models\Role::firstOrCreate(['name' => 'superadmin']);
\$user->assignRole('superadmin');

echo 'Admin créé: ' . \$user->email;
"

# 10. Permissions finales
echo "🔒 Configuration des permissions..."
php artisan permission:cache-reset

# 11. Remettre en ligne
php artisan up

echo "✅ Réparation terminée !"
echo "🌐 URL: http://127.0.0.1:8001"
echo "👤 Admin: r00t3d@pm.me"
echo "🔑 Pass: QwerTfc443"
