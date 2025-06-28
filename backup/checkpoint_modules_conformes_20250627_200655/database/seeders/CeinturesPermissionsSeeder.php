<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CeinturesPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Créer les permissions pour les ceintures
        $ceinturePermissions = [
            'viewAny-ceintures',
            'view-ceintures',
            'create-ceintures',
            'update-ceintures',
            'delete-ceintures',
            'assign-ceintures',
        ];

        foreach ($ceinturePermissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        // Assigner les permissions aux rôles
        $roles = [
            'admin_ecole' => $ceinturePermissions,
            'admin-ecole' => $ceinturePermissions,
            'superadmin' => $ceinturePermissions,
            'super-admin' => $ceinturePermissions,
            'admin' => ['viewAny-ceintures', 'view-ceintures', 'assign-ceintures'],
            'instructeur' => ['viewAny-ceintures', 'view-ceintures'],
        ];

        foreach ($roles as $roleName => $permissions) {
            $role = Role::where('name', $roleName)->first();
            if ($role) {
                $role->givePermissionTo($permissions);
                $this->command->info("Permissions ceintures ajoutées au rôle: {$role->name}");
            }
        }

        $this->command->info('Permissions pour les ceintures créées et assignées !');
    }
}
