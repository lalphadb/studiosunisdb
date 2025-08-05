<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ceinture;

class CeintureSeeder extends Seeder
{
    /**
     * Seeder Ceintures Arts Martiaux Ultra-Professionnel
     * Basé sur le système traditionnel de grades
     */
    public function run(): void
    {
        $ceintures = [
            [
                'nom' => 'Ceinture Blanche',
                'couleur_hex' => '#FFFFFF',
                'ordre' => 1,
                'description' => 'Grade débutant - Apprentissage des fondamentaux',
                'duree_minimum_mois' => 0,
                'presences_minimum' => 0,
                'age_minimum' => 5,
                'tarif_examen' => 0.00,
                'examen_requis' => false,
                'actif' => true,
            ],
            [
                'nom' => 'Ceinture Jaune',
                'couleur_hex' => '#FFD700',
                'ordre' => 2,
                'description' => 'Premier grade coloré - Maîtrise des bases',
                'duree_minimum_mois' => 3,
                'presences_minimum' => 24,
                'age_minimum' => 5,
                'tarif_examen' => 50.00,
                'examen_requis' => true,
                'actif' => true,
            ],
            [
                'nom' => 'Ceinture Orange',
                'couleur_hex' => '#FF8C00',
                'ordre' => 3,
                'description' => 'Progression technique intermédiaire',
                'duree_minimum_mois' => 4,
                'presences_minimum' => 32,
                'age_minimum' => 6,
                'tarif_examen' => 60.00,
                'examen_requis' => true,
                'actif' => true,
            ],
            [
                'nom' => 'Ceinture Verte',
                'couleur_hex' => '#32CD32',
                'ordre' => 4,
                'description' => 'Niveau intermédiaire confirmé',
                'duree_minimum_mois' => 6,
                'presences_minimum' => 48,
                'age_minimum' => 7,
                'tarif_examen' => 70.00,
                'examen_requis' => true,
                'actif' => true,
            ],
            [
                'nom' => 'Ceinture Bleue',
                'couleur_hex' => '#4169E1',
                'ordre' => 5,
                'description' => 'Niveau avancé - Techniques complexes',
                'duree_minimum_mois' => 8,
                'presences_minimum' => 64,
                'age_minimum' => 9,
                'tarif_examen' => 80.00,
                'examen_requis' => true,
                'actif' => true,
            ],
            [
                'nom' => 'Ceinture Marron',
                'couleur_hex' => '#8B4513',
                'ordre' => 6,
                'description' => 'Pré-ceinture noire - Maîtrise technique',
                'duree_minimum_mois' => 12,
                'presences_minimum' => 96,
                'age_minimum' => 12,
                'tarif_examen' => 100.00,
                'examen_requis' => true,
                'actif' => true,
            ],
            [
                'nom' => 'Ceinture Noire 1er Dan',
                'couleur_hex' => '#000000',
                'ordre' => 7,
                'description' => 'Premier niveau expert - Enseignement junior',
                'duree_minimum_mois' => 18,
                'presences_minimum' => 144,
                'age_minimum' => 16,
                'tarif_examen' => 150.00,
                'examen_requis' => true,
                'actif' => true,
            ],
        ];

        foreach ($ceintures as $ceinture) {
            Ceinture::updateOrCreate(
                ['ordre' => $ceinture['ordre']], 
                $ceinture
            );
        }
    }
}
