<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Ecole;
use Illuminate\Support\Facades\Hash;

class AdminUsersSeeder extends Seeder
{
    public function run(): void
    {
        // Vérifier qu'il y a des écoles
        if (Ecole::count() === 0) {
            $this->command->error('❌ Aucune école trouvée. Exécuter EcoleSeeder d\'abord.');
            return;
        }

        // 1. SUPER ADMIN GLOBAL (ecole_id = NULL)
        $superAdmin = User::firstOrCreate(
            ['email' => 'lalpha@4lb.ca'],
            [
                'name' => 'Super Admin StudiosDB',
                'email' => 'lalpha@4lb.ca',
                'password' => Hash::make('password123'),
                'ecole_id' => null, // Accès global
                'telephone' => '418-123-4567',
                'active' => true,
                'email_verified_at' => now(),
            ]
        );
        $superAdmin->assignRole('superadmin');

        // 2. SUPER ADMIN BACKUP
        $superAdminBackup = User::firstOrCreate(
            ['email' => 'r00t3d@pm.me'],
            [
                'name' => 'Root Admin Backup',
                'email' => 'r00t3d@pm.me', 
                'password' => Hash::make('password123'),
                'ecole_id' => null,
                'active' => true,
                'email_verified_at' => now(),
            ]
        );
        $superAdminBackup->assignRole('superadmin');

        // 3. ADMIN QUÉBEC (QBC)
        $ecoleQuebec = Ecole::where('code', 'QBC')->first();
        if ($ecoleQuebec) {
            $adminQuebec = User::firstOrCreate(
                ['email' => 'root3d@pm.me'],
                [
                    'name' => 'Admin Québec',
                    'email' => 'root3d@pm.me',
                    'password' => Hash::make('password123'),
                    'ecole_id' => $ecoleQuebec->id,
                    'telephone' => '418-555-0001',
                    'active' => true,
                    'email_verified_at' => now(),
                ]
            );
            $adminQuebec->assignRole('admin');
            $this->command->info("✅ Admin Québec créé (École ID: {$ecoleQuebec->id})");
        } else {
            $this->command->warn("⚠️ École QBC non trouvée");
        }

        // 4. ADMIN ST-ÉMILE (STE)
        $ecoleSTE = Ecole::where('code', 'STE')->first();
        if ($ecoleSTE) {
            $adminSTE = User::firstOrCreate(
                ['email' => 'louis@4lb.ca'],
                [
                    'name' => 'Louis Admin St-Émile',
                    'email' => 'louis@4lb.ca',
                    'password' => Hash::make('password123'),
                    'ecole_id' => $ecoleSTE->id,
                    'telephone' => '418-555-0002',
                    'active' => true,
                    'email_verified_at' => now(),
                ]
            );
            $adminSTE->assignRole('admin');
            $this->command->info("✅ Admin St-Émile créé (École ID: {$ecoleSTE->id})");
        } else {
            $this->command->warn("⚠️ École STE non trouvée");
        }

        // 5. INSTRUCTEUR TEST MONTRÉAL
        $ecoleMTL = Ecole::where('code', 'MTL')->first();
        if ($ecoleMTL) {
            $instructeurMTL = User::firstOrCreate(
                ['email' => 'instructeur@mtl.ca'],
                [
                    'name' => 'Instructeur Montréal',
                    'email' => 'instructeur@mtl.ca',
                    'password' => Hash::make('password123'),
                    'ecole_id' => $ecoleMTL->id,
                    'telephone' => '514-555-0003',
                    'active' => true,
                    'email_verified_at' => now(),
                ]
            );
            $instructeurMTL->assignRole('instructeur');
            $this->command->info("✅ Instructeur Montréal créé");
        }

        // 6. MEMBRE TEST - Prendre la première école disponible
        $premiereEcole = Ecole::first();
        if ($premiereEcole) {
            $membreTest = User::firstOrCreate(
                ['email' => 'membre@test.ca'],
                [
                    'name' => 'Membre Test',
                    'email' => 'membre@test.ca',
                    'password' => Hash::make('password123'),
                    'ecole_id' => $premiereEcole->id, // Première école disponible
                    'telephone' => '418-555-9999',
                    'date_naissance' => '1990-01-01',
                    'sexe' => 'M',
                    'active' => true,
                    'email_verified_at' => now(),
                ]
            );
            $membreTest->assignRole('membre');
            $this->command->info("✅ Membre Test créé (École: {$premiereEcole->nom})");
        }

        $this->command->info('✅ Utilisateurs administrateurs créés:');
        $this->command->info("   SuperAdmin: lalpha@4lb.ca (accès global)");
        $this->command->info("   SuperAdmin: r00t3d@pm.me (backup)");
        $this->command->info("   Mot de passe: password123");
    }
}
