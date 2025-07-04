<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

echo "=== Configuration des permissions StudiosDB ===" . PHP_EOL;

// 1. Créer les permissions
$permissions = [
    'admin.dashboard',
    'users.view', 'users.create', 'users.edit', 'users.delete',
    'cours.view', 'cours.create', 'cours.edit', 'cours.delete',
    'ecoles.view', 'ecoles.create', 'ecoles.edit', 'ecoles.delete',
    'sessions.view', 'sessions.create', 'sessions.edit', 'sessions.delete',
    'ceintures.view', 'ceintures.create', 'ceintures.edit', 'ceintures.delete',
    'seminaires.view', 'seminaires.create', 'seminaires.edit', 'seminaires.delete',
    'paiements.view', 'paiements.create', 'paiements.edit', 'paiements.delete',
    'presences.view', 'presences.create', 'presences.edit', 'presences.delete',
    'roles.view', 'roles.edit',
    'logs.view',
    'exports.view'
];

echo "Création des permissions..." . PHP_EOL;
foreach ($permissions as $permission) {
    if (!Permission::where('name', $permission)->exists()) {
        Permission::create(['name' => $permission]);
        echo "✓ Permission créée: $permission" . PHP_EOL;
    } else {
        echo "- Permission existe: $permission" . PHP_EOL;
    }
}

// 2. Créer les rôles s'ils n'existent pas
echo PHP_EOL . "Création des rôles..." . PHP_EOL;
$roles_to_create = ['superadmin', 'admin_ecole', 'membre'];
foreach ($roles_to_create as $role_name) {
    if (!Role::where('name', $role_name)->exists()) {
        Role::create(['name' => $role_name]);
        echo "✓ Rôle créé: $role_name" . PHP_EOL;
    } else {
        echo "- Rôle existe: $role_name" . PHP_EOL;
    }
}

// 3. Assigner permissions au superadmin
echo PHP_EOL . "Configuration rôle superadmin..." . PHP_EOL;
$superadminRole = Role::where('name', 'superadmin')->first();
if ($superadminRole) {
    $superadminRole->syncPermissions($permissions);
    echo "✓ Toutes les permissions assignées au superadmin" . PHP_EOL;
}

// 4. Assigner permissions à admin_ecole
echo PHP_EOL . "Configuration rôle admin_ecole..." . PHP_EOL;
$adminEcolePermissions = [
    'admin.dashboard',
    'users.view', 'users.create', 'users.edit', 'users.delete',
    'cours.view', 'cours.create', 'cours.edit', 'cours.delete',
    'sessions.view', 'sessions.create', 'sessions.edit', 'sessions.delete',
    'ceintures.view', 'ceintures.create', 'ceintures.edit', 'ceintures.delete',
    'seminaires.view', 'seminaires.create', 'seminaires.edit', 'seminaires.delete',
    'paiements.view', 'paiements.create', 'paiements.edit', 'paiements.delete',
    'presences.view', 'presences.create', 'presences.edit', 'presences.delete'
];

$adminEcoleRole = Role::where('name', 'admin_ecole')->first();
if ($adminEcoleRole) {
    $adminEcoleRole->syncPermissions($adminEcolePermissions);
    echo "✓ Permissions assignées au rôle admin_ecole" . PHP_EOL;
}

// 5. Vérifier et configurer l'utilisateur 5
echo PHP_EOL . "Configuration utilisateur 5..." . PHP_EOL;
$user = User::find(5);
if ($user) {
    // Assigner le rôle superadmin
    $user->assignRole('superadmin');
    
    echo "✓ User: " . $user->name . PHP_EOL;
    echo "✓ Email: " . $user->email . PHP_EOL;
    echo "✓ Ecole ID: " . $user->ecole_id . PHP_EOL;
    echo "✓ Rôles: " . $user->getRoleNames()->implode(', ') . PHP_EOL;
    echo "✓ Permissions: " . $user->getAllPermissions()->count() . PHP_EOL;
    echo "✓ Peut admin.dashboard: " . ($user->can('admin.dashboard') ? 'OUI' : 'NON') . PHP_EOL;
} else {
    echo "❌ User 5 non trouvé!" . PHP_EOL;
}

echo PHP_EOL . "=== Configuration terminée ===" . PHP_EOL;
