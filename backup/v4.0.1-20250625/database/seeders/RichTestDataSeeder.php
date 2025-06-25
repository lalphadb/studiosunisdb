<?php

namespace Database\Seeders;

use App\Models\Ceinture;
use App\Models\Ecole;
use App\Models\Membre;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RichTestDataSeeder extends Seeder
{
    public function run()
    {
        // Créer des utilisateurs admin pour différentes écoles
        $adminMontreal = User::create([
            'name' => 'Admin Montréal',
            'email' => 'admin.montreal@studiosunisdb.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'ecole_id' => 1, // Première école
        ]);
        $adminMontreal->assignRole('admin');

        $adminQuebec = User::create([
            'name' => 'Admin Québec',
            'email' => 'admin.quebec@studiosunisdb.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'ecole_id' => 2, // Deuxième école
        ]);
        $adminQuebec->assignRole('admin');

        // Créer des instructeurs
        $instructeur1 = User::create([
            'name' => 'Sensei Yamamoto',
            'email' => 'yamamoto@studiosunisdb.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'ecole_id' => 1,
        ]);
        $instructeur1->assignRole('instructeur');

        // Créer 30 membres répartis dans les écoles
        $ecoles = Ecole::take(5)->get();
        $prenoms = ['Alexandre', 'Marie', 'Jean', 'Sophie', 'Pierre', 'Julie', 'David', 'Isabelle', 'Martin', 'Catherine', 'François', 'Annie', 'Robert', 'Diane', 'Michel'];
        $noms = ['Tremblay', 'Gagnon', 'Roy', 'Côté', 'Bouchard', 'Gauthier', 'Morin', 'Lavoie', 'Fortin', 'Gagné', 'Ouellet', 'Pelletier', 'Bélanger', 'Lévesque', 'Bergeron'];

        for ($i = 0; $i < 30; $i++) {
            $ecole = $ecoles->random();
            $prenom = $prenoms[array_rand($prenoms)];
            $nom = $noms[array_rand($noms)];

            Membre::create([
                'ecole_id' => $ecole->id,
                'prenom' => $prenom,
                'nom' => $nom,
                'date_naissance' => Carbon::now()->subYears(rand(8, 45))->subDays(rand(1, 365)),
                'telephone' => '514-'.rand(100, 999).'-'.rand(1000, 9999),
                'email' => strtolower($prenom.'.'.$nom.'@email.com'),
                'adresse' => rand(100, 9999).' Rue '.['Principale', 'Saint-Jean', 'Notre-Dame', 'Sainte-Catherine', 'Saint-Laurent'][array_rand(['Principale', 'Saint-Jean', 'Notre-Dame', 'Sainte-Catherine', 'Saint-Laurent'])],
                'contact_urgence' => $prenoms[array_rand($prenoms)].' '.$noms[array_rand($noms)],
                'telephone_urgence' => '438-'.rand(100, 999).'-'.rand(1000, 9999),
                'date_inscription' => Carbon::now()->subDays(rand(1, 730)),
                'statut' => ['actif', 'actif', 'actif', 'actif', 'inactif'][array_rand(['actif', 'actif', 'actif', 'actif', 'inactif'])],
                'notes' => rand(1, 100) > 70 ? 'Étudiant motivé, bons progrès' : null,
            ]);
        }

        // Créer les ceintures de karaté
        $ceintures = [
            ['nom' => 'Ceinture Blanche', 'couleur' => 'blanc', 'ordre_affichage' => 1, 'description' => 'Niveau débutant'],
            ['nom' => 'Ceinture Jaune', 'couleur' => 'jaune', 'ordre_affichage' => 2, 'description' => 'Premier niveau coloré'],
            ['nom' => 'Ceinture Orange', 'couleur' => 'orange', 'ordre_affichage' => 3, 'description' => 'Progression intermédiaire'],
            ['nom' => 'Ceinture Verte', 'couleur' => 'verte', 'ordre_affichage' => 4, 'description' => 'Niveau intermédiaire'],
            ['nom' => 'Ceinture Bleue', 'couleur' => 'bleue', 'ordre_affichage' => 5, 'description' => 'Niveau avancé'],
            ['nom' => 'Ceinture Marron', 'couleur' => 'marron', 'ordre_affichage' => 6, 'description' => 'Pré-expert'],
            ['nom' => 'Ceinture Noire 1er Dan', 'couleur' => 'noire', 'ordre_affichage' => 7, 'description' => 'Expert niveau 1'],
            ['nom' => 'Ceinture Noire 2e Dan', 'couleur' => 'noire', 'ordre_affichage' => 8, 'description' => 'Expert niveau 2'],
        ];

        foreach ($ceintures as $ceinture) {
            Ceinture::create($ceinture);
        }

        $this->command->info('✅ Données de test riches créées avec succès !');
        $this->command->info('📊 30 membres, 8 ceintures, 3 utilisateurs admin/instructeur');
    }
}
