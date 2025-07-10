<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Ecole;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UsersTestSeeder extends Seeder
{
    public function run(): void
    {
        // Créer les rôles s'ils n'existent pas
        $roles = ['superadmin', 'admin_ecole', 'admin', 'instructeur', 'membre'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // Trouver ou créer l'école St-Émile
        $ecoleStEmile = Ecole::firstOrCreate([
            'code' => 'ST-EMILE'
        ], [
            'nom' => 'Studio St-Émile',
            'adresse' => '123 Rue Principale',
            'ville' => 'St-Émile',
            'province' => 'QC',
            'code_postal' => 'G0A 4A0',
            'telephone' => '(418) 123-4567',
            'email' => 'stemile@studiosdb.com',
            'proprietaire' => 'Louis Tremblay',
            'is_active' => true
        ]);

        // 1. Superadmin (lalpha@4lb.ca)
        $superadmin = User::firstOrCreate([
            'email' => 'lalpha@4lb.ca'
        ], [
            'name' => 'L. Alpha Superadmin',
            'password' => Hash::make('password123'),
            'ecole_id' => null, // Superadmin n'est lié à aucune école spécifique
            'telephone' => '(514) 123-4567',
            'date_naissance' => '1985-01-01',
            'sexe' => 'M',
            'adresse' => '456 Rue Admin',
            'ville' => 'Montréal',
            'code_postal' => 'H1A 1A1',
            'is_active' => true,
            'date_inscription' => now()->toDateString(),
            'email_verified_at' => now()
        ]);

        // Assigner le rôle superadmin
        $superadmin->assignRole('superadmin');

        // 2. Admin École St-Émile (louis@4lb.ca)
        $adminEcole = User::firstOrCreate([
            'email' => 'louis@4lb.ca'
        ], [
            'name' => 'Louis Tremblay',
            'password' => Hash::make('password123'),
            'ecole_id' => $ecoleStEmile->id,
            'telephone' => '(418) 123-4567',
            'date_naissance' => '1980-05-15',
            'sexe' => 'M',
            'adresse' => '789 Rue de l\'École',
            'ville' => 'St-Émile',
            'code_postal' => 'G0A 4A0',
            'contact_urgence_nom' => 'Marie Tremblay',
            'contact_urgence_telephone' => '(418) 123-4568',
            'is_active' => true,
            'date_inscription' => now()->toDateString(),
            'email_verified_at' => now()
        ]);

        // Assigner le rôle admin_ecole
        $adminEcole->assignRole('admin_ecole');

        $this->command->info('✅ Utilisateurs test créés avec succès :');
        $this->command->info('   - lalpha@4lb.ca (superadmin) / password123');
        $this->command->info('   - louis@4lb.ca (admin_ecole St-Émile) / password123');
    }
}
