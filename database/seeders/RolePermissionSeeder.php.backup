<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Créer les permissions
        $permissions = [
            // Gestion globale
            'manage-all', 'view-dashboard', 'access-admin',
            
            // Écoles
            'manage-ecoles', 'create-ecole', 'edit-ecole', 'delete-ecole', 'view-ecoles',
            
            // Membres
            'manage-membres', 'create-membre', 'edit-membre', 'delete-membre', 'view-membres',
            'approve-membre', 'suspend-membre', 'export-membres',
            
            // Cours
            'manage-cours', 'create-cours', 'edit-cours', 'delete-cours', 'view-cours',
            'assign-instructeur', 'manage-horaires',
            
            // Présences
            'manage-presences', 'take-presences', 'edit-presences', 'view-presences',
            'export-presences', 'view-statistics',
            
            // Ceintures
            'manage-ceintures', 'evaluate-ceintures', 'assign-ceintures', 'view-progressions',
            
            // Finances
            'manage-finances', 'view-paiements', 'create-paiement', 'generate-factures',
            
            // Rapports
            'view-reports', 'generate-reports', 'view-analytics', 'export-data',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Créer les rôles
        $superAdmin = Role::create(['name' => 'superadmin']);
        $admin = Role::create(['name' => 'admin']);
        $instructeur = Role::create(['name' => 'instructeur']);
        $membre = Role::create(['name' => 'membre']);

        // Assigner permissions aux rôles
        $superAdmin->givePermissionTo(Permission::all());

        $admin->givePermissionTo([
            'view-dashboard', 'access-admin',
            'manage-ecoles', 'view-ecoles', 'edit-ecole',
            'manage-membres', 'create-membre', 'edit-membre', 'view-membres',
            'manage-cours', 'create-cours', 'edit-cours', 'view-cours',
            'manage-presences', 'take-presences', 'view-presences',
            'manage-ceintures', 'evaluate-ceintures', 'view-progressions',
            'view-reports', 'view-analytics'
        ]);

        $instructeur->givePermissionTo([
            'view-dashboard', 'access-admin',
            'view-cours', 'take-presences', 'view-presences',
            'view-membres', 'evaluate-ceintures'
        ]);

        $membre->givePermissionTo([
            'view-dashboard'
        ]);

        // Créer un utilisateur super admin
        $user = User::create([
            'name' => 'Admin StudiosUnisDB',
            'email' => 'admin@studiosunisdb.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now()
        ]);

        $user->assignRole('superadmin');
    }
}
