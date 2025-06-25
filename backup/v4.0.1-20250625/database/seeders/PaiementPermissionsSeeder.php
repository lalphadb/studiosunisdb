<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PaiementPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Créer les permissions pour les paiements
        $permissions = [
            'view-paiements',
            'create-paiements', 
            'edit-paiements',
            'delete-paiements',
            'validate-paiements',
            'export-paiements'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assigner les permissions aux rôles
        $superadmin = Role::findByName('superadmin');
        $admin = Role::findByName('admin');
        $instructeur = Role::findByName('instructeur');

        // SuperAdmin : toutes les permissions
        $superadmin->givePermissionTo($permissions);

        // Admin : toutes sauf delete
        $admin->givePermissionTo([
            'view-paiements',
            'create-paiements',
            'edit-paiements', 
            'validate-paiements',
            'export-paiements'
        ]);

        // Instructeur : consultation seulement
        $instructeur->givePermissionTo(['view-paiements']);
    }
}
