<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class TestUsersSeeder extends Seeder
{
    public function run(): void
    {
        // Admin principal
        User::create([
            'name' => 'Louis Administrateur',
            'email' => 'louis@4lb.ca',
            'password' => Hash::make('StudiosDB2025!'),
            'email_verified_at' => now(),
        ]);

        // Instructeur test
        User::create([
            'name' => 'Sensei Yamamoto',
            'email' => 'instructeur@studiosdb.local',
            'password' => Hash::make('karate2025'),
            'email_verified_at' => now(),
        ]);

        // Membre test
        User::create([
            'name' => 'Élève Débutant',
            'email' => 'eleve@studiosdb.local', 
            'password' => Hash::make('student2025'),
            'email_verified_at' => now(),
        ]);
    }
}
