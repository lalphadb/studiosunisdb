<?php

/**
 * Diagnostic complet des permissions utilisateur
 * Usage: php diagnostic-user-permissions.php
 */

require_once __DIR__.'/vendor/autoload.php';

use App\Models\Cours;
use App\Models\User;

// Initialiser Laravel
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== DIAGNOSTIC PERMISSIONS UTILISATEUR ===\n\n";

try {
    // Vérifier l'utilisateur louis@4lb.ca
    $user = User::where('email', 'louis@4lb.ca')->first();

    if (! $user) {
        echo "❌ ERREUR: Utilisateur louis@4lb.ca introuvable\n";
        echo "Utilisateurs existants:\n";
        $users = User::select('id', 'name', 'email', 'ecole_id')->get();
        foreach ($users as $u) {
            echo "  - ID:{$u->id} | {$u->name} | {$u->email} | ecole_id:{$u->ecole_id}\n";
        }
        exit(1);
    }

    echo "✅ Utilisateur trouvé:\n";
    echo "  - ID: {$user->id}\n";
    echo "  - Nom: {$user->name}\n";
    echo "  - Email: {$user->email}\n";
    echo '  - École ID: '.($user->ecole_id ?? 'NULL')."\n";
    echo "  - Créé: {$user->created_at}\n";
    echo "  - Dernière MAJ: {$user->updated_at}\n\n";

    // Vérifier les rôles Spatie
    echo "=== RÔLES SPATIE ===\n";
    $roles = $user->getRoleNames();
    if ($roles->isEmpty()) {
        echo "❌ AUCUN RÔLE ASSIGNÉ\n";
    } else {
        echo "✅ Rôles assignés:\n";
        foreach ($roles as $role) {
            echo "  - {$role}\n";
        }
    }

    // Vérifier les permissions directes
    echo "\n=== PERMISSIONS DIRECTES ===\n";
    $permissions = $user->getDirectPermissions();
    if ($permissions->isEmpty()) {
        echo "ℹ️ Aucune permission directe (normal, utilise les rôles)\n";
    } else {
        foreach ($permissions as $permission) {
            echo "  - {$permission->name}\n";
        }
    }

    // Vérifier toutes les permissions (via rôles + directes)
    echo "\n=== PERMISSIONS TOTALES ===\n";
    $allPermissions = $user->getAllPermissions();
    if ($allPermissions->isEmpty()) {
        echo "❌ AUCUNE PERMISSION TOTALE\n";
    } else {
        echo "✅ Permissions totales:\n";
        foreach ($allPermissions as $permission) {
            echo "  - {$permission->name}\n";
        }
    }

    // Tests de rôles spécifiques
    echo "\n=== TESTS RÔLES ===\n";
    echo "hasRole('superadmin'): ".($user->hasRole('superadmin') ? '✅ OUI' : '❌ NON')."\n";
    echo "hasAnyRole(['superadmin']): ".($user->hasAnyRole(['superadmin']) ? '✅ OUI' : '❌ NON')."\n";
    echo "hasAnyRole(['superadmin','admin_ecole']): ".($user->hasAnyRole(['superadmin', 'admin_ecole']) ? '✅ OUI' : '❌ NON')."\n";

    // Tests de methods du model User
    echo "\n=== MÉTHODES USER ===\n";
    echo 'isSuperAdmin(): '.($user->isSuperAdmin() ? '✅ OUI' : '❌ NON')."\n";
    echo 'isAdminEcole(): '.($user->isAdminEcole() ? '✅ OUI' : '❌ NON')."\n";
    echo 'isInstructeur(): '.($user->isInstructeur() ? '✅ OUI' : '❌ NON')."\n";
    echo 'isMembre(): '.($user->isMembre() ? '✅ OUI' : '❌ NON')."\n";

    // Test d'un cours spécifique pour voir la policy
    echo "\n=== TEST POLICY COURS ===\n";
    $cours = Cours::find(5); // Le cours qui pose problème dans Telescope

    if (! $cours) {
        echo "❌ Cours ID 5 introuvable\n";
        $premiers_cours = Cours::take(3)->get(['id', 'nom', 'ecole_id']);
        echo "Premiers cours disponibles:\n";
        foreach ($premiers_cours as $c) {
            echo "  - ID:{$c->id} | {$c->nom} | ecole_id:{$c->ecole_id}\n";
        }
    } else {
        echo "✅ Cours trouvé:\n";
        echo "  - ID: {$cours->id}\n";
        echo "  - Nom: {$cours->nom}\n";
        echo '  - École ID: '.($cours->ecole_id ?? 'NULL')."\n\n";

        // Test Policy directement
        $policy = app('App\Policies\CoursPolicy');

        echo "Tests Policy CoursPolicy:\n";
        echo 'viewAny(): '.($policy->viewAny($user) ? '✅ OUI' : '❌ NON')."\n";
        echo 'view(cours): '.($policy->view($user, $cours) ? '✅ OUI' : '❌ NON')."\n";
        echo 'update(cours): '.($policy->update($user, $cours) ? '✅ OUI' : '❌ NON')."\n";
        echo 'delete(cours): '.($policy->delete($user, $cours) ? '✅ OUI' : '❌ NON')."\n";

        // Test via Gate (comme Laravel le fait)
        echo "\nTests via Gate (comme dans Controller):\n";
        echo "Gate::forUser(\$user)->allows('viewAny', Cours::class): ";
        try {
            $result = Gate::forUser($user)->allows('viewAny', \App\Models\Cours::class);
            echo ($result ? '✅ OUI' : '❌ NON')."\n";
        } catch (\Exception $e) {
            echo '❌ ERREUR: '.$e->getMessage()."\n";
        }

        echo "Gate::forUser(\$user)->allows('view', \$cours): ";
        try {
            $result = Gate::forUser($user)->allows('view', $cours);
            echo ($result ? '✅ OUI' : '❌ NON')."\n";
        } catch (\Exception $e) {
            echo '❌ ERREUR: '.$e->getMessage()."\n";
        }

        echo "Gate::forUser(\$user)->allows('update', \$cours): ";
        try {
            $result = Gate::forUser($user)->allows('update', $cours);
            echo ($result ? '✅ OUI' : '❌ NON')."\n";
        } catch (\Exception $e) {
            echo '❌ ERREUR: '.$e->getMessage()."\n";
        }
    }

    // Informations sur les tables de permissions
    echo "\n=== TABLES SPATIE ===\n";
    $rolesCount = DB::table('roles')->count();
    $permissionsCount = DB::table('permissions')->count();
    $modelRolesCount = DB::table('model_has_roles')->where('model_id', $user->id)->count();
    $modelPermissionsCount = DB::table('model_has_permissions')->where('model_id', $user->id)->count();

    echo "Roles totaux: {$rolesCount}\n";
    echo "Permissions totaux: {$permissionsCount}\n";
    echo "Rôles de cet user: {$modelRolesCount}\n";
    echo "Permissions directes de cet user: {$modelPermissionsCount}\n";

    // Lister tous les rôles disponibles
    echo "\n=== RÔLES DISPONIBLES ===\n";
    $allRoles = DB::table('roles')->get(['id', 'name', 'guard_name']);
    foreach ($allRoles as $role) {
        echo "  - ID:{$role->id} | {$role->name} | guard:{$role->guard_name}\n";
    }

    // Détails des assignations de rôles pour cet user
    echo "\n=== ASSIGNATIONS RÔLES USER ===\n";
    $userRoles = DB::table('model_has_roles')
        ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
        ->where('model_has_roles.model_id', $user->id)
        ->where('model_has_roles.model_type', 'App\Models\User')
        ->get(['roles.id', 'roles.name', 'roles.guard_name']);

    if ($userRoles->isEmpty()) {
        echo "❌ Aucune assignation de rôle trouvée\n";
    } else {
        foreach ($userRoles as $role) {
            echo "  - Rôle ID:{$role->id} | {$role->name} | guard:{$role->guard_name}\n";
        }
    }

} catch (\Exception $e) {
    echo '❌ ERREUR FATALE: '.$e->getMessage()."\n";
    echo "Stack trace:\n".$e->getTraceAsString()."\n";
}

echo "\n=== FIN DIAGNOSTIC ===\n";
