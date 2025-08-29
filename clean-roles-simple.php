<?php

/**
 * Script simple de nettoyage des rôles - Version corrigée
 */

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

echo "=== NETTOYAGE RÔLES STUDIOSDB - VERSION SIMPLIFIÉE ===\n\n";

// Reset cached roles and permissions
app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

echo "1️⃣ ÉTAT ACTUEL...\n";
$rolesActuels = Role::pluck('name')->toArray();
echo "Rôles actuels: " . implode(', ', $rolesActuels) . "\n";
echo "Total: " . count($rolesActuels) . " rôles\n\n";

echo "2️⃣ MIGRATION DES UTILISATEURS...\n";

// Créer les rôles canoniques s'ils n'existent pas
$canoniques = ['superadmin', 'admin', 'instructeur', 'membre'];
foreach ($canoniques as $roleName) {
    Role::firstOrCreate(['name' => $roleName], ['guard_name' => 'web']);
    echo "✓ Rôle {$roleName} vérifié\n";
}

$adminRole = Role::where('name', 'admin')->first();
$superadminRole = Role::where('name', 'superadmin')->first();

// Migration admin_ecole → admin
$adminEcoleUsers = DB::table('model_has_roles as mhr')
    ->join('roles as r', 'mhr.role_id', '=', 'r.id')
    ->where('r.name', 'admin_ecole')
    ->where('mhr.model_type', 'App\Models\User')
    ->pluck('mhr.model_id');

foreach ($adminEcoleUsers as $userId) {
    // Vérifier si n'a pas déjà admin
    $hasAdmin = DB::table('model_has_roles')
        ->where('model_id', $userId)
        ->where('role_id', $adminRole->id)
        ->where('model_type', 'App\Models\User')
        ->exists();
        
    if (!$hasAdmin) {
        DB::table('model_has_roles')->insert([
            'role_id' => $adminRole->id,
            'model_type' => 'App\Models\User',
            'model_id' => $userId,
        ]);
        echo "✓ User {$userId}: admin_ecole → admin\n";
    }
}

// Migration super-admin → superadmin
$superAdminUsers = DB::table('model_has_roles as mhr')
    ->join('roles as r', 'mhr.role_id', '=', 'r.id')
    ->where('r.name', 'super-admin')
    ->where('mhr.model_type', 'App\Models\User')
    ->pluck('mhr.model_id');

foreach ($superAdminUsers as $userId) {
    $hasSuper = DB::table('model_has_roles')
        ->where('model_id', $userId)
        ->where('role_id', $superadminRole->id)
        ->where('model_type', 'App\Models\User')
        ->exists();
        
    if (!$hasSuper) {
        DB::table('model_has_roles')->insert([
            'role_id' => $superadminRole->id,
            'model_type' => 'App\Models\User',
            'model_id' => $userId,
        ]);
        echo "✓ User {$userId}: super-admin → superadmin\n";
    }
}

// Migration gestionnaire → admin
$gestionnaireUsers = DB::table('model_has_roles as mhr')
    ->join('roles as r', 'mhr.role_id', '=', 'r.id')
    ->where('r.name', 'gestionnaire')
    ->where('mhr.model_type', 'App\Models\User')
    ->pluck('mhr.model_id');

foreach ($gestionnaireUsers as $userId) {
    $hasAdmin = DB::table('model_has_roles')
        ->where('model_id', $userId)
        ->where('role_id', $adminRole->id)
        ->where('model_type', 'App\Models\User')
        ->exists();
        
    if (!$hasAdmin) {
        DB::table('model_has_roles')->insert([
            'role_id' => $adminRole->id,
            'model_type' => 'App\Models\User',
            'model_id' => $userId,
        ]);
        echo "✓ User {$userId}: gestionnaire → admin\n";
    }
}

echo "\n3️⃣ SUPPRESSION ANCIENS RÔLES...\n";

// Supprimer les relations puis les rôles obsolètes
$obsoletes = ['admin_ecole', 'super-admin', 'gestionnaire'];
foreach ($obsoletes as $roleName) {
    $role = Role::where('name', $roleName)->first();
    if ($role) {
        // Supprimer relations users
        DB::table('model_has_roles')->where('role_id', $role->id)->delete();
        // Supprimer relations permissions
        DB::table('role_has_permissions')->where('role_id', $role->id)->delete();
        // Supprimer rôle
        $role->delete();
        echo "🗑️  Rôle {$roleName} supprimé\n";
    }
}

echo "\n4️⃣ CONFIGURATION PERMISSIONS...\n";

// Permissions simplifiées
$permissions = ['admin-panel', 'cours-manage', 'membres-manage', 'users-manage'];
foreach ($permissions as $permName) {
    Permission::firstOrCreate(['name' => $permName], ['guard_name' => 'web']);
}

// Attribution permissions
$superadminRole->syncPermissions(Permission::all());
$adminRole->syncPermissions(['admin-panel', 'cours-manage', 'membres-manage', 'users-manage']);
Role::where('name', 'instructeur')->first()->syncPermissions(['cours-manage', 'membres-manage']);
Role::where('name', 'membre')->first()->syncPermissions([]);

echo "✅ Permissions configurées\n";

echo "\n5️⃣ VÉRIFICATION LOUIS@4LB.CA...\n";

$louis = DB::table('users')->where('email', 'louis@4lb.ca')->first();
if ($louis) {
    $hasSuper = DB::table('model_has_roles')
        ->where('model_id', $louis->id)
        ->where('role_id', $superadminRole->id)
        ->where('model_type', 'App\Models\User')
        ->exists();
        
    if (!$hasSuper) {
        DB::table('model_has_roles')->insert([
            'role_id' => $superadminRole->id,
            'model_type' => 'App\Models\User',
            'model_id' => $louis->id,
        ]);
    }
    echo "✅ louis@4lb.ca confirmé superadmin\n";
}

echo "\n=== RÉSUMÉ FINAL ===\n";
$rolesFinals = Role::pluck('name')->toArray();
sort($rolesFinals);
echo "Rôles finaux: " . implode(', ', $rolesFinals) . "\n";
echo "Total: " . count($rolesFinals) . " rôles\n";

if (count($rolesFinals) == 4 && 
    in_array('superadmin', $rolesFinals) && 
    in_array('admin', $rolesFinals) && 
    in_array('instructeur', $rolesFinals) && 
    in_array('membre', $rolesFinals)) {
    echo "✅ OBJECTIF ATTEINT: 4 rôles canoniques!\n";
} else {
    echo "⚠️  Rôles supplémentaires détectés\n";
    foreach ($rolesFinals as $role) {
        if (!in_array($role, $canoniques)) {
            echo "  - Rôle inattendu: {$role}\n";
        }
    }
}

// Clear cache final
app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
echo "✅ Cache permissions nettoyé\n";

echo "\n🎯 NETTOYAGE TERMINÉ - Prêt pour module J4 Utilisateurs\n";
