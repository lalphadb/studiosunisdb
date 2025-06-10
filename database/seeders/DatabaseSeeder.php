<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Permissions et rôles en premier
        $this->call(RolePermissionSeeder::class);
        
        // Utilisateurs de base
        $this->call(FinalUsersSeeder::class);
        
        // Données de test riches
        $this->call(RichTestDataSeeder::class);
    }
}
