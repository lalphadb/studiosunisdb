<?php

namespace Database\Seeders;

use App\Models\Belt;
use App\Models\School;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class InitStudiosDBSeeder extends Seeder
{
    public function run(): void
    {
        // École
        $ecole = School::firstOrCreate(
            ['slug' => 'studios-unis-st-emile'],
            ['nom' => 'Studios Unis St‑Émile', 'courriel' => 'info@studiosunis.local']
        );

        // Rôles
        foreach (['superadmin','admin_ecole','instructeur','membre'] as $role) {
            Role::findOrCreate($role);
        }

        // Ceintures de base
        $belts = [
            ['rang'=>1,'nom'=>'Blanche','couleur'=>'white'],
            ['rang'=>2,'nom'=>'Jaune','couleur'=>'yellow'],
            ['rang'=>3,'nom'=>'Orange','couleur'=>'orange'],
            ['rang'=>4,'nom'=>'Verte','couleur'=>'green'],
            ['rang'=>5,'nom'=>'Bleue','couleur'=>'blue'],
            ['rang'=>6,'nom'=>'Marron','couleur'=>'brown'],
            ['rang'=>7,'nom'=>'Noire','couleur'=>'black'],
        ];
        foreach ($belts as $b) {
            Belt::updateOrCreate(['rang'=>$b['rang']], $b);
        }

        // Superadmin (DEV)
        if (!User::where('email','admin@studiosunis.local')->exists()) {
            $admin = User::create([
                'name' => 'Super Admin',
                'email' => 'admin@studiosunis.local',
                'password' => Hash::make('password'),
                'ecole_id' => $ecole->id,
                'statut' => 'actif',
            ]);
            $admin->assignRole('superadmin');
        }
    }
}
