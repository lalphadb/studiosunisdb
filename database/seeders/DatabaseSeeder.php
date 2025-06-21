<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🚀 Début du seeding StudiosUnisDB...');
        
        // 1. Écoles (si pas déjà fait)
        $this->call(EcoleSeeder::class);
        
        // 2. Ceintures (si pas déjà fait)
        $this->call(CeintureSeeder::class);
        
        // 3. Rôles et Permissions CRITIQUES
        $this->call(RolePermissionSeeder::class);
        
        // 4. Utilisateurs administrateurs
        $this->call(AdminUsersSeeder::class);
        
        $this->command->info('✅ Seeding terminé avec succès!');
        $this->command->info('');
        $this->command->info('👤 COMPTES CRÉÉS:');
        $this->command->info('   SuperAdmin: lalpha@4lb.ca / password123');
        $this->command->info('   Admin QBC: root3d@pm.me / password123');  
        $this->command->info('   Admin STE: louis@4lb.ca / password123');
        $this->command->info('');
        $this->command->info('🔐 ACCÈS:');
        $this->command->info('   Dashboard: /admin/dashboard');
        $this->command->info('   Telescope: /telescope (SuperAdmin only)');
    }
}
