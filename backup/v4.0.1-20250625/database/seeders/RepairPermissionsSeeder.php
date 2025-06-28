<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RepairPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Réinitialiser le cache des permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->command->info('🔧 Réparation des permissions StudiosUnisDB...');

        // 1. Créer toutes les permissions nécessaires
        $permissions = [
            // Dashboard
            'admin.dashboard',

            // Écoles
            'view-ecoles', 'create-ecole', 'edit-ecole', 'delete-ecole', 'ecole.export',

            // Membres
            'view-membres', 'create-membre', 'edit-membre', 'delete-membre', 'membre.export',

            // Cours
            'view-cours', 'create-cours', 'edit-cours', 'delete-cours',

            // Présences
            'view-presences', 'create-presence', 'edit-presence', 'delete-presence', 'presence.export',

            // Ceintures
            'view-ceintures', 'create-ceinture', 'edit-ceinture', 'delete-ceinture',
            'manage-ceintures', 'assign-ceintures', 'evaluate-ceintures',

            // Séminaires
            'view-seminaires', 'create-seminaire', 'edit-seminaire', 'delete-seminaire',
            'manage-seminaires', 'inscribe-seminaires',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
            $this->command->info("✅ Permission: {$permission}");
        }

        // 2. Configurer les rôles avec leurs permissions
        $this->setupRoles();

        // 3. Restaurer les utilisateurs clés
        $this->restoreKeyUsers();

        $this->command->info('✅ Réparation terminée !');
    }

    private function setupRoles()
    {
        // SuperAdmin : toutes les permissions
        $superadmin = Role::firstOrCreate(['name' => 'superadmin']);
        $superadmin->syncPermissions(Permission::all());
        $this->command->info('✅ Rôle SuperAdmin configuré');

        // Admin d'école
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $adminPermissions = [
            'admin.dashboard',
            'view-ecoles',
            'view-membres', 'create-membre', 'edit-membre', 'delete-membre', 'membre.export',
            'view-cours', 'create-cours', 'edit-cours', 'delete-cours',
            'view-presences', 'create-presence', 'edit-presence', 'presence.export',
            'view-ceintures', 'create-ceinture', 'edit-ceinture', 'assign-ceintures', 'evaluate-ceintures',
            'view-seminaires', 'create-seminaire', 'edit-seminaire', 'manage-seminaires',
        ];
        $admin->syncPermissions($adminPermissions);
        $this->command->info('✅ Rôle Admin configuré');

        // Instructeur
        $instructeur = Role::firstOrCreate(['name' => 'instructeur']);
        $instructeurPermissions = [
            'admin.dashboard',
            'view-membres',
            'view-cours',
            'view-presences', 'create-presence', 'edit-presence',
            'view-ceintures', 'assign-ceintures',
            'view-seminaires',
        ];
        $instructeur->syncPermissions($instructeurPermissions);
        $this->command->info('✅ Rôle Instructeur configuré');

        // Membre
        $membre = Role::firstOrCreate(['name' => 'membre']);
        $membrePermissions = ['admin.dashboard'];
        $membre->syncPermissions($membrePermissions);
        $this->command->info('✅ Rôle Membre configuré');
    }

    private function restoreKeyUsers()
    {
        $keyUsers = [
            'lalpha@4lb.ca' => ['role' => 'superadmin', 'ecole_id' => null],
            'admin@studiosunisdb.com' => ['role' => 'admin', 'ecole_id' => 1],
            'root3d@4lb.ca' => ['role' => 'instructeur', 'ecole_id' => 1],
        ];

        foreach ($keyUsers as $email => $config) {
            $user = User::where('email', $email)->first();
            if ($user) {
                // Assigner le rôle
                $user->syncRoles([$config['role']]);

                // Définir l'école si nécessaire
                if ($config['ecole_id']) {
                    $user->ecole_id = $config['ecole_id'];
                    $user->save();
                }

                $this->command->info("✅ Utilisateur {$email} : rôle {$config['role']}");
            } else {
                $this->command->warn("⚠️  Utilisateur {$email} non trouvé");
            }
        }
    }
}
