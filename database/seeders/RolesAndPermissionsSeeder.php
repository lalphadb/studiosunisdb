<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Définir les rôles canoniques StudiosDB
        $roles = [
            'superadmin' => 'Super Administrateur - Accès total',
            'admin_ecole' => 'Administrateur École - Gestion complète école',
            'instructeur' => 'Instructeur - Gestion cours et membres',
            'membre' => 'Membre - Accès limité aux informations personnelles'
        ];

        echo "=== CRÉATION DES RÔLES SPATIE ===\n";
        
        foreach ($roles as $roleName => $description) {
            $role = Role::firstOrCreate(['name' => $roleName], ['guard_name' => 'web']);
            
            if ($role->wasRecentlyCreated) {
                echo "✅ Rôle créé: {$roleName}\n";
            } else {
                echo "ℹ️  Rôle existant: {$roleName}\n";
            }
        }

        // Permissions de base (optionnel - peut être étendu)
        $permissions = [
            'admin-panel' => 'Accès panneau administration',
            'cours-manage' => 'Gérer les cours',
            'membres-manage' => 'Gérer les membres',
            'users-manage' => 'Gérer les utilisateurs',
        ];

        echo "\n=== CRÉATION DES PERMISSIONS ===\n";
        
        foreach ($permissions as $permName => $description) {
            $permission = Permission::firstOrCreate(['name' => $permName], ['guard_name' => 'web']);
            
            if ($permission->wasRecentlyCreated) {
                echo "✅ Permission créée: {$permName}\n";
            } else {
                echo "ℹ️  Permission existante: {$permName}\n";
            }
        }

        // Attribution des permissions aux rôles
        echo "\n=== ATTRIBUTION PERMISSIONS ===\n";
        
        $superadmin = Role::findByName('superadmin');
        $adminEcole = Role::findByName('admin_ecole');
        $instructeur = Role::findByName('instructeur');
        
        // Superadmin : toutes les permissions
        $superadmin->givePermissionTo(Permission::all());
        echo "✅ Superadmin: toutes permissions\n";
        
        // Admin école : gestion cours, membres, panneau admin
        $adminEcole->givePermissionTo(['admin-panel', 'cours-manage', 'membres-manage']);
        echo "✅ Admin École: permissions de gestion\n";
        
        // Instructeur : gestion cours et membres (pas users)
        $instructeur->givePermissionTo(['cours-manage', 'membres-manage']);
        echo "✅ Instructeur: permissions cours/membres\n";

        // Vérifier si l'utilisateur louis@4lb.ca existe et lui donner le rôle superadmin
        echo "\n=== CONFIGURATION UTILISATEUR LOUIS ===\n";
        
        $user = DB::table('users')->where('email', 'louis@4lb.ca')->first();
        
        if (!$user) {
            // Créer l'utilisateur superadmin
            $userId = DB::table('users')->insertGetId([
                'name' => 'Louis Superadmin',
                'email' => 'louis@4lb.ca',
                'password' => bcrypt('password123'),
                'ecole_id' => null, // Superadmin n'appartient à aucune école
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            echo "✅ Utilisateur louis@4lb.ca créé avec ID: {$userId}\n";
            $user = (object) ['id' => $userId, 'email' => 'louis@4lb.ca'];
        } else {
            echo "ℹ️  Utilisateur louis@4lb.ca existe déjà (ID: {$user->id})\n";
        }

        // Attribuer le rôle superadmin
        $existingRole = DB::table('model_has_roles')
            ->where('model_id', $user->id)
            ->where('model_type', 'App\Models\User')
            ->where('role_id', $superadmin->id)
            ->first();

        if (!$existingRole) {
            DB::table('model_has_roles')->insert([
                'role_id' => $superadmin->id,
                'model_type' => 'App\Models\User',
                'model_id' => $user->id,
            ]);
            echo "✅ Rôle superadmin attribué à louis@4lb.ca\n";
        } else {
            echo "ℹ️  louis@4lb.ca a déjà le rôle superadmin\n";
        }

        echo "\n=== RÉSUMÉ FINAL ===\n";
        echo "Rôles disponibles: " . Role::count() . "\n";
        echo "Permissions disponibles: " . Permission::count() . "\n";
        echo "✅ Configuration des rôles terminée\n";
        
        // Clear cache des permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        echo "✅ Cache permissions réinitialisé\n";
    }
}
