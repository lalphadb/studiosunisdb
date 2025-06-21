<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Créer toutes les permissions
        $permissions = [
            // Users
            'view-users',
            'create-user', 
            'edit-user',
            'delete-user',
            'export-users',
            
            // Écoles
            'view-ecoles',
            'create-ecole',
            'edit-ecole', 
            'delete-ecole',
            
            // Cours
            'view-cours',
            'create-cours',
            'edit-cours',
            'delete-cours',
            'manage-horaires',
            
            // Présences
            'view-presences',
            'create-presence',
            'edit-presence',
            'delete-presence',
            'scan-qr-presence',
            
            // Ceintures
            'view-ceintures',
            'create-ceinture',
            'edit-ceinture',
            'delete-ceinture',
            'assign-ceintures',
            'manage-ceintures',
            
            // Séminaires
            'view-seminaires',
            'create-seminaire',
            'edit-seminaire',
            'delete-seminaire',
            'manage-seminaires',
            'inscribe-seminaires',
            
            // Paiements
            'view-paiements',
            'create-paiements',
            'edit-paiements',
            'delete-paiements',
            'validate-paiements',
            'export-paiements',
            
            // Système
            'manage-roles',
            'export-data',
            'view-activity-log',
            'access-telescope',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // RÔLE: SuperAdmin (accès global)
        $superadmin = Role::firstOrCreate(['name' => 'superadmin', 'guard_name' => 'web']);
        $superadmin->givePermissionTo(Permission::all());

        // RÔLE: Admin (école spécifique)
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->givePermissionTo([
            'view-users', 'create-user', 'edit-user', 'delete-user', 'export-users',
            'view-cours', 'create-cours', 'edit-cours', 'delete-cours', 'manage-horaires',
            'view-presences', 'create-presence', 'edit-presence', 'scan-qr-presence',
            'view-ceintures', 'assign-ceintures', 'manage-ceintures',
            'view-seminaires', 'create-seminaire', 'edit-seminaire', 'manage-seminaires',
            'view-paiements', 'create-paiements', 'edit-paiements', 'validate-paiements',
            'export-data', 'view-activity-log',
        ]);

        // RÔLE: Instructeur
        $instructeur = Role::firstOrCreate(['name' => 'instructeur', 'guard_name' => 'web']);
        $instructeur->givePermissionTo([
            'view-users', 'view-cours',
            'view-presences', 'create-presence', 'edit-presence', 'scan-qr-presence',
            'view-ceintures', 'assign-ceintures',
            'view-seminaires',
        ]);

        // RÔLE: Membre
        $membre = Role::firstOrCreate(['name' => 'membre', 'guard_name' => 'web']);
        $membre->givePermissionTo([
            'view-presences', // ses propres présences
            'view-ceintures', // ses propres ceintures
        ]);

        $this->command->info('✅ Rôles et permissions créés avec succès');
    }
}
