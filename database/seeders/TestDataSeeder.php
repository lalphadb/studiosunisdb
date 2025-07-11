<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Ecole;
use App\Models\Cours;
use App\Models\Presence;
use App\Models\Paiement;
use App\Models\SessionCours;
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
        
        $codeCounter = 10; // Commencer à 10 pour éviter les conflits
        
        foreach ($ecoles->take(3) as $ecole) { // Limiter à 3 écoles pour les tests
            $this->command->info("Création des données de test pour: {$ecole->nom}");
            
            // Créer 5 membres par école
            for ($i = 1; $i <= 5; $i++) {
                $user = User::create([
                    'nom' => "Membre{$i}",
                    'prenom' => $ecole->code,
                    'email' => "membre{$i}.{$ecole->code}@test.com",
                    'password' => Hash::make('password123'),
                    'ecole_id' => $ecole->id,
                    'telephone' => '418-555-' . str_pad($codeCounter++, 4, '0', STR_PAD_LEFT),
                    'actif' => true,
                    'email_verified_at' => now(),
                    'code_utilisateur' => 'U' . str_pad($codeCounter, 6, '0', STR_PAD_LEFT),
                ]);
                
                $user->assignRole('membre');
            }
            
            // Créer 2 instructeurs
            for ($i = 1; $i <= 2; $i++) {
                $user = User::create([
                    'nom' => "Instructeur{$i}",
                    'prenom' => $ecole->code,
                    'email' => "instructeur{$i}.{$ecole->code}@test.com",
                    'password' => Hash::make('password123'),
                    'ecole_id' => $ecole->id,
                    'telephone' => '418-666-' . str_pad($codeCounter++, 4, '0', STR_PAD_LEFT),
                    'actif' => true,
                    'email_verified_at' => now(),
                    'code_utilisateur' => 'I' . str_pad($codeCounter, 6, '0', STR_PAD_LEFT),
                ]);
                
                $user->assignRole('instructeur');
            }
            
            // Créer 3 cours
            $instructeur = $ecole->users()->role('instructeur')->first();
            
            if ($instructeur) {
                $cours1 = Cours::create([
                    'nom' => 'Karaté Débutant',
                    'code' => 'KAR-DEB-' . $ecole->code,
                    'description' => 'Cours pour débutants',
                    'ecole_id' => $ecole->id,
                    'type' => 'regulier',
                    'niveau' => 'debutant',
                    'duree_minutes' => 60,
                    'capacite_max' => 20,
                    'prix_mensuel' => 80.00,
                    'actif' => true,
                ]);
                
                $cours2 = Cours::create([
                    'nom' => 'Karaté Avancé',
                    'code' => 'KAR-AVA-' . $ecole->code,
                    'description' => 'Cours pour avancés',
                    'ecole_id' => $ecole->id,
                    'type' => 'regulier',
                    'niveau' => 'avance',
                    'duree_minutes' => 90,
                    'capacite_max' => 15,
                    'prix_mensuel' => 100.00,
                    'actif' => true,
                ]);
                
                // Créer une session aujourd'hui
                $session = SessionCours::create([
                    'cours_id' => $cours1->id,
                    'ecole_id' => $ecole->id,
                    'date_debut' => Carbon::today()->setHour(18)->setMinute(0),
                    'date_fin' => Carbon::today()->setHour(19)->setMinute(0),
                    'instructeur_id' => $instructeur->id,
                    'salle' => 'Dojo Principal',
                    'capacite_max' => $cours1->capacite_max,
                    'statut' => 'planifie',
                ]);
                
                // Créer des présences pour la session
                $membres = $ecole->users()->role('membre')->get();
                
                foreach ($membres->take(3) as $membre) {
                    Presence::create([
                        'session_cours_id' => $session->id,
                        'user_id' => $membre->id,
                        'ecole_id' => $ecole->id,
                        'status' => 'present',
                        'heure_arrivee' => Carbon::today()->setHour(17)->setMinute(55),
                    ]);
                }
                
                // Créer des paiements
                foreach ($membres as $membre) {
                    Paiement::create([
                        'user_id' => $membre->id,
                        'ecole_id' => $ecole->id,
                        'montant' => 80.00,
                        'date_paiement' => Carbon::now(),
                        'type_paiement' => 'mensualite',
                        'methode_paiement' => 'carte',
                        'statut' => 'complete',
                        'reference_paiement' => 'PAY-' . strtoupper(uniqid()),
                        'description' => 'Paiement mensuel - ' . Carbon::now()->format('F Y'),
                    ]);
                }
            }
        }
        
        $this->command->info('✅ Données de test créées avec succès!');
    }
}
