<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        // Créer toutes les permissions
        $permissions = [
            'view-presences', 'create-presence', 'edit-presence', 'delete-presence',
            'view-ecoles', 'create-ecole', 'edit-ecole', 'delete-ecole',
            'view-ceintures', 'create-ceinture', 'edit-ceinture', 'delete-ceinture',
            'view-users', 'create-user', 'edit-user', 'delete-user',
            'view-cours', 'create-cours', 'edit-cours', 'delete-cours',
            'view-seminaires', 'create-seminaire', 'edit-seminaire', 'delete-seminaire',
            'view-paiements', 'create-paiement', 'edit-paiement', 'delete-paiement',
            'view-inscriptions', 'create-inscription', 'edit-inscription', 'delete-inscription',
            'manage-all', 'admin-access'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Créer le rôle superadmin
        $superadminRole = Role::firstOrCreate(['name' => 'superadmin']);
        
        // Donner toutes les permissions au superadmin
        $superadminRole->givePermissionTo(Permission::all());

        // Assigner le rôle à Alpha
        $alpha = User::where('email', 'lalpha@4lb.ca')->first();
        if ($alpha) {
            $alpha->assignRole('superadmin');
            $this->command->info('✅ Alpha configuré comme superadmin avec toutes les permissions!');
        }

        // Créer rôle admin_ecole pour Louis
        $adminEcoleRole = Role::firstOrCreate(['name' => 'admin_ecole']);
        $adminEcoleRole->givePermissionTo([
            'view-presences', 'create-presence', 'edit-presence',
            'view-ecoles', 'view-ceintures', 'view-users',
            'view-cours', 'create-cours', 'edit-cours'
        ]);

        $louis = User::where('email', 'louis@4lb.ca')->first();
        if ($louis) {
            $louis->assignRole('admin_ecole');
            $this->command->info('✅ Louis configuré comme admin école!');
        }
    }
}
