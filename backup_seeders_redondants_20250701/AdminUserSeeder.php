<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Créer permissions
        $permissions = [
            'manage-all', 'view-dashboard', 'manage-ecoles', 'manage-membres',
            'manage-cours', 'manage-presences', 'manage-ceintures', 'manage-seminaires',
            'manage-finances', 'view-reports', 'manage-users',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Créer rôles
        $superAdmin = Role::firstOrCreate(['name' => 'superadmin']);
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $instructeur = Role::firstOrCreate(['name' => 'instructeur']);

        // Assigner toutes permissions au superadmin
        $superAdmin->syncPermissions(Permission::all());

        // Assigner permissions limitées à admin
        $admin->syncPermissions([
            'view-dashboard', 'manage-membres', 'manage-cours',
            'manage-presences', 'manage-ceintures', 'view-reports',
        ]);

        // Créer utilisateur superadmin
        $user = User::firstOrCreate([
            'email' => 'louis@4lb.ca',
        ], [
            'name' => 'Louis Admin',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
        ]);

        $user->assignRole('superadmin');
    }
}
