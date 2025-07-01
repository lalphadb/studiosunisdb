<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Ecole;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        // Créer SuperAdmin
        $superAdmin = User::firstOrCreate(
            ['email' => 'lalpha@4lb.ca'],
            [
                'name' => 'Lalpha Super Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'ecole_id' => 2, // École existante
                'telephone' => '418-555-9999',
                'date_naissance' => '1980-01-01',
                'sexe' => 'M',
                'adresse' => '123 Rue Admin',
                'ville' => 'Québec',
                'code_postal' => 'G1V 0A1',
                'active' => true,
                'date_inscription' => now()->format('Y-m-d'),
                'notes' => 'Super administrateur du système',
            ]
        );
        $superAdmin->assignRole('super-admin');

        // Créer Admin École
        $adminEcole = User::firstOrCreate(
            ['email' => 'louis@4lb.ca'],
            [
                'name' => 'Louis Admin St-Émile',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'ecole_id' => 1, // École St-Émile
                'telephone' => '418-555-1234',
                'date_naissance' => '1975-05-15',
                'sexe' => 'M',
                'adresse' => '456 Rue École',
                'ville' => 'St-Émile',
                'code_postal' => 'G0A 4E0',
                'active' => true,
                'date_inscription' => now()->format('Y-m-d'),
                'notes' => 'Administrateur de l\'école St-Émile',
            ]
        );
        $adminEcole->assignRole('admin-ecole');

        echo "✅ Utilisateurs admin restaurés !\n";
    }
}
