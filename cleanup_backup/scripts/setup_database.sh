#!/bin/bash
echo "🗃️ Configuration base de données..."

# Fresh migration avec force
php artisan migrate:fresh --force

# Seed les données essentielles
php artisan db:seed --class=CeintureSeeder --force

# Créer admin si n'existe pas
php artisan tinker --execute="
if (!\\App\\Models\\User::where('email', 'louis@4lb.ca')->exists()) {
    \$admin = \\App\\Models\\User::create([
        'name' => 'Louis Admin',
        'email' => 'louis@4lb.ca',
        'password' => bcrypt('password123'),
        'email_verified_at' => now()
    ]);
    echo 'Admin créé: louis@4lb.ca / password123';
}
"

echo "✅ Base de données configurée"
