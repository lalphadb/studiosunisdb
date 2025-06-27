<?php
require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Vérifier les permissions de louis@4lb.ca
$user = DB::table('users')->where('email', 'louis@4lb.ca')->first();

if ($user) {
    echo "=== UTILISATEUR TROUVÉ ===\n";
    echo "ID: {$user->id}\n";
    echo "Nom: {$user->name}\n";
    echo "École ID: {$user->ecole_id}\n";
    
    // Vérifier les rôles
    $roles = DB::table('model_has_roles')
        ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
        ->where('model_id', $user->id)
        ->select('roles.name')
        ->get();
    
    echo "\n=== RÔLES ===\n";
    foreach ($roles as $role) {
        echo "- {$role->name}\n";
    }
    
    // Vérifier les permissions via rôles
    $permissions = DB::table('role_has_permissions')
        ->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
        ->join('model_has_roles', 'model_has_roles.role_id', '=', 'role_has_permissions.role_id')
        ->where('model_has_roles.model_id', $user->id)
        ->select('permissions.name')
        ->distinct()
        ->get();
    
    echo "\n=== PERMISSIONS (Total: " . count($permissions) . ") ===\n";
    foreach ($permissions as $permission) {
        echo "- {$permission->name}\n";
    }
} else {
    echo "Utilisateur louis@4lb.ca non trouvé!\n";
}
