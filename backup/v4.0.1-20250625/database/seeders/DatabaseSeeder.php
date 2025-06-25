<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PermissionsSeeder::class,
        ]);
    }
}
        // Permissions pour Louis admin Saint-Émile
        $this->call(LouisAdminSeeder::class);
