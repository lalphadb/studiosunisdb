<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Ecole;
use Illuminate\Support\Facades\Hash;

class LouisAdminSeeder extends Seeder
{
    public function run(): void
    {
        // Trouver l'école Saint-Émile
        $ecole = Ecole::where('nom', 'LIKE', '%Saint-Émile%')
                     ->orWhere('nom', 'LIKE', '%St-Émile%')
                     ->orWhere('code', 'LIKE', '%EMILE%')
                     ->first();

        if (!$ecole) {
            // Créer l'école si elle n'existe pas
            $ecole = Ecole::create([
                'nom' => 'Club de Karaté Saint-Émile',
                'code' => 'STE',
                'adresse' => '1234 rue Principale',
                'ville' => 'Saint-Émile',
                'province' => 'QC',
                'code_postal' => 'G0A 4E0',
                'telephone' => '418-XXX-XXXX',
                'email' => 'stemile@studiosunis.com',
                'active' => true,
            ]);
            
            $this->command->info("École Saint-Émile créée avec ID: {$ecole->id}");
        }

        // Créer ou mettre à jour l'utilisateur louis@4lb.ca
        $user = User::updateOrCreate(
            ['email' => 'louis@4lb.ca'],
            [
                'name' => 'Louis Admin Saint-Émile',
                'password' => Hash::make('StEmile2025!'),
                'ecole_id' => $ecole->id,
                'active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Assigner le rôle admin_ecole
        $user->syncRoles(['admin_ecole']);

        $this->command->info("✅ Louis@4lb.ca configuré comme admin_ecole pour {$ecole->nom}");
        $this->command->info("🔑 Mot de passe temporaire: StEmile2025!");
    }
}
