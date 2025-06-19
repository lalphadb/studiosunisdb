<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Ecole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Créer les rôles
        $roles = ['superadmin', 'admin', 'instructeur', 'membre'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Super Admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'lalpha@4lb.ca'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password123'),
                'ecole_id' => null,
                'email_verified_at' => now(),
            ]
        );
        $superAdmin->assignRole('superadmin');

        // Admin Québec (code QBC)
        $ecoleQuebec = Ecole::where('code', 'QBC')->first();
        if ($ecoleQuebec) {
            $adminQuebec = User::firstOrCreate(
                ['email' => 'root3d@pm.me'],
                [
                    'name' => 'Admin Québec',
                    'password' => Hash::make('password123'),
                    'ecole_id' => $ecoleQuebec->id,
                    'email_verified_at' => now(),
                ]
            );
            $adminQuebec->assignRole('admin');
        }

        // Admin St-Émile (code STE)
        $ecoleStEmile = Ecole::where('code', 'STE')->first();
        if ($ecoleStEmile) {
            $adminStEmile = User::firstOrCreate(
                ['email' => 'louis@4lb.ca'],
                [
                    'name' => 'Louis Admin St-Émile',
                    'password' => Hash::make('password123'),
                    'ecole_id' => $ecoleStEmile->id,
                    'email_verified_at' => now(),
                ]
            );
            $adminStEmile->assignRole('admin');
        }

        $this->command->info('✅ Utilisateurs administrateurs créés avec codes courts');
    }
}
