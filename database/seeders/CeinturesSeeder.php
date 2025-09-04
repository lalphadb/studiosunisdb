<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ceinture;
use Illuminate\Support\Facades\DB;

class CeinturesSeeder extends Seeder
{
    /**
     * Les 21 ceintures officielles StudiosUnis
     */
    public function run(): void
    {
        // Utiliser updateOrCreate pour éviter les doublons (idempotent)
        // Les 21 ceintures officielles
        $ceintures = [
            ['order' => 1,  'name' => 'Blanche',         'name_en' => 'White',         'color_hex' => '#FFFFFF'],
            ['order' => 2,  'name' => 'Jaune',           'name_en' => 'Yellow',        'color_hex' => '#FFD700'],
            ['order' => 3,  'name' => 'Orange',          'name_en' => 'Orange',        'color_hex' => '#FFA500'],
            ['order' => 4,  'name' => 'Violette',        'name_en' => 'Purple',        'color_hex' => '#8A2BE2'],
            ['order' => 5,  'name' => 'Bleue',           'name_en' => 'Blue',          'color_hex' => '#0066CC'],
            ['order' => 6,  'name' => 'Bleue Rayée',     'name_en' => 'Blue Stripe',   'color_hex' => '#0066CC'],
            ['order' => 7,  'name' => 'Verte',           'name_en' => 'Green',         'color_hex' => '#228B22'],
            ['order' => 8,  'name' => 'Verte Rayée',     'name_en' => 'Green Stripe',  'color_hex' => '#228B22'],
            ['order' => 9,  'name' => 'Marron 1 Rayée',  'name_en' => 'Brown 1 Stripe','color_hex' => '#8B4513'],
            ['order' => 10, 'name' => 'Marron 2 Rayées', 'name_en' => 'Brown 2 Stripes','color_hex' => '#8B4513'],
            ['order' => 11, 'name' => 'Marron 3 Rayées', 'name_en' => 'Brown 3 Stripes','color_hex' => '#8B4513'],
            ['order' => 12, 'name' => 'Noire Shodan',    'name_en' => 'Black 1st Dan', 'color_hex' => '#000000'],
            ['order' => 13, 'name' => 'Noire Nidan',     'name_en' => 'Black 2nd Dan', 'color_hex' => '#000000'],
            ['order' => 14, 'name' => 'Noire Sandan',    'name_en' => 'Black 3rd Dan', 'color_hex' => '#000000'],
            ['order' => 15, 'name' => 'Noire Yondan',    'name_en' => 'Black 4th Dan', 'color_hex' => '#000000'],
            ['order' => 16, 'name' => 'Noire Godan',     'name_en' => 'Black 5th Dan', 'color_hex' => '#000000'],
            ['order' => 17, 'name' => 'Noire Rokudan',   'name_en' => 'Black 6th Dan', 'color_hex' => '#000000'],
            ['order' => 18, 'name' => 'Noire Nanadan',   'name_en' => 'Black 7th Dan', 'color_hex' => '#000000'],
            ['order' => 19, 'name' => 'Noire Hachidan',  'name_en' => 'Black 8th Dan', 'color_hex' => '#000000'],
            ['order' => 20, 'name' => 'Noire Kyudan',    'name_en' => 'Black 9th Dan', 'color_hex' => '#000000'],
            ['order' => 21, 'name' => 'Noire Judan',     'name_en' => 'Black 10th Dan','color_hex' => '#000000'],
        ];

        foreach ($ceintures as $ceinture) {
            Ceinture::updateOrCreate(
                ['order' => $ceinture['order']],
                [
                    'name' => $ceinture['name'],
                    'name_en' => $ceinture['name_en'],
                    'color_hex' => $ceinture['color_hex'],
                    'description' => $this->getDescription($ceinture['order']),
                    'minimum_duration_months' => $this->getMinimumDuration($ceinture['order']),
                    'minimum_attendances' => $this->getMinimumAttendances($ceinture['order']),
                    'active' => true,
                ]
            );
        }

        $this->command->info('✅ 21 ceintures officielles créées !');
    }

    private function getDescription($ordre): string
    {
        $descriptions = [
            1 => 'Ceinture de débutant - Introduction au karaté',
            2 => 'Première progression - Techniques de base',
            3 => 'Techniques de base acquises',
            4 => 'Progression intermédiaire',
            5 => 'Techniques avancées',
            6 => 'Perfectionnement niveau bleu',
            7 => 'Niveau vert de base',
            8 => 'Perfectionnement niveau vert',
            9 => 'Premier niveau marron',
            10 => 'Deuxième niveau marron',
            11 => 'Troisième niveau marron',
            12 => '1er Dan - Ceinture noire',
            13 => '2e Dan - Expertise confirmée',
            14 => '3e Dan - Maîtrise technique',
            15 => '4e Dan - Expert technique',
            16 => '5e Dan - Maître Rensei',
            17 => '6e Dan - Maître supérieur',
            18 => '7e Dan - Grand maître',
            19 => '8e Dan - Hanshi',
            20 => '9e Dan - Kyoshi',
            21 => '10e Dan - Meijin',
        ];
        return $descriptions[$ordre] ?? 'Niveau supérieur';
    }

    private function getMinimumDuration($ordre): int
    {
        if ($ordre <= 4) return 3;   // Ceintures colorées : 3 mois
        if ($ordre <= 8) return 6;   // Ceintures intermédiaires : 6 mois
        if ($ordre <= 11) return 12; // Marrons : 12 mois
        return 24;                   // Noires : 24 mois minimum
    }

    private function getMinimumAttendances($ordre): int
    {
        if ($ordre <= 4) return 20;
        if ($ordre <= 8) return 40;
        if ($ordre <= 11) return 60;
        return 80;
    }
}
