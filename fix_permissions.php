<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

echo "=== Correction Permissions Présences ===" . PHP_EOL;

$permissions = [
    'presences.view',
    'presences.create', 
    'presences.edit',
    'presences.delete',
    'presences.marquer'
];

foreach ($permissions as $perm) {
    if (!Permission::where('name', $perm)->exists()) {
        Permission::create(['name' => $perm]);
        echo "✓ Permission créée: $perm" . PHP_EOL;
    } else {
        echo "- Permission existe: $perm" . PHP_EOL;
    }
}

$superadmin = Role::where('name', 'superadmin')->first();
if ($superadmin) {
    $superadmin->givePermissionTo($permissions);
    echo "✓ Permissions assignées au superadmin" . PHP_EOL;
}

$adminEcole = Role::where('name', 'admin_ecole')->first();
if ($adminEcole) {
    $adminEcole->givePermissionTo($permissions);
    echo "✓ Permissions assignées à admin_ecole" . PHP_EOL;
}

echo "=== Terminé ===" . PHP_EOL;
