<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@studiosdb.com'],
            [
                'name' => 'Administrateur',
                'email' => 'admin@studiosdb.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );

        // Utilisateur de test pour le développement
        User::firstOrCreate(
            ['email' => 'test@studiosdb.com'],
            [
                'name' => 'Utilisateur Test',
                'email' => 'test@studiosdb.com',
                'password' => Hash::make('test123'),
                'email_verified_at' => now(),
            ]
        );
    }
}
