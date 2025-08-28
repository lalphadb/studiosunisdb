#!/bin/bash

# Script de diagnostic et réparation StudiosDB - Laravel 12.24
echo "=== DIAGNOSTIC ET RÉPARATION STUDIOSDB ==="
echo "Timestamp: $(date)"
echo ""

cd /home/studiosdb/studiosunisdb

echo "1. Vérification version Laravel..."
php artisan --version

echo ""
echo "2. Nettoyage des caches..."
php artisan optimize:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo ""
echo "3. Vérification de la base de données..."
php artisan tinker --execute="
echo 'Tables principales:';
foreach(['users','cours','membres','ecoles','roles','permissions'] as \$table) {
    echo \$table . ': ' . (\Schema::hasTable(\$table) ? 'OK' : 'MANQUANTE');
}
"

echo ""
echo "4. État des migrations..."
php artisan migrate:status

echo ""
echo "5. Application des migrations manquantes..."
php artisan migrate --force

echo ""
echo "6. Vérification utilisateurs et rôles..."
php artisan tinker --execute="
\$users = \App\Models\User::with('roles')->get();
echo 'Utilisateurs totaux: ' . \$users->count();
foreach(\$users as \$user) {
    echo 'User: ' . \$user->email . ' - Roles: ' . implode(', ', \$user->getRoleNames()->toArray()) . ' - Ecole: ' . \$user->ecole_id;
}
"

echo ""
echo "7. Test permissions Cours..."
php artisan tinker --execute="
\$user = \App\Models\User::first();
if(\$user) {
    echo 'Test user: ' . \$user->email;
    echo 'Can viewAny cours: ' . (\$user->can('viewAny', \App\Models\Cours::class) ? 'OUI' : 'NON');
    echo 'Has roles: ' . (\$user->roles->count() > 0 ? 'OUI' : 'NON');
} else {
    echo 'AUCUN UTILISATEUR TROUVÉ';
}
"

echo ""
echo "8. Création utilisateur admin si nécessaire..."
php artisan tinker --execute="
\$admin = \App\Models\User::where('email', 'admin@studiosdb.com')->first();
if(!\$admin) {
    \$ecole = \App\Models\Ecole::first();
    if(!\$ecole) {
        \$ecole = \App\Models\Ecole::create([
            'nom' => 'École de Karaté StudiosDB',
            'adresse' => '123 Rue Test',
            'ville' => 'Québec',
            'province' => 'QC',
            'code_postal' => 'G1A 1A1',
            'telephone' => '418-123-4567',
            'email' => 'info@studiosdb.com'
        ]);
    }
    
    \$admin = \App\Models\User::create([
        'name' => 'Administrateur',
        'email' => 'admin@studiosdb.com',
        'password' => bcrypt('password'),
        'ecole_id' => \$ecole->id,
        'email_verified_at' => now()
    ]);
    
    \$admin->assignRole('admin_ecole');
    echo 'Utilisateur admin créé: admin@studiosdb.com / password';
} else {
    echo 'Utilisateur admin existe déjà: ' . \$admin->email;
    if(!\$admin->hasAnyRole(['admin_ecole', 'superadmin'])) {
        \$admin->assignRole('admin_ecole');
        echo 'Rôle admin_ecole assigné';
    }
}
"

echo ""
echo "9. Test final du serveur..."
timeout 5s php artisan serve --port=8001 &
sleep 2
curl -s -o /dev/null -w "HTTP Status: %{http_code}" http://127.0.0.1:8001/login
echo ""

echo ""
echo "=== DIAGNOSTIC TERMINÉ ==="
echo "Si tout est OK, démarrer avec: php artisan serve --port=8001"
echo "Connexion: admin@studiosdb.com / password"
