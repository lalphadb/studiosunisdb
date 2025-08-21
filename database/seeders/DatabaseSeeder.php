<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('');
        $this->command->info('╔══════════════════════════════════════════╗');
        $this->command->info('║     SEEDING STUDIOSDB - DONNÉES DE BASE   ║');
        $this->command->info('╚══════════════════════════════════════════╝');
        $this->command->info('');
        
        // 1. École par défaut (OBLIGATOIRE - doit être en premier)
        $this->command->info('🏢 Création de l\'école par défaut...');
        $this->call(EcoleSeeder::class);
        
        // 2. Rôles et permissions (OBLIGATOIRE)
        $this->command->info('📋 Création des rôles et permissions...');
        $this->call(RolesAndPermissionsSeeder::class);
        
        // 3. Ceintures officielles (OBLIGATOIRE)
        $this->command->info('🥋 Création des 21 ceintures officielles...');
        $this->call(CeinturesSeeder::class);
        
        // 4. Super administrateur (OBLIGATOIRE)
        $this->command->info('👤 Création du super administrateur...');
        $this->call(SuperAdminSeeder::class);
        
        $this->command->info('');
        $this->command->info('╔══════════════════════════════════════════╗');
        $this->command->info('║         ✅ SEEDING TERMINÉ !              ║');
        $this->command->info('╚══════════════════════════════════════════╝');
        $this->command->info('');
        $this->command->table(
            ['Configuration', 'Valeur'],
            [
                ['École', 'Studios Unis St-Émile'],
                ['Super Admin', 'louis@4lb.ca'],
                ['Mot de passe', 'password123'],
                ['Ceintures', '21 ceintures officielles'],
                ['Rôles', '4 (superadmin, admin_ecole, instructeur, membre)'],
            ]
        );
        $this->command->info('');
    }
}
