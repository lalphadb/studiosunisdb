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
            ['email' => 'logan@4lb.ca'],
            [
                'name' => 'Administrateur',
                'email' => 'logan@4lb.ca',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );

        // Utilisateur de test pour le développement
        User::firstOrCreate(
            ['email' => 'lalpha@4lb.ca'],
            [
                'name' => 'lalpha membre',
                'email' => 'lalpha@4lb.ca',
                'password' => Hash::make('test123'),
                'email_verified_at' => now(),
            ]
        );
    }
}
