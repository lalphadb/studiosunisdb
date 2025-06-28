<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class StandardPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Permissions par module (standard anglais)
        $modules = [
            'users' => ['viewAny', 'view', 'create', 'update', 'delete', 'export'],
            'ecoles' => ['viewAny', 'view', 'create', 'update', 'delete'],
            'cours' => ['viewAny', 'view', 'create', 'update', 'delete'],
            'ceintures' => ['viewAny', 'view', 'create', 'update', 'delete', 'assign'],
            'seminaires' => ['viewAny', 'view', 'create', 'update', 'delete', 'inscribe'],
            'paiements' => ['viewAny', 'view', 'create', 'update', 'delete', 'validate'],
            'presences' => ['viewAny', 'view', 'create', 'update', 'delete'],
            'inscriptions' => ['viewAny', 'view', 'create', 'update', 'delete'],
        ];

        // Créer toutes les permissions
        foreach ($modules as $module => $actions) {
            foreach ($actions as $action) {
                Permission::firstOrCreate([
                    'name' => "{$action}-{$module}",
                    'guard_name' => 'web'
                ]);
            }
        }

        // Permissions spéciales
        $specialPermissions = [
            'admin-access',
            'dashboard-view',
            'logs-view',
            'telescope-access',
        ];

        foreach ($specialPermissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        // Assigner permissions aux rôles
        $this->assignPermissionsToRoles();
    }

    private function assignPermissionsToRoles(): void
    {
        // Superadmin - toutes les permissions
        $superadmin = Role::findByName('superadmin');
        $superadmin->givePermissionTo(Permission::all());

        // Admin École - gestion de son école
        $adminEcole = Role::findByName('admin_ecole');
        $adminEcole->givePermissionTo([
            'viewAny-users', 'view-users', 'create-users', 'update-users', 'delete-users',
            'viewAny-cours', 'view-cours', 'create-cours', 'update-cours', 'delete-cours',
            'viewAny-ceintures', 'view-ceintures', 'assign-ceintures',
            'viewAny-seminaires', 'view-seminaires', 'create-seminaires', 'update-seminaires',
            'viewAny-paiements', 'view-paiements', 'create-paiements', 'update-paiements',
            'viewAny-presences', 'view-presences', 'create-presences', 'update-presences',
            'dashboard-view',
        ]);

        // Admin - gestion limitée
        $admin = Role::findByName('admin');
        $admin->givePermissionTo([
            'viewAny-users', 'view-users', 'create-users', 'update-users',
            'viewAny-cours', 'view-cours',
            'viewAny-ceintures', 'view-ceintures', 'assign-ceintures',
            'viewAny-presences', 'view-presences', 'create-presences',
            'dashboard-view',
        ]);

        // Instructeur - enseignement
        $instructeur = Role::findByName('instructeur');
        $instructeur->givePermissionTo([
            'viewAny-users', 'view-users',
            'viewAny-cours', 'view-cours',
            'viewAny-ceintures', 'view-ceintures',
            'viewAny-presences', 'view-presences', 'create-presences', 'update-presences',
            'dashboard-view',
        ]);

        // User - accès de base
        $user = Role::findByName('user');
        $user->givePermissionTo([
            'dashboard-view',
        ]);

        $this->command->info('Permissions assignées aux rôles');
    }
}
