<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Ecole;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class FixLouisPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Trouver ou créer l'école Saint-Émile
        $ecole = Ecole::firstOrCreate(
            ['nom' => 'Club de Karaté Saint-Émile'],
            [
                'code' => 'STE',
                'adresse' => '1234 rue Principale',
                'ville' => 'Saint-Émile',
                'province' => 'QC',
                'code_postal' => 'G0A 4E0',
                'telephone' => '418-XXX-XXXX',
                'email' => 'stemile@studiosunis.com',
                'active' => true,
            ]
        );

        // Créer/mettre à jour Louis avec le bon mot de passe
        $user = User::updateOrCreate(
            ['email' => 'louis@4lb.ca'],
            [
                'name' => 'Louis Admin Saint-Émile',
                'password' => Hash::make('password123'),  // MOT DE PASSE CORRECT
                'ecole_id' => $ecole->id,
                'active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Assigner le rôle admin_ecole
        $user->syncRoles(['admin_ecole']);

        $this->command->info("✅ Louis@4lb.ca configuré");
        $this->command->info("🏫 École: {$ecole->nom}");
        $this->command->info("🔑 Mot de passe: password123");
        $this->command->info("🎯 Permissions: " . $user->getAllPermissions()->count());
    }
}
