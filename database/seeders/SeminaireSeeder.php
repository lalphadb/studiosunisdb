<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Seminaire;
use Carbon\Carbon;

class SeminaireSeeder extends Seeder
{
    public function run(): void
    {
        $seminaires = [
            [
                'nom' => 'Stage Technique Avancée',
                'intervenant' => 'Sensei Takeshi Yamamoto',
                'type_seminaire' => 'technique',
                'description' => 'Perfectionnement des techniques de base et applications en combat',
                'date_debut' => Carbon::now()->addDays(15)->format('Y-m-d'),
                'date_fin' => Carbon::now()->addDays(15)->format('Y-m-d'),
                'lieu' => 'Studio Unis Centre-Ville',
                'niveau_cible' => 'Ceinture bleue et plus',
                'pre_requis' => 'Minimum 2 ans de pratique',
                'ouvert_toutes_ecoles' => true,
                'materiel_requis' => 'Karategi, protections',
                'capacite_max' => 40,
                'prix' => 75.00,
                'statut' => 'actif'
            ],
            [
                'nom' => 'Kata Traditionnel',
                'intervenant' => 'Maître Kenji Nakamura', 
                'type_seminaire' => 'kata',
                'description' => 'Apprentissage et perfectionnement des kata traditionnels',
                'date_debut' => Carbon::now()->addDays(30)->format('Y-m-d'),
                'date_fin' => Carbon::now()->addDays(30)->format('Y-m-d'),
                'lieu' => 'Studio Unis Ouest',
                'niveau_cible' => 'Tous niveaux',
                'pre_requis' => null,
                'ouvert_toutes_ecoles' => true,
                'materiel_requis' => 'Karategi blanc uniquement',
                'capacite_max' => 25,
                'prix' => 60.00,
                'statut' => 'actif'
            ],
            [
                'nom' => 'Préparation Compétition',
                'intervenant' => 'Champion David Leblanc',
                'type_seminaire' => 'competition',
                'description' => 'Techniques et stratégies pour la compétition',
                'date_debut' => Carbon::now()->addDays(45)->format('Y-m-d'),
                'date_fin' => Carbon::now()->addDays(45)->format('Y-m-d'),
                'lieu' => 'Studio Unis Nord',
                'niveau_cible' => 'Ceinture verte et plus',
                'pre_requis' => 'Expérience compétition souhaitée',
                'ouvert_toutes_ecoles' => true,
                'materiel_requis' => 'Équipement complet de protection',
                'capacite_max' => 30,
                'prix' => 85.00,
                'statut' => 'actif'
            ]
        ];

        foreach ($seminaires as $seminaire) {
            Seminaire::create($seminaire);
        }
    }
}
