<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🌱 Démarrage du seeding StudiosUnisDB...');
        $this->command->info('📋 24 écoles et 21 ceintures par école seront créées');
        
        // Désactiver les foreign keys temporairement
        Schema::disableForeignKeyConstraints();
        
        try {
            DB::beginTransaction();
            
            // 1. Permissions et Rôles
            $this->command->info("\n1️⃣ Création des rôles et permissions...");
            $this->call(CompletePermissionsSeeder::class);
            
            // 2. Les 24 Écoles StudiosUnis
            $this->command->info("\n2️⃣ Création des 24 écoles StudiosUnis...");
            $this->call(EcolesSeeder::class);
            
            // 3. SuperAdmin et utilisateurs
            $this->command->info("\n3️⃣ Création des utilisateurs...");
            $this->call(SuperAdminSeeder::class);
            $this->call(AdminUsersSeeder::class);
            
            // 4. Les 21 Ceintures pour chaque école
            $this->command->info("\n4️⃣ Création des 21 ceintures par école...");
            $this->call(CeinturesSeeder::class);
            
            DB::commit();
            $this->command->info("\n✅ Seeding terminé avec succès!");
            $this->command->info("📊 Résumé: 24 écoles, " . (24 * 21) . " ceintures créées");
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('❌ Erreur lors du seeding: ' . $e->getMessage());
            throw $e;
        } finally {
            Schema::enableForeignKeyConstraints();
        }
    }
}
