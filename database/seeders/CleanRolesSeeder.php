<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class CleanRolesSeeder extends Seeder
{
    public function run()
    {
        echo "=== NETTOYAGE RÔLES STUDIOSDB ===\n";
        
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Rôles canoniques FINAUX (4 seulement)
        $rolesCanoniques = [
            'superadmin' => 'Super Administrateur - Accès total système',
            'admin' => 'Administrateur École - Gestion complète école',
            'instructeur' => 'Instructeur - Gestion cours et membres',
            'membre' => 'Membre - Accès personnel limité'
        ];

        // Rôles obsolètes à supprimer
        $rolesObsoletes = ['admin_ecole', 'gestionnaire', 'super-admin'];

        echo "1️⃣ MIGRATION DES UTILISATEURS...\n";
        
        // Migrer admin_ecole → admin
        $adminEcoleRole = Role::where('name', 'admin_ecole')->first();
        $adminRole = Role::firstOrCreate(['name' => 'admin'], ['guard_name' => 'web']);
        
        if ($adminEcoleRole) {
            $usersToMigrate = DB::table('model_has_roles')
                ->where('role_id', $adminEcoleRole->id)
                ->where('model_type', 'App\Models\User')
                ->get();
                
            foreach ($usersToMigrate as $userRole) {
                // Vérifier si l'user n'a pas déjà le rôle admin
                $existingAdmin = DB::table('model_has_roles')
                    ->where('model_id', $userRole->model_id)
                    ->where('role_id', $adminRole->id)
                    ->where('model_type', 'App\Models\User')
                    ->first();
                    
                if (!$existingAdmin) {
                    DB::table('model_has_roles')->insert([
                        'role_id' => $adminRole->id,
                        'model_type' => $userRole->model_type,
                        'model_id' => $userRole->model_id,
                    ]);
                }
                
                // Supprimer l'ancien rôle (correction syntaxe)
                DB::table('model_has_roles')
                    ->where('role_id', $adminEcoleRole->id)
                    ->where('model_type', 'App\Models\User')
                    ->where('model_id', $userRole->model_id)
                    ->delete();
            }
            echo "✅ Utilisateurs admin_ecole → admin migrés\n";
        }

        // Migrer super-admin → superadmin 
        $superAdminRole = Role::where('name', 'super-admin')->first();
        $superadminRole = Role::firstOrCreate(['name' => 'superadmin'], ['guard_name' => 'web']);
        
        if ($superAdminRole) {
            $usersToMigrate = DB::table('model_has_roles')
                ->where('role_id', $superAdminRole->id)
                ->where('model_type', 'App\Models\User')
                ->get();
                
            foreach ($usersToMigrate as $userRole) {
                $existingSuper = DB::table('model_has_roles')
                    ->where('model_id', $userRole->model_id)
                    ->where('role_id', $superadminRole->id)
                    ->where('model_type', 'App\Models\User')
                    ->first();
                    
                if (!$existingSuper) {
                    DB::table('model_has_roles')->insert([
                        'role_id' => $superadminRole->id,
                        'model_type' => $userRole->model_type,
                        'model_id' => $userRole->model_id,
                    ]);
                }
                
                DB::table('model_has_roles')
                    ->where('role_id', $superAdminRole->id)
                    ->where('model_type', 'App\Models\User')
                    ->where('model_id', $userRole->model_id)
                    ->delete();
            }
            echo "✅ Utilisateurs super-admin → superadmin migrés\n";
        }

        // Migrer gestionnaire → admin (principe de moindre privilège)
        $gestionnaireRole = Role::where('name', 'gestionnaire')->first();
        if ($gestionnaireRole) {
            $usersToMigrate = DB::table('model_has_roles')
                ->where('role_id', $gestionnaireRole->id)
                ->where('model_type', 'App\Models\User')
                ->get();
                
            foreach ($usersToMigrate as $userRole) {
                $existingAdmin = DB::table('model_has_roles')
                    ->where('model_id', $userRole->model_id)
                    ->where('role_id', $adminRole->id)
                    ->where('model_type', 'App\Models\User')
                    ->first();
                    
                if (!$existingAdmin) {
                    DB::table('model_has_roles')->insert([
                        'role_id' => $adminRole->id,
                        'model_type' => $userRole->model_type,
                        'model_id' => $userRole->model_id,
                    ]);
                }
                
                DB::table('model_has_roles')
                    ->where('role_id', $gestionnaireRole->id)
                    ->where('model_type', 'App\Models\User')
                    ->where('model_id', $userRole->model_id)
                    ->delete();
            }
            echo "✅ Utilisateurs gestionnaire → admin migrés\n";
        }

        echo "\n2️⃣ SUPPRESSION RÔLES OBSOLÈTES...\n";
        
        // Supprimer les rôles obsolètes
        foreach ($rolesObsoletes as $roleName) {
            $role = Role::where('name', $roleName)->first();
            if ($role) {
                // Supprimer toutes les relations role-permissions
                DB::table('role_has_permissions')->where('role_id', $role->id)->delete();
                
                // Supprimer le rôle
                $role->delete();
                echo "🗑️  Rôle supprimé: {$roleName}\n";
            }
        }

        echo "\n3️⃣ CRÉATION RÔLES CANONIQUES...\n";
        
        // Créer/vérifier les rôles canoniques
        foreach ($rolesCanoniques as $roleName => $description) {
            $role = Role::firstOrCreate(['name' => $roleName], ['guard_name' => 'web']);
            
            if ($role->wasRecentlyCreated) {
                echo "✅ Rôle créé: {$roleName}\n";
            } else {
                echo "ℹ️  Rôle existant: {$roleName}\n";
            }
        }

        echo "\n4️⃣ PERMISSIONS ET ATTRIBUTIONS...\n";
        
        // Permissions simplifiées
        $permissions = [
            'admin-panel' => 'Accès panneau administration',
            'cours-manage' => 'Gérer les cours',
            'membres-manage' => 'Gérer les membres',
            'users-manage' => 'Gérer les utilisateurs',
        ];
        
        foreach ($permissions as $permName => $description) {
            Permission::firstOrCreate(['name' => $permName], ['guard_name' => 'web']);
        }

        // Attribution des permissions aux 4 rôles canoniques
        $superadmin = Role::findByName('superadmin');
        $admin = Role::findByName('admin');
        $instructeur = Role::findByName('instructeur');
        $membre = Role::findByName('membre');
        
        // Reset permissions avant attribution
        $superadmin->syncPermissions(Permission::all());
        $admin->syncPermissions(['admin-panel', 'cours-manage', 'membres-manage', 'users-manage']);
        $instructeur->syncPermissions(['cours-manage', 'membres-manage']);
        $membre->syncPermissions([]);
        
        echo "✅ Permissions attribuées aux 4 rôles canoniques\n";

        echo "\n5️⃣ VÉRIFICATION LOUIS@4LB.CA...\n";
        
        // Vérifier louis@4lb.ca
        $louis = DB::table('users')->where('email', 'louis@4lb.ca')->first();
        if ($louis) {
            $hasSuper = DB::table('model_has_roles')
                ->where('model_id', $louis->id)
                ->where('role_id', $superadmin->id)
                ->where('model_type', 'App\Models\User')
                ->first();
                
            if (!$hasSuper) {
                DB::table('model_has_roles')->insert([
                    'role_id' => $superadmin->id,
                    'model_type' => 'App\Models\User',
                    'model_id' => $louis->id,
                ]);
                echo "✅ louis@4lb.ca confirmé comme superadmin\n";
            } else {
                echo "ℹ️  louis@4lb.ca déjà superadmin\n";
            }
        }

        echo "\n=== RÉSUMÉ FINAL ===\n";
        $finalRoles = Role::pluck('name')->toArray();
        echo "Rôles finaux: " . implode(', ', $finalRoles) . "\n";
        echo "Total rôles: " . count($finalRoles) . " (objectif: 4)\n";
        
        if (count($finalRoles) == 4 && 
            in_array('superadmin', $finalRoles) && 
            in_array('admin', $finalRoles) && 
            in_array('instructeur', $finalRoles) && 
            in_array('membre', $finalRoles)) {
            echo "✅ NETTOYAGE RÉUSSI - 4 rôles canoniques\n";
        } else {
            echo "⚠️  Rôles supplémentaires détectés\n";
        }
        
        // Clear cache final
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        echo "✅ Cache permissions nettoyé\n";
    }
}
