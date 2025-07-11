<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Ecole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUsersSeeder extends Seeder
{
    public function run(): void
    {
        // Récupérer quelques écoles pour créer des admins
        $ecoleQuebec = Ecole::where('nom', 'like', '%Québec%')->first();
        $ecoleLevis = Ecole::where('nom', 'like', '%Lévis%')->first();
        $ecoleBeauport = Ecole::where('nom', 'like', '%Beauport%')->first();
        
        // Admin École pour Québec
        if ($ecoleQuebec) {
            $admin1 = User::firstOrCreate(
                ['email' => 'louis@4lb.ca'],
                [
                    'name' => 'Louis Admin',
                    'password' => Hash::make(env('TEST_ADMIN_PASSWORD', 'password123')),
                    'ecole_id' => $ecoleQuebec->id,
                    'active' => true,
                    'email_verified_at' => now(),
                ]
            );
            $admin1->assignRole('admin_ecole');
            $this->command->info('✓ Admin École créé pour ' . $ecoleQuebec->nom . ': ' . $admin1->email);
        }
        
        // Instructeur pour Lévis
        if ($ecoleLevis) {
            $instructeur = User::firstOrCreate(
                ['email' => 'instructeur.levis@studiosunisdb.com'],
                [
                    'name' => 'Jean-François Instructeur',
                    'password' => Hash::make('password123'),
                    'ecole_id' => $ecoleLevis->id,
                    'active' => true,
                    'email_verified_at' => now(),
                ]
            );
            $instructeur->assignRole('instructeur');
            $this->command->info('✓ Instructeur créé pour ' . $ecoleLevis->nom);
        }
        
        // Membres tests pour Beauport
        if ($ecoleBeauport) {
            $membre = User::firstOrCreate(
                ['email' => 'membre.beauport@studiosunisdb.com'],
                [
                    'name' => 'Marie Membre',
                    'password' => Hash::make('password123'),
                    'ecole_id' => $ecoleBeauport->id,
                    'active' => true,
                    'email_verified_at' => now(),
                ]
            );
            $membre->assignRole('membre');
            $this->command->info('✓ Membre test créé pour ' . $ecoleBeauport->nom);
        }
        
        $this->command->info('✅ Utilisateurs de test créés');
    }
}
