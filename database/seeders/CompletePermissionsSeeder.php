<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class CompletePermissionsSeeder extends Seeder
{
    public function run()
    {
        // Réinitialiser les permissions cached
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Supprimer les anciennes permissions si elles existent
        Permission::whereIn('name', [
            // Dashboard
            'view-dashboard',
            
            // Users
            'view-users', 'create-user', 'edit-user', 'delete-user', 'export-users',
            
            // Écoles  
            'view-ecoles', 'create-ecole', 'edit-ecole', 'delete-ecole',
            
            // Ceintures
            'view-ceintures', 'create-ceinture', 'edit-ceinture', 'delete-ceinture', 'assign-ceintures',
            
            // Cours
            'view-cours', 'create-cours', 'edit-cours', 'delete-cours',
            
            // Modules futurs
            'view-seminaires', 'create-seminaire', 'edit-seminaire', 'delete-seminaire',
            'view-presences', 'create-presence', 'edit-presence', 'delete-presence',
            'view-paiements', 'create-paiement', 'edit-paiement', 'delete-paiement',
        ])->delete();

        // Permissions Dashboard
        Permission::create(['name' => 'view-dashboard', 'guard_name' => 'web']);

        // Permissions Users
        $userPermissions = [
            'view-users', 'create-user', 'edit-user', 'delete-user', 'export-users'
        ];
        foreach ($userPermissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'web']);
        }

        // Permissions Écoles
        $ecolePermissions = [
            'view-ecoles', 'create-ecole', 'edit-ecole', 'delete-ecole'
        ];
        foreach ($ecolePermissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'web']);
        }

        // Permissions Ceintures
        $ceinturePermissions = [
            'view-ceintures', 'create-ceinture', 'edit-ceinture', 'delete-ceinture', 'assign-ceintures'
        ];
        foreach ($ceinturePermissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'web']);
        }

        // Permissions Cours
        $coursPermissions = [
            'view-cours', 'create-cours', 'edit-cours', 'delete-cours'
        ];
        foreach ($coursPermissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'web']);
        }

        // Permissions modules futurs
        $futureModules = [
            'seminaires' => ['view-seminaires', 'create-seminaire', 'edit-seminaire', 'delete-seminaire'],
            'presences' => ['view-presences', 'create-presence', 'edit-presence', 'delete-presence'],
            'paiements' => ['view-paiements', 'create-paiement', 'edit-paiement', 'delete-paiement'],
        ];

        foreach ($futureModules as $modulePermissions) {
            foreach ($modulePermissions as $permission) {
                Permission::create(['name' => $permission, 'guard_name' => 'web']);
            }
        }

        // Supprimer les anciens rôles s'ils existent
        Role::whereIn('name', ['super-admin', 'admin', 'instructeur', 'membre'])->delete();

        // Créer le rôle Super Admin et lui donner toutes les permissions
        $superAdminRole = Role::create(['name' => 'super-admin', 'guard_name' => 'web']);
        $superAdminRole->givePermissionTo(Permission::all());

        // Créer le rôle Admin
        $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->givePermissionTo([
            'view-dashboard',
            'view-users', 'create-user', 'edit-user', 'export-users',
            'view-ecoles', 'create-ecole', 'edit-ecole',
            'view-ceintures', 'create-ceinture', 'edit-ceinture', 'assign-ceintures',
            'view-cours', 'create-cours', 'edit-cours',
            'view-seminaires', 'create-seminaire', 'edit-seminaire',
            'view-presences', 'create-presence', 'edit-presence',
            'view-paiements', 'create-paiement', 'edit-paiement'
        ]);

        // Créer le rôle Instructeur
        $instructeurRole = Role::create(['name' => 'instructeur', 'guard_name' => 'web']);
        $instructeurRole->givePermissionTo([
            'view-dashboard',
            'view-users',
            'view-cours', 'edit-cours',
            'view-ceintures', 'assign-ceintures',
            'view-presences', 'create-presence', 'edit-presence'
        ]);

        // Créer le rôle Membre (utilisateur standard)
        $membreRole = Role::create(['name' => 'membre', 'guard_name' => 'web']);
        $membreRole->givePermissionTo([
            'view-dashboard'
        ]);

        $this->command->info('✅ Permissions et rôles créés avec succès !');
        $this->command->info('📊 Permissions créées: ' . Permission::count());
        $this->command->info('🎭 Rôles créés: ' . Role::count());
    }
}
