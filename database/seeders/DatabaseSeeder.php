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
        // 1. Données de base
        $this->call([
            CompletePermissionsSeeder::class,  // Permissions et rôles
            EcolesSeeder::class,              // Écoles de test
            CeintureSeeder::class,            // 21 ceintures karaté
            AdminUsersSeeder::class,          // Utilisateurs admin
        ]);
    }
}
