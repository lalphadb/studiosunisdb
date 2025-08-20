<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['superadmin','admin_ecole','instructeur','membre'];
        foreach ($roles as $r) {
            Role::findOrCreate($r, 'web');
        }

        $perms = [
            'membres.view','membres.create','membres.edit','membres.delete','membres.export',
            'users.view','users.create','users.edit','users.delete','users.reset',
            'cours.view','cours.create','cours.edit','cours.delete',
        ];
        foreach ($perms as $p) {
            Permission::findOrCreate($p, 'web');
        }

        Role::findByName('superadmin')->givePermissionTo($perms);
        Role::findByName('admin_ecole')->givePermissionTo($perms);
        Role::findByName('instructeur')->givePermissionTo(['membres.view','cours.view']);
        // membre: aucune permission d'admin
    }
}
