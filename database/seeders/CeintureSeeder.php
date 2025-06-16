<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ceinture;

class CeintureSeeder extends Seeder
{
    public function run()
    {
        $ceintures = [
            ['nom' => 'Ceinture Blanche', 'couleur' => 'blanc', 'niveau' => 1, 'ordre_affichage' => 1, 'description' => 'Ceinture de débutant', 'duree_minimum_mois' => 0],
            ['nom' => 'Ceinture Jaune', 'couleur' => 'jaune', 'niveau' => 2, 'ordre_affichage' => 2, 'description' => 'Première ceinture colorée', 'duree_minimum_mois' => 3],
            ['nom' => 'Ceinture Orange', 'couleur' => 'orange', 'niveau' => 3, 'ordre_affichage' => 3, 'description' => 'Progression vers les ceintures intermédiaires', 'duree_minimum_mois' => 3],
            ['nom' => 'Ceinture Violet', 'couleur' => 'violet', 'niveau' => 4, 'ordre_affichage' => 4, 'description' => 'Niveau intermédiaire', 'duree_minimum_mois' => 4],
            ['nom' => 'Ceinture Bleue', 'couleur' => 'bleu', 'niveau' => 5, 'ordre_affichage' => 5, 'description' => 'Ceinture bleue standard', 'duree_minimum_mois' => 4],
            ['nom' => 'Ceinture Bleue I', 'couleur' => 'bleu', 'niveau' => 6, 'ordre_affichage' => 6, 'description' => 'Ceinture bleue avec barrette', 'duree_minimum_mois' => 4],
            ['nom' => 'Ceinture Verte', 'couleur' => 'vert', 'niveau' => 7, 'ordre_affichage' => 7, 'description' => 'Ceinture verte standard', 'duree_minimum_mois' => 6],
            ['nom' => 'Ceinture Verte I', 'couleur' => 'vert', 'niveau' => 8, 'ordre_affichage' => 8, 'description' => 'Ceinture verte avec barrette', 'duree_minimum_mois' => 6],
            ['nom' => 'Ceinture Brune I', 'couleur' => 'marron', 'niveau' => 9, 'ordre_affichage' => 9, 'description' => 'Première ceinture brune', 'duree_minimum_mois' => 8],
            ['nom' => 'Ceinture Brune II', 'couleur' => 'marron', 'niveau' => 10, 'ordre_affichage' => 10, 'description' => 'Deuxième ceinture brune', 'duree_minimum_mois' => 8],
            ['nom' => 'Ceinture Brune III', 'couleur' => 'marron', 'niveau' => 11, 'ordre_affichage' => 11, 'description' => 'Troisième ceinture brune', 'duree_minimum_mois' => 8],
            ['nom' => 'Ceinture Noire (1er Dan) — Shodan', 'couleur' => 'noir', 'niveau' => 12, 'ordre_affichage' => 12, 'description' => 'Premier Dan - Shodan', 'duree_minimum_mois' => 12],
            ['nom' => 'Ceinture Noire (2e Dan) — Nidan', 'couleur' => 'noir', 'niveau' => 13, 'ordre_affichage' => 13, 'description' => 'Deuxième Dan - Nidan', 'duree_minimum_mois' => 24],
            ['nom' => 'Ceinture Noire (3e Dan) — Sandan', 'couleur' => 'noir', 'niveau' => 14, 'ordre_affichage' => 14, 'description' => 'Troisième Dan - Sandan', 'duree_minimum_mois' => 36],
            ['nom' => 'Ceinture Noire (4e Dan) — Yondan', 'couleur' => 'noir', 'niveau' => 15, 'ordre_affichage' => 15, 'description' => 'Quatrième Dan - Yondan', 'duree_minimum_mois' => 48],
            ['nom' => 'Ceinture Noire (5e Dan) — Godan', 'couleur' => 'noir', 'niveau' => 16, 'ordre_affichage' => 16, 'description' => 'Cinquième Dan - Godan', 'duree_minimum_mois' => 60],
            ['nom' => 'Ceinture Noire (6e Dan) — Rokudan', 'couleur' => 'noir', 'niveau' => 17, 'ordre_affichage' => 17, 'description' => 'Sixième Dan - Rokudan', 'duree_minimum_mois' => 72],
            ['nom' => 'Ceinture Noire (7e Dan) — Nanadan', 'couleur' => 'noir', 'niveau' => 18, 'ordre_affichage' => 18, 'description' => 'Septième Dan - Nanadan', 'duree_minimum_mois' => 84],
            ['nom' => 'Ceinture Noire (8e Dan) — Hachidan', 'couleur' => 'noir', 'niveau' => 19, 'ordre_affichage' => 19, 'description' => 'Huitième Dan - Hachidan', 'duree_minimum_mois' => 96],
            ['nom' => 'Ceinture Noire (9e Dan) — Kudan', 'couleur' => 'noir', 'niveau' => 20, 'ordre_affichage' => 20, 'description' => 'Neuvième Dan - Kudan', 'duree_minimum_mois' => 108],
            ['nom' => 'Ceinture Noire (10e Dan) — Jūdan', 'couleur' => 'noir', 'niveau' => 21, 'ordre_affichage' => 21, 'description' => 'Dixième Dan - Jūdan - Grade ultime', 'duree_minimum_mois' => 120],
        ];

        foreach ($ceintures as $ceinture) {
            Ceinture::updateOrCreate(
                ['niveau' => $ceinture['niveau']],
                $ceinture
            );
        }

        $this->command->info('✅ 21 ceintures créées avec succès');
    }
}
