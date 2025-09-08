<?php

namespace Database\Seeders;

use App\Models\Cours;
use App\Models\User;
use Illuminate\Database\Seeder;

class CoursSeeder extends Seeder
{
    /**
     * Cours de test pour StudiosDB
     */
    public function run(): void
    {
        // Récupérer un utilisateur existant pour être l'instructeur
        $instructeur = User::where('email', 'superadmin@test.com')->first();

        if (! $instructeur) {
            // Si pas de superadmin, prendre le premier utilisateur
            $instructeur = User::first();
        }

        if (! $instructeur) {
            $this->command->error('❌ Aucun utilisateur trouvé. Veuillez créer un utilisateur d\'abord.');

            return;
        }

        $ecoleId = $instructeur->ecole_id ?? 1;

        $cours = [
            [
                'nom' => 'Karaté Enfants 6-10 ans',
                'description' => 'Cours adapté aux enfants de 6 à 10 ans. Apprentissage des bases du karaté de manière ludique.',
                'niveau' => 'debutant',
                'type_cours' => 'regulier',
                'places_max' => 15,
                'type_tarif' => 'mensuel',
                'montant' => 45.00,
                'tarif_mensuel' => 45.00,
                'jour_semaine' => 'lundi',
                'heure_debut' => '17:00',
                'heure_fin' => '18:00',
                'age_min' => 6,
                'age_max' => 10,
                'instructeur_id' => $instructeur->id,
                'ecole_id' => $ecoleId,
                'actif' => true,
                'notes' => 'Cours pour enfants débutants - Focus sur le plaisir et les bases',
            ],
            [
                'nom' => 'Karaté Enfants 11-14 ans',
                'description' => 'Cours pour adolescents. Développement des techniques et préparation aux compétitions.',
                'niveau' => 'intermediaire',
                'type_cours' => 'regulier',
                'places_max' => 12,
                'type_tarif' => 'mensuel',
                'montant' => 55.00,
                'tarif_mensuel' => 55.00,
                'jour_semaine' => 'mercredi',
                'heure_debut' => '18:30',
                'heure_fin' => '19:45',
                'age_min' => 11,
                'age_max' => 14,
                'instructeur_id' => $instructeur->id,
                'ecole_id' => $ecoleId,
                'actif' => true,
                'notes' => 'Cours adolescents - Techniques intermédiaires et kumite de base',
            ],
            [
                'nom' => 'Karaté Adultes Débutants',
                'description' => 'Cours pour adultes souhaitant découvrir le karaté. Rythme adapté aux débutants.',
                'niveau' => 'debutant',
                'type_cours' => 'regulier',
                'places_max' => 20,
                'type_tarif' => 'mensuel',
                'montant' => 65.00,
                'tarif_mensuel' => 65.00,
                'jour_semaine' => 'mardi',
                'heure_debut' => '19:00',
                'heure_fin' => '20:30',
                'age_min' => 15,
                'age_max' => null,
                'instructeur_id' => $instructeur->id,
                'ecole_id' => $ecoleId,
                'actif' => true,
                'notes' => 'Adultes débutants - Rythme adapté, focus sur la condition physique',
            ],
            [
                'nom' => 'Karaté Adultes Avancés',
                'description' => 'Cours technique pour pratiquants expérimentés. Kata et kumite avancés.',
                'niveau' => 'avance',
                'type_cours' => 'regulier',
                'places_max' => 15,
                'type_tarif' => 'mensuel',
                'montant' => 75.00,
                'tarif_mensuel' => 75.00,
                'jour_semaine' => 'jeudi',
                'heure_debut' => '20:00',
                'heure_fin' => '21:30',
                'age_min' => 18,
                'age_max' => null,
                'instructeur_id' => $instructeur->id,
                'ecole_id' => $ecoleId,
                'actif' => true,
                'notes' => 'Adultes avancés - Kata complexes et kumite compétition',
            ],
            [
                'nom' => 'Préparation Compétition',
                'description' => 'Entraînement intensif pour les compétiteurs. Techniques de combat et stratégie.',
                'niveau' => 'competiteur',
                'type_cours' => 'special',
                'places_max' => 10,
                'type_tarif' => 'mensuel',
                'montant' => 85.00,
                'tarif_mensuel' => 85.00,
                'jour_semaine' => 'samedi',
                'heure_debut' => '10:00',
                'heure_fin' => '12:00',
                'age_min' => 12,
                'age_max' => null,
                'instructeur_id' => $instructeur->id,
                'ecole_id' => $ecoleId,
                'actif' => true,
                'notes' => 'Préparation compétition - Entraînement intensif pour compétiteurs',
            ],
            [
                'nom' => 'Karaté Seniors',
                'description' => 'Cours adapté aux personnes âgées. Focus sur la santé, l\'équilibre et la relaxation.',
                'niveau' => 'tous',
                'type_cours' => 'regulier',
                'places_max' => 12,
                'type_tarif' => 'mensuel',
                'montant' => 50.00,
                'tarif_mensuel' => 50.00,
                'jour_semaine' => 'vendredi',
                'heure_debut' => '10:00',
                'heure_fin' => '11:00',
                'age_min' => 55,
                'age_max' => null,
                'instructeur_id' => $instructeur->id,
                'ecole_id' => $ecoleId,
                'actif' => true,
                'notes' => 'Cours seniors - Focus santé, équilibre et relaxation',
            ],
        ];

        foreach ($cours as $data) {
            Cours::firstOrCreate([
                'nom' => $data['nom'],
                'jour_semaine' => $data['jour_semaine'],
                'heure_debut' => $data['heure_debut'],
                'heure_fin' => $data['heure_fin'],
            ], $data);
        }

        $this->command->info('✅ '.count($cours).' cours de test créés !');
    }
}
