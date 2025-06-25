<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CompletePermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // 1. CRÉER TOUTES LES 38 PERMISSIONS
        $permissions = [
            // Users (8)
            'viewAny-users', 'view-users', 'create-users', 'update-users', 'delete-users', 'export-users', 'assign-roles', 'manage-users',
            
            // Écoles (5)  
            'viewAny-ecoles', 'view-ecoles', 'create-ecoles', 'update-ecoles', 'delete-ecoles',
            
            // Cours (6)
            'viewAny-cours', 'view-cours', 'create-cours', 'update-cours', 'delete-cours', 'manage-cours',
            
            // Ceintures (6)
            'viewAny-ceintures', 'view-ceintures', 'create-ceintures', 'update-ceintures', 'delete-ceintures', 'assign-ceintures',
            
            // Présences (5)
            'viewAny-presences', 'view-presences', 'create-presences', 'update-presences', 'delete-presences',
            
            // Séminaires (5)
            'viewAny-seminaires', 'view-seminaires', 'create-seminaires', 'update-seminaires', 'delete-seminaires',
            
            // Paiements (5)
            'viewAny-paiements', 'view-paiements', 'create-paiements', 'update-paiements', 'delete-paiements',
        ];

        // 2. CRÉER LES PERMISSIONS
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 3. CRÉER/METTRE À JOUR LES RÔLES AVEC UNDERSCORES
        $superadmin = Role::firstOrCreate(['name' => 'superadmin']);
        $adminEcole = Role::firstOrCreate(['name' => 'admin_ecole']);
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $instructeur = Role::firstOrCreate(['name' => 'instructeur']);
        $membre = Role::firstOrCreate(['name' => 'membre']);

        // 4. ASSIGNER TOUTES LES PERMISSIONS À admin_ecole
        $adminEcole->syncPermissions($permissions);

        // 5. ASSIGNER TOUTES LES PERMISSIONS À superadmin
        $superadmin->syncPermissions($permissions);

        // 6. ASSIGNER PERMISSIONS LIMITÉES À admin
        $adminPermissions = [
            'viewAny-users', 'view-users', 'create-users', 'update-users',
            'viewAny-cours', 'view-cours', 'create-cours', 'update-cours',
            'viewAny-presences', 'view-presences', 'create-presences', 'update-presences',
            'viewAny-ceintures', 'view-ceintures', 'assign-ceintures',
        ];
        $admin->syncPermissions($adminPermissions);

        // 7. METTRE À JOUR LOUIS
        $user = User::where('email', 'louis@4lb.ca')->first();
        if ($user) {
            $user->syncRoles(['admin_ecole']);
            $this->command->info("✅ Louis@4lb.ca: {$user->getAllPermissions()->count()} permissions assignées");
        }

        $this->command->info("✅ Permissions créées: " . count($permissions));
        $this->command->info("✅ Rôles mis à jour avec underscores");
    }
}
