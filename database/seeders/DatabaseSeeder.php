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
        // Seeders dans l'ordre de dépendance
        $this->call([
            CeintureSeeder::class,      // D'abord les ceintures
            SuperAdminSeeder::class,    // Ensuite l'admin
            MembreSeeder::class,        // Puis les membres (dépendent des ceintures)
            CoursSeeder::class,         // Les cours
            PaiementSeeder::class,      // Enfin les paiements (dépendent des membres)
        ]);
        
        $this->command->info('✅ Tous les seeders ont été exécutés avec succès !');
    }
}
