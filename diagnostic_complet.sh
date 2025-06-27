#!/bin/bash

echo "🔍 DIAGNOSTIC STUDIOSUNISDB v4.1.8.3-DEV"
echo "========================================"

# Base de données
echo "📊 BASE DE DONNÉES:"
php artisan tinker --execute="
echo 'Users: ' . App\Models\User::count();
echo 'Écoles: ' . App\Models\Ecole::count(); 
echo 'Cours: ' . App\Models\Cours::count();
echo 'Rôles: ' . Spatie\Permission\Models\Role::count();
"

# Utilisateurs test
echo -e "\n👥 UTILISATEURS TEST:"
php artisan tinker --execute="
App\Models\User::with(['roles', 'ecole'])->whereIn('email', ['louis@4lb.ca', 'lalpha@4lb.ca'])->get()->each(function(\$user) {
    echo \$user->email . ' - ' . \$user->roles->pluck('name')->join(', ') . ' - École: ' . (\$user->ecole ? \$user->ecole->nom : 'Aucune') . \"\n\";
});
"

# Routes
echo -e "\n🛣️ ROUTES COURS:"
php artisan route:list --name=cours

# Permissions
echo -e "\n🔐 PERMISSIONS:"
php artisan permission:show

# Cache
echo -e "\n🧹 NETTOYAGE CACHE:"
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo -e "\n✅ DIAGNOSTIC TERMINÉ"
