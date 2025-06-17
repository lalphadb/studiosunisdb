<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class FixPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Réinitialiser le cache des permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Créer/vérifier toutes les permissions nécessaires
        $permissions = [
            // Ceintures
            'view-ceintures',
            'create-ceinture',
            'edit-ceinture',
            'delete-ceinture',
            'manage-ceintures',
            'assign-ceintures',
            'evaluate-ceintures',

            // Présences
            'view-presences',
            'create-presence',
            'edit-presence',
            'delete-presence',
            'presence.export',

            // Autres permissions essentielles
            'view-ecoles',
            'view-membres',
            'view-cours',
            'view-seminaires',
            'admin.dashboard',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 2. Réassigner les permissions aux rôles
        $superadmin = Role::firstOrCreate(['name' => 'superadmin']);
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $instructeur = Role::firstOrCreate(['name' => 'instructeur']);
        $membre = Role::firstOrCreate(['name' => 'membre']);

        // SuperAdmin : toutes les permissions
        $superadmin->syncPermissions(Permission::all());

        // Admin : gestion de son école
        $adminPermissions = [
            'view-ceintures', 'create-ceinture', 'edit-ceinture', 'assign-ceintures',
            'view-presences', 'create-presence', 'edit-presence', 'presence.export',
            'view-ecoles', 'view-membres', 'view-cours', 'view-seminaires',
            'admin.dashboard',
        ];
        $admin->syncPermissions($adminPermissions);

        // Instructeur : cours et présences
        $instructeurPermissions = [
            'view-ceintures', 'assign-ceintures',
            'view-presences', 'create-presence', 'edit-presence',
            'view-membres', 'view-cours',
            'admin.dashboard',
        ];
        $instructeur->syncPermissions($instructeurPermissions);

        // 3. Réassigner les rôles aux utilisateurs existants
        $this->fixUserRoles();

        $this->command->info('Permissions restaurées avec succès !');
    }

    private function fixUserRoles()
    {
        // Utilisateurs spécifiques à restaurer
        $users = [
            'lalpha@4lb.ca' => 'superadmin',
            'admin@studiosunisdb.com' => 'admin',
            'root3d@4lb.ca' => 'instructeur',
        ];

        foreach ($users as $email => $roleName) {
            $user = User::where('email', $email)->first();
            if ($user) {
                $user->syncRoles([$roleName]);
                $this->command->info("Rôle {$roleName} assigné à {$email}");
            }
        }
    }
}
