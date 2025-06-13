<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Ordre important : Écoles en premier
        $this->call([
            EcoleSeeder::class,
            CeintureSeeder::class,
            RolePermissionSeeder::class,
            UserSeeder::class,
        ]);
    }
}
