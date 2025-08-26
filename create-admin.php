<?php
// Script de création d'utilisateur admin pour StudiosDB
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

echo "\n=== CRÉATION UTILISATEUR ADMIN STUDIOSDB ===\n\n";

// Créer les rôles si nécessaire
$roles = ['superadmin', 'admin_ecole', 'instructeur', 'membre'];
foreach ($roles as $roleName) {
    $role = Role::firstOrCreate(['name' => $roleName]);
    echo "✓ Rôle '{$roleName}' disponible\n";
}

// Créer ou mettre à jour l'utilisateur admin
$adminEmail = 'admin@studiosdb.ca';
$adminPassword = 'AdminStudios2025!';

$user = User::firstOrCreate(
    ['email' => $adminEmail],
    [
        'name' => 'Louis Admin',
        'password' => Hash::make($adminPassword),
        'email_verified_at' => now(),
    ]
);

// Assigner le rôle admin_ecole
$user->syncRoles(['admin_ecole']);

echo "\n✅ UTILISATEUR ADMIN CRÉÉ/MIS À JOUR:\n";
echo "   Email: {$adminEmail}\n";
echo "   Mot de passe: {$adminPassword}\n";
echo "   Rôle: admin_ecole\n";

// Créer quelques utilisateurs de test
$testUsers = [
    [
        'name' => 'Jean Instructeur',
        'email' => 'instructeur@studiosdb.ca',
        'password' => 'Test1234!',
        'role' => 'instructeur'
    ],
    [
        'name' => 'Marie Membre',
        'email' => 'membre@studiosdb.ca', 
        'password' => 'Test1234!',
        'role' => 'membre'
    ]
];

echo "\n📋 UTILISATEURS DE TEST:\n";
foreach ($testUsers as $userData) {
    $testUser = User::firstOrCreate(
        ['email' => $userData['email']],
        [
            'name' => $userData['name'],
            'password' => Hash::make($userData['password']),
            'email_verified_at' => now(),
        ]
    );
    $testUser->syncRoles([$userData['role']]);
    echo "   ✓ {$userData['name']} ({$userData['email']}) - Rôle: {$userData['role']}\n";
}

// Afficher les instructions
echo "\n🚀 INSTRUCTIONS:\n";
echo "   1. Démarrer le serveur: php artisan serve\n";
echo "   2. Ouvrir: http://localhost:8000/login\n";
echo "   3. Se connecter avec:\n";
echo "      - Email: {$adminEmail}\n";
echo "      - Mot de passe: {$adminPassword}\n";
echo "   4. Accéder au dashboard: http://localhost:8000/dashboard\n";

echo "\n💡 COMMANDES UTILES:\n";
echo "   - Vider le cache: php artisan optimize:clear\n";
echo "   - Régénérer les routes: php artisan route:cache\n";
echo "   - Voir les logs: tail -f storage/logs/laravel.log\n";

echo "\n=== FIN ===\n";
