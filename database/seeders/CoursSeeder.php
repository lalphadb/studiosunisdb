cat > /home/studiosdb/studiosunisdb/database/seeders/CoursSeeder.php << 'EOH'
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cours;
use App\Models\User;
use App\Models\Membre;
use Carbon\Carbon;

class CoursSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer ou créer des instructeurs
        $instructeurs = User::role('instructeur')->get();
        
        if ($instructeurs->isEmpty()) {
            // Créer des instructeurs de test
            $instructeur1 = User::create([
                'name' => 'Sensei Yamamoto',
                'email' => 'yamamoto@studiosdb.local',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]);
            $instructeur1->assignRole('instructeur');
            
            $instructeur2 = User::create([
                'name' => 'Sensei Tanaka',
                'email' => 'tanaka@studiosdb.local',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]);
            $instructeur2->assignRole('instructeur');
            
            $instructeur3 = User::create([
                'name' => 'Sensei Nakamura',
                'email' => 'nakamura@studiosdb.local',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]);
            $instructeur3->assignRole('instructeur');
            
            $instructeurs = collect([$instructeur1, $instructeur2, $instructeur3]);
        }

        // Définir les cours types pour une école de karaté
        $coursData = [
            // Cours pour enfants
            [
                'nom' => 'Karaté Éveil (4-6 ans)',
                'description' => 'Initiation au karaté pour les tout-petits. Développement de la motricité, discipline et respect.',
                'niveau' => 'debutant',
                'age_min' => 4,
                'age_max' => 6,
                'places_max' => 12,
                'jour_semaine' => 'samedi',
                'heure_debut' => '09:00',
                'heure_fin' => '09:45',
                'tarif_mensuel' => 45.00,
                'type_cours' => 'Éveil',
                'couleur' => '#10b981',
            ],
            [
                'nom' => 'Karaté Débutant Enfants (7-9 ans)',
                'description' => 'Cours de karaté pour enfants débutants. Apprentissage des bases et premiers katas.',
                'niveau' => 'debutant',
                'age_min' => 7,
                'age_max' => 9,
                'places_max' => 16,
                'jour_semaine' => 'mercredi',
                'heure_debut' => '17:00',
                'heure_fin' => '18:00',
                'tarif_mensuel' => 55.00,
                'type_cours' => 'Technique',
                'couleur' => '#22c55e',
            ],
            [
                'nom' => 'Karaté Débutant Enfants (7-9 ans) - Samedi',
                'description' => 'Cours de karaté pour enfants débutants. Session du samedi.',
                'niveau' => 'debutant',
                'age_min' => 7,
                'age_max' => 9,
                'places_max' => 16,
                'jour_semaine' => 'samedi',
                'heure_debut' => '10:00',
                'heure_fin' => '11:00',
                'tarif_mensuel' => 55.00,
                'type_cours' => 'Technique',
                'couleur' => '#22c55e',
            ],

            // Cours pour pré-ados
            [
                'nom' => 'Karaté Intermédiaire (10-12 ans)',
                'description' => 'Perfectionnement des techniques, katas avancés et introduction au kumite.',
                'niveau' => 'intermediaire',
                'age_min' => 10,
                'age_max' => 12,
                'places_max' => 18,
                'jour_semaine' => 'mardi',
                'heure_debut' => '18:00',
                'heure_fin' => '19:15',
                'tarif_mensuel' => 60.00,
                'type_cours' => 'Technique',
                'couleur' => '#3b82f6',
                'prerequis' => 'Ceinture jaune',
            ],
            [
                'nom' => 'Karaté Intermédiaire (10-12 ans) - Jeudi',
                'description' => 'Perfectionnement des techniques. Session du jeudi.',
                'niveau' => 'intermediaire',
                'age_min' => 10,
                'age_max' => 12,
                'places_max' => 18,
                'jour_semaine' => 'jeudi',
                'heure_debut' => '18:00',
                'heure_fin' => '19:15',
                'tarif_mensuel' => 60.00,
                'type_cours' => 'Technique',
                'couleur' => '#3b82f6',
                'prerequis' => 'Ceinture jaune',
            ],

            // Cours pour adolescents
            [
                'nom' => 'Karaté Ados (13-17 ans)',
                'description' => 'Cours complet pour adolescents. Technique, kata, kumite et préparation physique.',
                'niveau' => 'intermediaire',
                'age_min' => 13,
                'age_max' => 17,
                'places_max' => 20,
                'jour_semaine' => 'lundi',
                'heure_debut' => '19:00',
                'heure_fin' => '20:30',
                'tarif_mensuel' => 65.00,
                'type_cours' => 'Mixte',
                'couleur' => '#6366f1',
            ],
            [
                'nom' => 'Karaté Ados Avancé (13-17 ans)',
                'description' => 'Cours avancé pour adolescents ceintures vertes et plus.',
                'niveau' => 'avance',
                'age_min' => 13,
                'age_max' => 17,
                'places_max' => 16,
                'jour_semaine' => 'vendredi',
                'heure_debut' => '18:30',
                'heure_fin' => '20:00',
                'tarif_mensuel' => 70.00,
                'type_cours' => 'Mixte',
                'couleur' => '#8b5cf6',
                'prerequis' => 'Ceinture verte',
            ],

            // Cours pour adultes
            [
                'nom' => 'Karaté Adultes Débutant',
                'description' => 'Initiation au karaté pour adultes. Condition physique et self-défense.',
                'niveau' => 'debutant',
                'age_min' => 18,
                'age_max' => 99,
                'places_max' => 24,
                'jour_semaine' => 'mardi',
                'heure_debut' => '20:00',
                'heure_fin' => '21:30',
                'tarif_mensuel' => 75.00,
                'type_cours' => 'Mixte',
                'couleur' => '#059669',
            ],
            [
                'nom' => 'Karaté Adultes Tous Niveaux',
                'description' => 'Cours pour adultes tous niveaux. Progression adaptée à chacun.',
                'niveau' => 'intermediaire',
                'age_min' => 18,
                'age_max' => 99,
                'places_max' => 24,
                'jour_semaine' => 'jeudi',
                'heure_debut' => '20:00',
                'heure_fin' => '21:30',
                'tarif_mensuel' => 75.00,
                'type_cours' => 'Mixte',
                'couleur' => '#0891b2',
            ],

            // Cours spécialisés
            [
                'nom' => 'Kata Spécialisation',
                'description' => 'Perfectionnement des katas pour compétitions et passages de grades.',
                'niveau' => 'avance',
                'age_min' => 14,
                'age_max' => 99,
                'places_max' => 15,
                'jour_semaine' => 'mercredi',
                'heure_debut' => '19:30',
                'heure_fin' => '21:00',
                'tarif_mensuel' => 80.00,
                'type_cours' => 'Kata',
                'couleur' => '#7c3aed',
                'prerequis' => 'Ceinture bleue',
            ],
            [
                'nom' => 'Kumite Compétition',
                'description' => 'Entraînement intensif au combat pour compétiteurs.',
                'niveau' => 'competition',
                'age_min' => 14,
                'age_max' => 35,
                'places_max' => 12,
                'jour_semaine' => 'vendredi',
                'heure_debut' => '20:00',
                'heure_fin' => '22:00',
                'tarif_mensuel' => 90.00,
                'type_cours' => 'Kumite',
                'couleur' => '#dc2626',
                'prerequis' => 'Ceinture marron',
                'materiel_requis' => ['Protège-dents', 'Gants', 'Protège-tibias', 'Coquille'],
            ],
            [
                'nom' => 'Self-Défense Féminin',
                'description' => 'Cours de self-défense adapté spécifiquement pour les femmes.',
                'niveau' => 'debutant',
                'age_min' => 16,
                'age_max' => 99,
                'places_max' => 16,
                'jour_semaine' => 'samedi',
                'heure_debut' => '11:30',
                'heure_fin' => '13:00',
                'tarif_mensuel' => 70.00,
                'type_cours' => 'Self-défense',
                'couleur' => '#ec4899',
            ],

            // Cours du dimanche
            [
                'nom' => 'Stage Technique Dimanche',
                'description' => 'Stage mensuel de perfectionnement technique. Ouvert à tous les niveaux.',
                'niveau' => 'intermediaire',
                'age_min' => 10,
                'age_max' => 99,
                'places_max' => 30,
                'jour_semaine' => 'dimanche',
                'heure_debut' => '10:00',
                'heure_fin' => '12:00',
                'tarif_mensuel' => 40.00,
                'type_cours' => 'Stage',
                'couleur' => '#f59e0b',
            ],
        ];

        // Créer les cours
        foreach ($coursData as $data) {
            $data['instructeur_id'] = $instructeurs->random()->id;
            $data['date_debut'] = Carbon::now()->startOfMonth();
            $data['date_fin'] = Carbon::now()->addMonths(6)->endOfMonth();
            $data['actif'] = true;
            $data['salle'] = 'Dojo ' . rand(1, 3);
            
            // Convertir materiel_requis en JSON si nécessaire
            if (isset($data['materiel_requis'])) {
                $data['materiel_requis'] = json_encode($data['materiel_requis']);
            }
            
            $cours = Cours::create($data);
            
            // Ajouter quelques membres inscrits (si des membres existent)
            $membres = Membre::inRandomOrder()->limit(rand(5, min(15, $data['places_max'] - 2)))->get();
            
            foreach ($membres as $membre) {
                // Vérifier l'âge du membre
                if ($membre->age >= $data['age_min'] && $membre->age <= $data['age_max']) {
                    $cours->membres()->attach($membre->id, [
                        'date_inscription' => Carbon::now()->subDays(rand(1, 60)),
                        'statut' => 'actif',
                    ]);
                }
            }
        }

        $this->command->info('✅ ' . count($coursData) . ' cours créés avec succès!');
    }
}
EOH