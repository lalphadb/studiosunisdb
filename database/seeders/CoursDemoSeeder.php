<?php

namespace Database\Seeders;

use App\Models\Cours;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoursDemoSeeder extends Seeder
{
    /**
     * Seed demo courses for testing
     */
    public function run(): void
    {
        // Récupérer première école
        $ecole = DB::table('ecoles')->first();
        if (! $ecole) {
            $this->command->warn('Aucune école trouvée - créer une école d\'abord');

            return;
        }

        // Récupérer instructeurs disponibles (sans global scope pour éviter erreur ecole_id)
        $instructeurs = User::withoutGlobalScopes()->role('instructeur')->get();
        $instructeur = $instructeurs->first();

        $coursDemo = [
            [
                'nom' => 'Karaté Débutants Enfants',
                'description' => 'Initiation au karaté pour les 6-10 ans. Apprentissage des bases: positions, coups de poing, coups de pied.',
                'instructeur_id' => $instructeur?->id,
                'ecole_id' => $ecole->id,
                'niveau' => 'debutant',
                'age_min' => 6,
                'age_max' => 10,
                'places_max' => 15,
                'jour_semaine' => 'mercredi',
                'heure_debut' => '16:00:00',
                'heure_fin' => '17:00:00',
                'date_debut' => '2025-09-01',
                'date_fin' => '2025-12-20',
                'tarif_mensuel' => 45.00,
                'actif' => true,
                'couleur_calendrier' => '#10b981',
                'salle' => 'Dojo Principal',
            ],
            [
                'nom' => 'Karaté Intermédiaire Ados',
                'description' => 'Perfectionnement technique et préparation aux grades supérieurs pour adolescents.',
                'instructeur_id' => $instructeur?->id,
                'ecole_id' => $ecole->id,
                'niveau' => 'intermediaire',
                'age_min' => 11,
                'age_max' => 17,
                'places_max' => 12,
                'jour_semaine' => 'vendredi',
                'heure_debut' => '18:00:00',
                'heure_fin' => '19:30:00',
                'date_debut' => '2025-09-01',
                'date_fin' => '2025-12-20',
                'tarif_mensuel' => 55.00,
                'actif' => true,
                'couleur_calendrier' => '#3b82f6',
                'salle' => 'Dojo Principal',
            ],
            [
                'nom' => 'Karaté Avancé Adultes',
                'description' => 'Cours technique avancé: kumité, katas supérieurs, préparation compétitions.',
                'instructeur_id' => $instructeur?->id,
                'ecole_id' => $ecole->id,
                'niveau' => 'avance',
                'age_min' => 18,
                'age_max' => 65,
                'places_max' => 10,
                'jour_semaine' => 'mardi',
                'heure_debut' => '19:30:00',
                'heure_fin' => '21:00:00',
                'date_debut' => '2025-09-01',
                'date_fin' => '2025-12-20',
                'tarif_mensuel' => 65.00,
                'actif' => true,
                'couleur_calendrier' => '#8b5cf6',
                'salle' => 'Dojo Principal',
            ],
            [
                'nom' => 'Éveil Martial (Sans instructeur)',
                'description' => 'Cours d\'éveil corporel et martial pour les tout-petits. Cours supervisé sans instructeur fixe.',
                'instructeur_id' => null, // Test instructeur optionnel
                'ecole_id' => $ecole->id,
                'niveau' => 'debutant',
                'age_min' => 4,
                'age_max' => 6,
                'places_max' => 8,
                'jour_semaine' => 'samedi',
                'heure_debut' => '09:30:00',
                'heure_fin' => '10:30:00',
                'date_debut' => '2025-09-01',
                'date_fin' => '2025-12-20',
                'tarif_mensuel' => 35.00,
                'actif' => true,
                'couleur_calendrier' => '#f59e0b',
                'salle' => 'Salle Annexe',
            ],
        ];

        foreach ($coursDemo as $cours) {
            Cours::create($cours);
        }

        $this->command->info('✅ '.count($coursDemo).' cours de démonstration créés');
        $this->command->info('📍 École: '.$ecole->nom);
        $this->command->info('👨‍🏫 Instructeur: '.($instructeur ? $instructeur->name : 'Non assigné'));
    }
}
