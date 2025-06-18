<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            EcoleSeeder::class,
            CeintureSeeder::class,
            PermissionsSeeder::class, // Nouveau seeder complet
        ]);
    }
}
