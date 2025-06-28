<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class FixPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Créer les permissions pour les users
        $userPermissions = [
            'viewAny-users',
            'view-users', 
            'create-users',
            'update-users',
            'delete-users',
            'export-users',
        ];

        foreach ($userPermissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        // Assigner les permissions aux rôles existants
        $adminEcole = Role::where('name', 'admin_ecole')->first();
        if (!$adminEcole) {
            $adminEcole = Role::where('name', 'admin-ecole')->first();
        }

        if ($adminEcole) {
            $adminEcole->givePermissionTo($userPermissions);
            $this->command->info("Permissions ajoutées au rôle: {$adminEcole->name}");
        }

        // Vérifier les autres rôles similaires
        $roles = ['superadmin', 'admin', 'super-admin'];
        foreach ($roles as $roleName) {
            $role = Role::where('name', $roleName)->first();
            if ($role) {
                $role->givePermissionTo($userPermissions);
                $this->command->info("Permissions ajoutées au rôle: {$role->name}");
            }
        }

        $this->command->info('Permissions pour les users créées et assignées !');
    }
}
