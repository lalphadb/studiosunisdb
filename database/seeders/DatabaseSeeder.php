<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🌱 Démarrage du seeding StudiosDB...');
        
        Schema::disableForeignKeyConstraints();
        
        try {
            DB::beginTransaction();
            
            // 1. Permissions et Rôles
            $this->command->info("\n1️⃣ Création des rôles et permissions...");
            $this->call(CompletePermissionsSeeder::class);
            
            // 2. Les Écoles
            $this->command->info("\n2️⃣ Création des écoles...");
            $this->call(EcolesSeeder::class);
            
            // 3. Les utilisateurs
            $this->command->info("\n3️⃣ Création des utilisateurs...");
            $this->call(UserSeeder::class);
            
            // 4. Les Ceintures
            $this->command->info("\n4️⃣ Création des ceintures...");
            $this->call(CeinturesSeeder::class);
            
            // 5. Données de test (optionnel)
            if (app()->environment('local')) {
                $this->command->info("\n5️⃣ Création des données de test...");
                $this->call(TestDataSeeder::class);
            }
            
            DB::commit();
            $this->command->info("\n✅ Seeding terminé avec succès!");
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('❌ Erreur: ' . $e->getMessage());
            throw $e;
        } finally {
            Schema::enableForeignKeyConstraints();
        }
    }
}
