<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CeinturesOfficielles extends Seeder
{
    /**
     * Les 21 ceintures officielles StudiosUnis
     */
    public function run(): void
    {
        $ceintures = [
            ['order' => 1,  'name' => 'Blanche',         'color_hex' => '#FFFFFF', 'name_en' => 'White'],
            ['order' => 2,  'name' => 'Jaune',           'color_hex' => '#FFD700', 'name_en' => 'Yellow'],
            ['order' => 3,  'name' => 'Orange',          'color_hex' => '#FF8C00', 'name_en' => 'Orange'],
            ['order' => 4,  'name' => 'Violette',        'color_hex' => '#8B008B', 'name_en' => 'Purple'],
            ['order' => 5,  'name' => 'Bleue',           'color_hex' => '#0000CD', 'name_en' => 'Blue'],
            ['order' => 6,  'name' => 'Bleue Rayée',     'color_hex' => '#4169E1', 'name_en' => 'Blue Striped'],
            ['order' => 7,  'name' => 'Verte',           'color_hex' => '#228B22', 'name_en' => 'Green'],
            ['order' => 8,  'name' => 'Verte Rayée',     'color_hex' => '#32CD32', 'name_en' => 'Green Striped'],
            ['order' => 9,  'name' => 'Marron 1 Rayée',  'color_hex' => '#8B4513', 'name_en' => 'Brown 1 Stripe'],
            ['order' => 10, 'name' => 'Marron 2 Rayées', 'color_hex' => '#A0522D', 'name_en' => 'Brown 2 Stripes'],
            ['order' => 11, 'name' => 'Marron 3 Rayées', 'color_hex' => '#654321', 'name_en' => 'Brown 3 Stripes'],
            ['order' => 12, 'name' => 'Noire Shodan',    'color_hex' => '#1C1C1C', 'name_en' => 'Black Shodan'],
            ['order' => 13, 'name' => 'Noire Nidan',     'color_hex' => '#1C1C1C', 'name_en' => 'Black Nidan'],
            ['order' => 14, 'name' => 'Noire Sandan',    'color_hex' => '#1C1C1C', 'name_en' => 'Black Sandan'],
            ['order' => 15, 'name' => 'Noire Yondan',    'color_hex' => '#1C1C1C', 'name_en' => 'Black Yondan'],
            ['order' => 16, 'name' => 'Noire Godan',     'color_hex' => '#1C1C1C', 'name_en' => 'Black Godan'],
            ['order' => 17, 'name' => 'Noire Rokudan',   'color_hex' => '#1C1C1C', 'name_en' => 'Black Rokudan'],
            ['order' => 18, 'name' => 'Noire Nanadan',   'color_hex' => '#1C1C1C', 'name_en' => 'Black Nanadan'],
            ['order' => 19, 'name' => 'Noire Hachidan',  'color_hex' => '#1C1C1C', 'name_en' => 'Black Hachidan'],
            ['order' => 20, 'name' => 'Noire Kyudan',    'color_hex' => '#1C1C1C', 'name_en' => 'Black Kyudan'],
            ['order' => 21, 'name' => 'Noire Judan',     'color_hex' => '#1C1C1C', 'name_en' => 'Black Judan'],
        ];

        // Désactiver temporairement les contraintes de clés étrangères
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Vider la table existante
        DB::table('ceintures')->truncate();
        
        // Réactiver les contraintes
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Insérer les nouvelles ceintures
        foreach ($ceintures as $ceinture) {
            DB::table('ceintures')->insert([
                'order' => $ceinture['order'],
                'name' => $ceinture['name'],
                'color_hex' => $ceinture['color_hex'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        echo "✅ 21 ceintures officielles insérées avec succès!\n";
        
        // Afficher la liste pour vérification
        $inserted = DB::table('ceintures')->orderBy('order')->pluck('name');
        echo "Ceintures: " . $inserted->implode(', ') . "\n";
    }
}
