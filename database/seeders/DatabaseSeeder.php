<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🌱 Début du seeding StudiosUnisDB v3.9.0...');
        
        $this->call([
            EcoleSeeder::class,
            CeintureSeeder::class,
            PermissionSeeder::class,  // ✅ NOUVEAU
            UserSeeder::class,
        ]);
        
        $this->command->info('✅ Seeding StudiosUnisDB v3.9.0 terminé avec succès!');
    }
}
