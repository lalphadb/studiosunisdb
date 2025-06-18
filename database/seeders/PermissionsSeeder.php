<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Ecole;
use Illuminate\Support\Facades\Hash;

class PermissionsSeeder extends Seeder
{
    public function run()
    {
        // Créer les rôles
        $superadmin = Role::firstOrCreate(['name' => 'superadmin']);
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $instructeur = Role::firstOrCreate(['name' => 'instructeur']);
        $membre = Role::firstOrCreate(['name' => 'membre']);

        // Créer toutes les permissions
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
            'view-paiements', 'create-paiements', 'edit-paiements', 'delete-paiements', 'validate-paiements', 'export-paiements'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assigner permissions aux rôles
        
        // SuperAdmin : toutes les permissions
        $superadmin->givePermissionTo($permissions);

        // Admin : gestion complète sauf suppression critique
        $admin->givePermissionTo([
            'view-ecoles', 'edit-ecole', 'ecole.export',
            'view-membres', 'create-membre', 'edit-membre', 'delete-membre', 'membre.export',
            'view-cours', 'create-cours', 'edit-cours', 'delete-cours',
            'view-presences', 'create-presence', 'edit-presence', 'presence.export',
            'view-ceintures', 'create-ceinture', 'edit-ceinture', 'manage-ceintures', 'assign-ceintures',
            'view-seminaires', 'create-seminaire', 'edit-seminaire', 'manage-seminaires', 'inscribe-seminaires',
            'view-paiements', 'create-paiements', 'edit-paiements', 'validate-paiements', 'export-paiements'
        ]);

        // Instructeur : gestion opérationnelle
        $instructeur->givePermissionTo([
            'view-ecoles',
            'view-membres', 'create-membre', 'edit-membre',
            'view-cours', 'create-cours', 'edit-cours',
            'view-presences', 'create-presence', 'edit-presence',
            'view-ceintures', 'assign-ceintures',
            'view-seminaires', 'inscribe-seminaires',
            'view-paiements'
        ]);

        // Membre : consultation seulement
        $membre->givePermissionTo([
            'view-cours',
            'view-presences',
            'view-ceintures',
            'view-seminaires'
        ]);

        // Créer les utilisateurs par défaut avec écoles
        $stEmileEcole = Ecole::where('code', 'STEMI')->first();
        $quebecEcole = Ecole::where('code', 'QUEBEC')->first();

        // SuperAdmin
        $superadminUser = User::firstOrCreate(
            ['email' => 'lalpha@4lb.ca'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('QwerTfc443-studios!'),
                'ecole_id' => null // SuperAdmin pas attaché à une école
            ]
        );
        $superadminUser->assignRole('superadmin');

        // Admin St-Émile
        if ($stEmileEcole) {
            $adminStEmile = User::firstOrCreate(
                ['email' => 'louis@4lb.ca'],
                [
                    'name' => 'Louis Admin St-Émile',
                    'password' => Hash::make('Bobby2111'),
                    'ecole_id' => $stEmileEcole->id
                ]
            );
            $adminStEmile->assignRole('admin');
        }

        // Admin Québec
        if ($quebecEcole) {
            $adminQuebec = User::firstOrCreate(
                ['email' => 'root3d@pm.me'],
                [
                    'name' => 'Admin Québec',
                    'password' => Hash::make('B0bby2111'),
                    'ecole_id' => $quebecEcole->id
                ]
            );
            $adminQuebec->assignRole('admin');
        }
    }
}
