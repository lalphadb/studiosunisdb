<?php

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

echo "🔧 CORRECTION PERMISSIONS LOUIS\n";
echo "===============================\n\n";

$louis = User::where('email', 'louis@4lb.ca')->first();

if (!$louis) {
    echo "❌ Louis non trouvé!\n";
    exit(1);
}

// Vérifier/créer le rôle admin_ecole
$role = Role::firstOrCreate(['name' => 'admin_ecole']);
echo "✅ Rôle admin_ecole: {$role->name}\n";

// Permissions pour les cours
$permissions = [
    'view-cours',
    'create-cours', 
    'edit-cours',
    'delete-cours',
    'viewAny-cours'
];

foreach ($permissions as $permName) {
    $perm = Permission::firstOrCreate(['name' => $permName]);
    if (!$role->hasPermissionTo($perm)) {
        $role->givePermissionTo($perm);
        echo "✅ Permission ajoutée: {$permName}\n";
    }
}

// Assigner le rôle à Louis
if (!$louis->hasRole('admin_ecole')) {
    $louis->assignRole('admin_ecole');
    echo "✅ Rôle admin_ecole assigné à Louis\n";
}

// Vérification finale
echo "\n🔍 VÉRIFICATION:\n";
echo "Rôles Louis: " . $louis->roles->pluck('name')->join(', ') . "\n";
echo "Permissions: " . $louis->getAllPermissions()->count() . "\n";
echo "Peut voir cours: " . ($louis->can('viewAny', \App\Models\Cours::class) ? 'OUI' : 'NON') . "\n";

echo "\n✅ Corrections terminées!\n";
