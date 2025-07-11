<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Ecole;
use App\Models\Cours;
use App\Models\Presence;
use App\Models\Paiement;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        $ecoles = Ecole::all();
        
        if ($ecoles->isEmpty()) {
            $this->command->error('Aucune école trouvée. Exécutez d\'abord EcolesSeeder.');
            return;
        }
        
        foreach ($ecoles->take(3) as $ecole) { // Limiter à 3 écoles pour les tests
            // Créer 5 membres par école
            for ($i = 1; $i <= 5; $i++) {
                $user = User::create([
                    'name' => "Membre {$i} - {$ecole->code}",
                    'email' => "membre{$i}.{$ecole->code}@test.com",
                    'password' => Hash::make('password123'),
                    'ecole_id' => $ecole->id,
                    'telephone' => '418-555-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]);
                
                $user->assignRole('membre');
            }
            
            // Créer 2 instructeurs
            for ($i = 1; $i <= 2; $i++) {
                $user = User::create([
                    'name' => "Instructeur {$i} - {$ecole->code}",
                    'email' => "instructeur{$i}.{$ecole->code}@test.com",
                    'password' => Hash::make('password123'),
                    'ecole_id' => $ecole->id,
                    'telephone' => '418-666-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]);
                
                $user->assignRole('instructeur');
            }
            
            // Créer 3 cours
            $instructeur = $ecole->users()->role('instructeur')->first();
            
            Cours::create([
                'nom' => 'Karaté Débutant',
                'description' => 'Cours pour débutants',
                'ecole_id' => $ecole->id,
                'instructeur_id' => $instructeur->id,
                'jour_semaine' => 1, // Lundi
                'heure_debut' => '18:00:00',
                'heure_fin' => '19:00:00',
                'capacite_max' => 20,
                'is_active' => true,
            ]);
            
            Cours::create([
                'nom' => 'Karaté Avancé',
                'description' => 'Cours pour avancés',
                'ecole_id' => $ecole->id,
                'instructeur_id' => $instructeur->id,
                'jour_semaine' => 3, // Mercredi
                'heure_debut' => '19:00:00',
                'heure_fin' => '20:30:00',
                'capacite_max' => 15,
                'is_active' => true,
            ]);
            
            // Créer des présences pour aujourd'hui
            $membres = $ecole->users()->role('membre')->get();
            $cours = $ecole->cours()->first();
            
            foreach ($membres->take(3) as $membre) {
                Presence::create([
                    'user_id' => $membre->id,
                    'cours_id' => $cours->id,
                    'date_presence' => Carbon::today(),
                    'statut' => 'present',
                ]);
            }
            
            // Créer des paiements
            foreach ($membres as $membre) {
                Paiement::create([
                    'user_id' => $membre->id,
                    'montant' => 100.00,
                    'type_paiement' => 'mensuel',
                    'methode_paiement' => 'carte',
                    'statut' => 'complete',
                    'date_paiement' => Carbon::now(),
                    'reference' => 'PAY-' . strtoupper(uniqid()),
                ]);
            }
        }
        
        $this->command->info('✅ Données de test créées !');
    }
}
