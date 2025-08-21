<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Créer les rôles et permissions essentiels
     */
    public function run(): void
    {
        // Reset cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Créer les rôles principaux
        $roles = [
            'superadmin',
            'admin_ecole', 
            'instructeur',
            'membre'
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Créer les permissions essentielles
        $permissions = [
            // Membres
            'membres.view',
            'membres.create',
            'membres.edit',
            'membres.delete',
            'membres.export',
            'membres.changer-ceinture',
            
            // Utilisateurs
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            'users.reset-password',
            
            // Cours
            'cours.view',
            'cours.create',
            'cours.edit',
            'cours.delete',
            'cours.planning',
            
            // Présences
            'presences.view',
            'presences.tablette',
            'presences.marquer',
            'presences.rapports',
            
            // Paiements
            'paiements.view',
            'paiements.create',
            'paiements.confirmer',
            'paiements.rapports',
            
            // Admin
            'admin.dashboard',
            'admin.configuration',
            'admin.logs',
            'admin.backup',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assigner les permissions aux rôles
        $superadmin = Role::findByName('superadmin');
        $superadmin->givePermissionTo(Permission::all());

        $adminEcole = Role::findByName('admin_ecole');
        $adminEcole->givePermissionTo(Permission::whereNotIn('name', [
            'admin.backup',
            'admin.configuration'
        ])->get());

        $instructeur = Role::findByName('instructeur');
        $instructeur->givePermissionTo([
            'membres.view',
            'membres.changer-ceinture',
            'cours.view',
            'presences.view',
            'presences.tablette',
            'presences.marquer',
            'admin.dashboard',
        ]);

        $membre = Role::findByName('membre');
        $membre->givePermissionTo([
            'cours.view',
        ]);

        $this->command->info('✅ Rôles et permissions créés');
    }
}
