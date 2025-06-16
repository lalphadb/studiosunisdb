<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PresencePermissionSeeder extends Seeder
{
    public function run()
    {
        // Créer les permissions de présence (si elles n'existent pas)
        $presencePermissions = [
            'presence.view',
            'presence.create', 
            'presence.edit',
            'presence.delete',
            'presence.export'
        ];

        foreach ($presencePermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Récupérer les rôles existants
        $superadmin = Role::findByName('superadmin');
        $admin = Role::findByName('admin');
        $instructeur = Role::findByName('instructeur');

        // Attribuer les permissions aux rôles
        $superadmin->givePermissionTo($presencePermissions);
        
        $admin->givePermissionTo([
            'presence.view',
            'presence.create',
            'presence.edit', 
            'presence.delete',
            'presence.export'
        ]);

        $instructeur->givePermissionTo([
            'presence.view',
            'presence.create',
            'presence.edit'
        ]);

        echo "✅ Permissions de présences ajoutées avec succès!\n";
    }
}
