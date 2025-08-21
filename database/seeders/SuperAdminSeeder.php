<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Créer le compte super administrateur louis@4lb.ca
     */
    public function run(): void
    {
        // Créer ou mettre à jour le super admin LOUIS
        $superAdmin = User::updateOrCreate(
            ['email' => 'louis@4lb.ca'],
            [
                'name' => 'Louis Boudreau',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );

        // Créer le rôle superadmin si n'existe pas
        $role = Role::firstOrCreate(['name' => 'superadmin']);
        
        // Assigner le rôle
        $superAdmin->syncRoles(['superadmin']);

        $this->command->info('✅ Super Admin configuré : louis@4lb.ca');
        $this->command->info('   Mot de passe : password123');
    }
}
