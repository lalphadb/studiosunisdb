<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class FinalRepairSeeder extends Seeder
{
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->command->info('🔧 Configuration finale StudiosUnisDB basée sur votre DB...');

        // 1. Créer toutes les permissions selon votre structure
        $permissions = [
            'admin.dashboard',
            
            // Écoles
            'view-ecoles', 'create-ecole', 'edit-ecole', 'delete-ecole',
            
            // Users (votre table users)
            'view-users', 'create-user', 'edit-user', 'delete-user',
            
            // Cours
            'view-cours', 'create-cours', 'edit-cours', 'delete-cours',
            
            // Présences
            'view-presences', 'create-presence', 'edit-presence', 'delete-presence',
            
            // Ceintures (votre table membre_ceintures référence users)
            'view-ceintures', 'create-ceinture', 'edit-ceinture', 'delete-ceinture',
            'assign-ceintures', 'evaluate-ceintures',
            
            // Séminaires
            'view-seminaires', 'create-seminaire', 'edit-seminaire', 'delete-seminaire',
            
            // Paiements (votre table paiements référence users)
            'view-paiements', 'create-paiement', 'edit-paiement', 'delete-paiement',
            'validate-paiements',
            
            // Inscriptions (vos tables inscriptions_* référencent users)
            'view-inscriptions', 'create-inscription', 'edit-inscription', 'delete-inscription',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
            $this->command->info("✅ Permission: {$permission}");
        }

        // 2. Configurer les rôles
        $this->setupRoles();

        // 3. Restaurer l'utilisateur Alpha Admin (ID 20)
        $this->restoreAlphaAdmin();

        $this->command->info('✅ Configuration terminée !');
    }

    private function setupRoles()
    {
        // SuperAdmin : toutes les permissions
        $superadmin = Role::firstOrCreate(['name' => 'superadmin', 'guard_name' => 'web']);
        $superadmin->syncPermissions(Permission::all());
        $this->command->info('✅ Rôle SuperAdmin configuré');

        // Admin d'école
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminPermissions = [
            'admin.dashboard',
            'view-ecoles',
            'view-users', 'create-user', 'edit-user', 'delete-user',
            'view-cours', 'create-cours', 'edit-cours', 'delete-cours',
            'view-presences', 'create-presence', 'edit-presence',
            'view-ceintures', 'assign-ceintures', 'evaluate-ceintures',
            'view-seminaires', 'create-seminaire', 'edit-seminaire',
            'view-paiements', 'create-paiement', 'edit-paiement', 'validate-paiements',
            'view-inscriptions', 'create-inscription', 'edit-inscription',
        ];
        $admin->syncPermissions($adminPermissions);
        $this->command->info('✅ Rôle Admin configuré');

        // Instructeur
        $instructeur = Role::firstOrCreate(['name' => 'instructeur', 'guard_name' => 'web']);
        $instructeurPermissions = [
            'admin.dashboard',
            'view-users',
            'view-cours',
            'view-presences', 'create-presence', 'edit-presence',
            'view-ceintures', 'assign-ceintures',
            'view-seminaires',
        ];
        $instructeur->syncPermissions($instructeurPermissions);
        $this->command->info('✅ Rôle Instructeur configuré');

        // User/Membre
        $user = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
        $userPermissions = ['admin.dashboard'];
        $user->syncPermissions($userPermissions);
        $this->command->info('✅ Rôle User configuré');
    }

    private function restoreAlphaAdmin()
    {
        // Alpha Admin ID 20 selon Telescope
        $alpha = User::find(20);
        if ($alpha) {
            $alpha->syncRoles(['superadmin']);
            $this->command->info("✅ Alpha Admin (ID: 20, Email: {$alpha->email}) configuré comme superadmin");
        } else {
            $this->command->warn("⚠️  Utilisateur ID 20 non trouvé");
            
            // Chercher par email comme fallback
            $alpha = User::where('email', 'lalpha@4lb.ca')->first();
            if ($alpha) {
                $alpha->syncRoles(['superadmin']);
                $this->command->info("✅ Alpha Admin trouvé par email et configuré");
            }
        }
    }
}
