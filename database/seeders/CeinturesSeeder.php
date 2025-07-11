<?php

namespace Database\Seeders;

use App\Models\{Ceinture, Ecole};
use Illuminate\Database\Seeder;

class CeinturesSeeder extends Seeder
{
    public function run(): void
    {
        // Les 21 ceintures officielles StudiosUnis
        $ceintures = [
            ['ordre' => 1,  'nom' => 'Blanche',            'couleur_principale' => 'white',   'couleur_hex' => '#FFFFFF', 'mois_minimum' => 0],
            ['ordre' => 2,  'nom' => 'Jaune',              'couleur_principale' => 'yellow',  'couleur_hex' => '#FFD700', 'mois_minimum' => 3],
            ['ordre' => 3,  'nom' => 'Orange',             'couleur_principale' => 'orange',  'couleur_hex' => '#FFA500', 'mois_minimum' => 6],
            ['ordre' => 4,  'nom' => 'Violette',           'couleur_principale' => 'purple',  'couleur_hex' => '#8A2BE2', 'mois_minimum' => 9],
            ['ordre' => 5,  'nom' => 'Bleue',              'couleur_principale' => 'blue',    'couleur_hex' => '#1E90FF', 'mois_minimum' => 12],
            ['ordre' => 6,  'nom' => 'Bleue Rayée',        'couleur_principale' => 'blue',    'couleur_secondaire' => 'white', 'couleur_hex' => '#1E90FF', 'mois_minimum' => 15],
            ['ordre' => 7,  'nom' => 'Verte',              'couleur_principale' => 'green',   'couleur_hex' => '#32CD32', 'mois_minimum' => 18],
            ['ordre' => 8,  'nom' => 'Verte Rayée',        'couleur_principale' => 'green',   'couleur_secondaire' => 'white', 'couleur_hex' => '#32CD32', 'mois_minimum' => 21],
            ['ordre' => 9,  'nom' => 'Marron 1 Rayée',     'couleur_principale' => 'brown',   'couleur_secondaire' => 'white', 'couleur_hex' => '#8B4513', 'mois_minimum' => 24],
            ['ordre' => 10, 'nom' => 'Marron 2 Rayées',    'couleur_principale' => 'brown',   'couleur_secondaire' => 'white', 'couleur_hex' => '#8B4513', 'mois_minimum' => 30],
            ['ordre' => 11, 'nom' => 'Marron 3 Rayées',    'couleur_principale' => 'brown',   'couleur_secondaire' => 'white', 'couleur_hex' => '#8B4513', 'mois_minimum' => 36],
            ['ordre' => 12, 'nom' => 'Noire Shodan',       'couleur_principale' => 'black',   'couleur_hex' => '#000000', 'mois_minimum' => 42, 'nom_anglais' => 'Shodan'],
            ['ordre' => 13, 'nom' => 'Noire Nidan',        'couleur_principale' => 'black',   'couleur_hex' => '#000000', 'mois_minimum' => 54, 'nom_anglais' => 'Nidan'],
            ['ordre' => 14, 'nom' => 'Noire Sandan',       'couleur_principale' => 'black',   'couleur_hex' => '#000000', 'mois_minimum' => 66, 'nom_anglais' => 'Sandan'],
            ['ordre' => 15, 'nom' => 'Noire Yondan',       'couleur_principale' => 'black',   'couleur_hex' => '#000000', 'mois_minimum' => 78, 'nom_anglais' => 'Yondan'],
            ['ordre' => 16, 'nom' => 'Noire Godan',        'couleur_principale' => 'black',   'couleur_hex' => '#000000', 'mois_minimum' => 90, 'nom_anglais' => 'Godan'],
            ['ordre' => 17, 'nom' => 'Noire Rokudan',      'couleur_principale' => 'black',   'couleur_hex' => '#000000', 'mois_minimum' => 102, 'nom_anglais' => 'Rokudan'],
            ['ordre' => 18, 'nom' => 'Noire Nanadan',      'couleur_principale' => 'black',   'couleur_hex' => '#000000', 'mois_minimum' => 114, 'nom_anglais' => 'Nanadan'],
            ['ordre' => 19, 'nom' => 'Noire Hachidan',     'couleur_principale' => 'black',   'couleur_hex' => '#000000', 'mois_minimum' => 126, 'nom_anglais' => 'Hachidan'],
            ['ordre' => 20, 'nom' => 'Noire Kyudan',       'couleur_principale' => 'black',   'couleur_hex' => '#000000', 'mois_minimum' => 138, 'nom_anglais' => 'Kyudan'],
            ['ordre' => 21, 'nom' => 'Noire Judan',        'couleur_principale' => 'black',   'couleur_hex' => '#000000', 'mois_minimum' => 150, 'nom_anglais' => 'Judan'],
        ];

        $ecoles = Ecole::all();
        $totalCreated = 0;

        // Ajouter les cours minimum par défaut
        foreach ($ceintures as &$ceintureData) {
            if (!isset($ceintureData['cours_minimum'])) {
                $ceintureData['cours_minimum'] = 0;
            }
            // Retirer couleur_hex qui n'existe pas dans la table
            unset($ceintureData['couleur_hex']);
        }

        foreach ($ecoles as $ecole) {
            $this->command->info("\nCréation des ceintures pour: " . $ecole->nom);
            
            foreach ($ceintures as $ceintureData) {
                $ceinture = Ceinture::firstOrCreate(
                    [
                        'ecole_id' => $ecole->id,
                        'ordre' => $ceintureData['ordre']
                    ],
                    array_merge($ceintureData, [
                        'ecole_id' => $ecole->id,
                        'description' => "Ceinture {$ceintureData['nom']} - Rang {$ceintureData['ordre']}",
                        'actif' => true
                    ])
                );
                
                if ($ceinture->wasRecentlyCreated) {
                    $totalCreated++;
                }
            }
        }

        $this->command->info("\n✅ Total: $totalCreated ceintures créées (21 par école × " . $ecoles->count() . " écoles)");
    }
}
