<?php
namespace Database\Seeders;

use App\Models\User;
use App\Models\Ecole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Alpha - Superadmin
        User::firstOrCreate(['email' => 'lalpha@4lb.ca'], [
            'name' => 'Alpha Admin',
            'email' => 'lalpha@4lb.ca',
            'password' => Hash::make('password123'),
            'role' => 'superadmin',
            'email_verified_at' => now(),
        ]);

        // Louis - École St-Émile
        $ecoleStEmile = Ecole::where('nom', 'École St-Émile')->first();
        if ($ecoleStEmile) {
            User::firstOrCreate(['email' => 'louis@4lb.ca'], [
                'name' => 'Louis - École St-Émile',
                'email' => 'louis@4lb.ca',
                'password' => Hash::make('password123'),
                'role' => 'admin_ecole',
                'ecole_id' => $ecoleStEmile->id,
                'email_verified_at' => now(),
            ]);
        }
        
        $this->command->info('✅ Utilisateurs créés!');
    }
}
