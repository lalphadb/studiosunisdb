<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Ecole;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. SuperAdmin
        $superadmin = User::updateOrCreate(
            ['email' => 'superadmin@studiosdb.ca'],
            [
                'nom' => 'Super',
                'prenom' => 'Admin',
                'password' => Hash::make('StudiosDB2024!'),
                'ecole_id' => Ecole::where('code', 'STU-001')->first()->id,
                'telephone' => '1-888-STUDIOS',
                'actif' => true,
                'email_verified_at' => now(),
                'code_utilisateur' => 'SA-0001',
            ]
        );
        $superadmin->assignRole('superadmin');
        
        // 2. Admin technique
        $lalpha = User::updateOrCreate(
            ['email' => 'lalpha@4lb.ca'],
            [
                'nom' => 'Louis',
                'prenom' => 'Alpha',
                'password' => Hash::make('password123'),
                'ecole_id' => Ecole::where('code', 'STU-001')->first()->id,
                'telephone' => '514-123-4567',
                'actif' => true,
                'email_verified_at' => now(),
                'code_utilisateur' => 'LA-0001',
            ]
        );
        $lalpha->assignRole('superadmin');
        
        // 3. Admin École St-Émile
        $louis = User::updateOrCreate(
            ['email' => 'louis@4lb.ca'],
            [
                'nom' => 'Louis',
                'prenom' => 'Tremblay',
                'password' => Hash::make('password123'),
                'ecole_id' => Ecole::where('code', 'STU-002')->first()->id,
                'telephone' => '418-123-4567',
                'actif' => true,
                'email_verified_at' => now(),
                'code_utilisateur' => 'LT-0001',
            ]
        );
        $louis->assignRole('admin_ecole');
        
        $this->command->info('✅ Utilisateurs créés:');
        $this->command->info('   - superadmin@studiosdb.ca / StudiosDB2024!');
        $this->command->info('   - lalpha@4lb.ca / password123');
        $this->command->info('   - louis@4lb.ca / password123');
    }
}
