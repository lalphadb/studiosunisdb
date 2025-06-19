<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Définir toutes les permissions nécessaires
        $permissions = [
            // Écoles
            'view-ecoles', 'create-ecole', 'edit-ecole', 'delete-ecole', 'ecole.export',
            
            // Membres
            'view-membres', 'create-membre', 'edit-membre', 'delete-membre', 'membre.export',
            
            // Cours
            'view-cours', 'create-cours', 'edit-cours', 'delete-cours',
            
            // Présences
            'view-presences', 'create-presence', 'edit-presence', 'delete-presence', 'presence.export',
            
            // Ceintures
            'view-ceintures', 'create-ceinture', 'edit-ceinture', 'delete-ceinture', 'manage-ceintures', 'assign-ceintures',
            
            // Séminaires
            'view-seminaires', 'create-seminaire', 'edit-seminaire', 'delete-seminaire', 'manage-seminaires', 'inscribe-seminaires',
            
            // Paiements
            'view-paiements', 'create-paiements', 'edit-paiements', 'delete-paiements', 'validate-paiements', 'export-paiements',
            
            // Administration
            'manage-roles'
        ];

        // Créer toutes les permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assigner permissions aux rôles
        $superadmin = Role::where('name', 'superadmin')->first();
        $admin = Role::where('name', 'admin')->first();
        $instructeur = Role::where('name', 'instructeur')->first();
        $membre = Role::where('name', 'membre')->first();

        if ($superadmin) {
            $superadmin->syncPermissions($permissions);
        }

        if ($admin) {
            $adminPermissions = [
                'view-ecoles', 'edit-ecole', 'ecole.export',
                'view-membres', 'create-membre', 'edit-membre', 'membre.export',
                'view-cours', 'create-cours', 'edit-cours',
                'view-presences', 'create-presence', 'edit-presence', 'presence.export',
                'view-ceintures', 'assign-ceintures', 'manage-ceintures',
                'view-seminaires', 'create-seminaire', 'edit-seminaire', 'inscribe-seminaires',
                'view-paiements', 'create-paiements', 'edit-paiements', 'validate-paiements', 'export-paiements'
            ];
            $admin->syncPermissions($adminPermissions);
        }

        if ($instructeur) {
            $instructeurPermissions = [
                'view-ecoles', 'view-membres', 'create-membre', 'edit-membre',
                'view-cours', 'create-cours', 'edit-cours',
                'view-presences', 'create-presence', 'edit-presence',
                'view-ceintures', 'assign-ceintures',
                'view-seminaires', 'inscribe-seminaires',
                'view-paiements'
            ];
            $instructeur->syncPermissions($instructeurPermissions);
        }

        if ($membre) {
            $membrePermissions = [
                'view-cours', 'view-presences', 'view-ceintures', 'view-seminaires'
            ];
            $membre->syncPermissions($membrePermissions);
        }

        $this->command->info('✅ Permissions créées et assignées aux rôles');
    }
}
