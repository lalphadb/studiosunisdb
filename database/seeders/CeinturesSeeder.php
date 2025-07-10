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
            ['ordre' => 1,  'nom' => 'Blanche',            'couleur' => 'white',   'couleur_hex' => '#FFFFFF', 'mois_minimum' => 0],
            ['ordre' => 2,  'nom' => 'Jaune',              'couleur' => 'yellow',  'couleur_hex' => '#FFD700', 'mois_minimum' => 3],
            ['ordre' => 3,  'nom' => 'Orange',             'couleur' => 'orange',  'couleur_hex' => '#FFA500', 'mois_minimum' => 6],
            ['ordre' => 4,  'nom' => 'Violette',           'couleur' => 'purple',  'couleur_hex' => '#8A2BE2', 'mois_minimum' => 9],
            ['ordre' => 5,  'nom' => 'Bleue',              'couleur' => 'blue',    'couleur_hex' => '#1E90FF', 'mois_minimum' => 12],
            ['ordre' => 6,  'nom' => 'Bleue Rayée',        'couleur' => 'blue',    'couleur_hex' => '#1E90FF', 'mois_minimum' => 15],
            ['ordre' => 7,  'nom' => 'Verte',              'couleur' => 'green',   'couleur_hex' => '#32CD32', 'mois_minimum' => 18],
            ['ordre' => 8,  'nom' => 'Verte Rayée',        'couleur' => 'green',   'couleur_hex' => '#32CD32', 'mois_minimum' => 21],
            ['ordre' => 9,  'nom' => 'Marron 1 Rayée',     'couleur' => 'brown',   'couleur_hex' => '#8B4513', 'mois_minimum' => 24],
            ['ordre' => 10, 'nom' => 'Marron 2 Rayées',    'couleur' => 'brown',   'couleur_hex' => '#8B4513', 'mois_minimum' => 30],
            ['ordre' => 11, 'nom' => 'Marron 3 Rayées',    'couleur' => 'brown',   'couleur_hex' => '#8B4513', 'mois_minimum' => 36],
            ['ordre' => 12, 'nom' => 'Noire Shodan',       'couleur' => 'black',   'couleur_hex' => '#000000', 'mois_minimum' => 42, 'nom_japonais' => 'Shodan'],
            ['ordre' => 13, 'nom' => 'Noire Nidan',        'couleur' => 'black',   'couleur_hex' => '#000000', 'mois_minimum' => 54, 'nom_japonais' => 'Nidan'],
            ['ordre' => 14, 'nom' => 'Noire Sandan',       'couleur' => 'black',   'couleur_hex' => '#000000', 'mois_minimum' => 66, 'nom_japonais' => 'Sandan'],
            ['ordre' => 15, 'nom' => 'Noire Yondan',       'couleur' => 'black',   'couleur_hex' => '#000000', 'mois_minimum' => 78, 'nom_japonais' => 'Yondan'],
            ['ordre' => 16, 'nom' => 'Noire Godan',        'couleur' => 'black',   'couleur_hex' => '#000000', 'mois_minimum' => 90, 'nom_japonais' => 'Godan'],
            ['ordre' => 17, 'nom' => 'Noire Rokudan',      'couleur' => 'black',   'couleur_hex' => '#000000', 'mois_minimum' => 102, 'nom_japonais' => 'Rokudan'],
            ['ordre' => 18, 'nom' => 'Noire Nanadan',      'couleur' => 'black',   'couleur_hex' => '#000000', 'mois_minimum' => 114, 'nom_japonais' => 'Nanadan'],
            ['ordre' => 19, 'nom' => 'Noire Hachidan',     'couleur' => 'black',   'couleur_hex' => '#000000', 'mois_minimum' => 126, 'nom_japonais' => 'Hachidan'],
            ['ordre' => 20, 'nom' => 'Noire Kyudan',       'couleur' => 'black',   'couleur_hex' => '#000000', 'mois_minimum' => 138, 'nom_japonais' => 'Kyudan'],
            ['ordre' => 21, 'nom' => 'Noire Judan',        'couleur' => 'black',   'couleur_hex' => '#000000', 'mois_minimum' => 150, 'nom_japonais' => 'Judan'],
        ];

        $ecoles = Ecole::all();
        $totalCreated = 0;

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
                        'description' => "Ceinture {$ceintureData['nom']} - Rang {$ceintureData['ordre']}"
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
