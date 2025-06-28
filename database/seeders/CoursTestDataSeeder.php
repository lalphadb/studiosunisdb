<?php

namespace Database\Seeders;

use App\Models\Cours;
use App\Models\Ecole;
use App\Models\InscriptionCours;
use App\Models\Membre;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CoursTestDataSeeder extends Seeder
{
    public function run()
    {
        // Récupérer les écoles et instructeurs existants
        $ecoles = Ecole::take(3)->get();
        $instructeurs = User::role('instructeur')->get();

        if ($ecoles->isEmpty()) {
            $this->command->warn('Aucune école trouvée. Créez d\'abord des écoles.');

            return;
        }

        // Cours réalistes pour Studios Unis (karaté)
        $coursData = [
            [
                'nom' => 'Karaté Enfants 6-9 ans',
                'description' => 'Initiation au karaté pour les plus jeunes. Développement de la coordination, discipline et confiance en soi.',
                'niveau_requis' => 'Débutant',
                'age_min' => 6,
                'age_max' => 9,
                'duree_minutes' => 45,
                'capacite_max' => 12,
                'prix_mensuel' => 75.00,
                'type_cours' => 'enfants',
                'jour_semaine' => 'mercredi',
                'heure_debut' => '16:00',
                'heure_fin' => '16:45',
            ],
            [
                'nom' => 'Karaté Jeunes 10-14 ans',
                'description' => 'Apprentissage des techniques de base et premières formes. Préparation aux passages de grades.',
                'niveau_requis' => 'Débutant/Intermédiaire',
                'age_min' => 10,
                'age_max' => 14,
                'duree_minutes' => 60,
                'capacite_max' => 15,
                'prix_mensuel' => 85.00,
                'type_cours' => 'enfants',
                'jour_semaine' => 'mercredi',
                'heure_debut' => '17:00',
                'heure_fin' => '18:00',
            ],
            [
                'nom' => 'Karaté Adultes Débutants',
                'description' => 'Cours pour adultes n\'ayant jamais pratiqué le karaté. Apprentissage des bases dans un environnement bienveillant.',
                'niveau_requis' => 'Débutant',
                'age_min' => 16,
                'age_max' => 99,
                'duree_minutes' => 60,
                'capacite_max' => 18,
                'prix_mensuel' => 95.00,
                'type_cours' => 'adultes',
                'jour_semaine' => 'mardi',
                'heure_debut' => '19:00',
                'heure_fin' => '20:00',
            ],
            [
                'nom' => 'Karaté Traditionnel',
                'description' => 'Cours axé sur la tradition et la philosophie du karaté. Katas, bunkai et applications.',
                'niveau_requis' => 'Intermédiaire/Avancé',
                'age_min' => 14,
                'age_max' => 99,
                'duree_minutes' => 75,
                'capacite_max' => 20,
                'prix_mensuel' => 110.00,
                'type_cours' => 'karate',
                'jour_semaine' => 'jeudi',
                'heure_debut' => '19:30',
                'heure_fin' => '20:45',
            ],
            [
                'nom' => 'Cours du Samedi Matin',
                'description' => 'Cours mixte tous niveaux pour ceux qui préfèrent s\'entraîner le weekend. Ambiance détendue.',
                'niveau_requis' => 'Tous niveaux',
                'age_min' => 12,
                'age_max' => 99,
                'duree_minutes' => 90,
                'capacite_max' => 25,
                'prix_mensuel' => 100.00,
                'type_cours' => 'karate',
                'jour_semaine' => 'samedi',
                'heure_debut' => '10:00',
                'heure_fin' => '11:30',
            ],
        ];

        foreach ($ecoles as $ecole) {
            // Créer 3 cours par école
            $coursSelectionnes = collect($coursData)->random(3);

            foreach ($coursSelectionnes as $data) {
                $cours = Cours::create([
                    'ecole_id' => $ecole->id,
                    'instructeur_id' => $instructeurs->isNotEmpty() ? $instructeurs->random()->id : null,
                    'nom' => $data['nom'],
                    'description' => $data['description'],
                    'niveau_requis' => $data['niveau_requis'],
                    'age_min' => $data['age_min'],
                    'age_max' => $data['age_max'],
                    'duree_minutes' => $data['duree_minutes'],
                    'capacite_max' => $data['capacite_max'],
                    'prix_mensuel' => $data['prix_mensuel'],
                    'type_cours' => $data['type_cours'],
                    'jour_semaine' => $data['jour_semaine'],
                    'heure_debut' => $data['heure_debut'],
                    'heure_fin' => $data['heure_fin'],
                    'date_debut' => Carbon::now()->startOfMonth(),
                    'date_fin' => Carbon::now()->addMonths(6),
                    'status' => 'actif',
                ]);

                // Inscrire quelques membres (avec les bonnes valeurs de status)
                $membres = Membre::where('ecole_id', $ecole->id)
                    ->where('statut', 'actif')
                    ->inRandomOrder()
                    ->take(rand(3, min(8, $cours->capacite_max - 2)))
                    ->get();

                foreach ($membres as $membre) {
                    InscriptionCours::create([
                        'cours_id' => $cours->id,
                        'membre_id' => $membre->id,
                        'date_inscription' => Carbon::now()->subDays(rand(1, 60)),
                        'status' => 'active', // Valeur correcte selon la structure
                        'montant_paye' => $cours->prix_mensuel,
                        'date_debut_facturation' => Carbon::now()->startOfMonth(),
                        'date_fin_facturation' => Carbon::now()->endOfMonth(),
                    ]);
                }
            }
        }

        $this->command->info('✅ Cours et inscriptions créés avec succès !');
        $this->command->info('📊 '.Cours::count().' cours avec inscriptions');
    }
}
