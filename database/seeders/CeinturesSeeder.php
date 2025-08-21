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
        // Vider la table pour éviter les doublons
        DB::table('ceintures')->truncate();

        // Les 21 ceintures officielles
        $ceintures = [
            ['ordre' => 1,  'nom' => 'Blanche',         'couleur_hex' => '#FFFFFF'],
            ['ordre' => 2,  'nom' => 'Jaune',           'couleur_hex' => '#FFD700'],
            ['ordre' => 3,  'nom' => 'Orange',          'couleur_hex' => '#FFA500'],
            ['ordre' => 4,  'nom' => 'Violette',        'couleur_hex' => '#8A2BE2'],
            ['ordre' => 5,  'nom' => 'Bleue',           'couleur_hex' => '#0066CC'],
            ['ordre' => 6,  'nom' => 'Bleue Rayée',     'couleur_hex' => '#0066CC'],
            ['ordre' => 7,  'nom' => 'Verte',           'couleur_hex' => '#228B22'],
            ['ordre' => 8,  'nom' => 'Verte Rayée',     'couleur_hex' => '#228B22'],
            ['ordre' => 9,  'nom' => 'Marron 1 Rayée',  'couleur_hex' => '#8B4513'],
            ['ordre' => 10, 'nom' => 'Marron 2 Rayées', 'couleur_hex' => '#8B4513'],
            ['ordre' => 11, 'nom' => 'Marron 3 Rayées', 'couleur_hex' => '#8B4513'],
            ['ordre' => 12, 'nom' => 'Noire Shodan',    'couleur_hex' => '#000000'],
            ['ordre' => 13, 'nom' => 'Noire Nidan',     'couleur_hex' => '#000000'],
            ['ordre' => 14, 'nom' => 'Noire Sandan',    'couleur_hex' => '#000000'],
            ['ordre' => 15, 'nom' => 'Noire Yondan',    'couleur_hex' => '#000000'],
            ['ordre' => 16, 'nom' => 'Noire Godan',     'couleur_hex' => '#000000'],
            ['ordre' => 17, 'nom' => 'Noire Rokudan',   'couleur_hex' => '#000000'],
            ['ordre' => 18, 'nom' => 'Noire Nanadan',   'couleur_hex' => '#000000'],
            ['ordre' => 19, 'nom' => 'Noire Hachidan',  'couleur_hex' => '#000000'],
            ['ordre' => 20, 'nom' => 'Noire Kyudan',    'couleur_hex' => '#000000'],
            ['ordre' => 21, 'nom' => 'Noire Judan',     'couleur_hex' => '#000000'],
        ];

        foreach ($ceintures as $ceinture) {
            Ceinture::create([
                'nom' => $ceinture['nom'],
                'couleur_hex' => $ceinture['couleur_hex'],
                'ordre' => $ceinture['ordre'],
                'description' => $this->getDescription($ceinture['ordre']),
                'duree_minimum_mois' => $this->getDureeMinimum($ceinture['ordre']),
                'presences_minimum' => $this->getPresencesMinimum($ceinture['ordre']),
                'age_minimum' => $this->getAgeMinimum($ceinture['ordre']),
                'tarif_examen' => $this->getTarifExamen($ceinture['ordre']),
                'examen_requis' => $ceinture['ordre'] > 1,
                'actif' => true,
                'prerequis_techniques' => $this->getPrerequisTechniques($ceinture['ordre']),
            ]);
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

    private function getDureeMinimum($ordre): int
    {
        if ($ordre <= 4) return 3;   // Ceintures colorées : 3 mois
        if ($ordre <= 8) return 6;   // Ceintures intermédiaires : 6 mois
        if ($ordre <= 11) return 12; // Marrons : 12 mois
        return 24;                   // Noires : 24 mois minimum
    }

    private function getPresencesMinimum($ordre): int
    {
        if ($ordre <= 4) return 20;
        if ($ordre <= 8) return 40;
        if ($ordre <= 11) return 60;
        return 80;
    }

    private function getAgeMinimum($ordre): int
    {
        if ($ordre <= 8) return 5;
        if ($ordre <= 11) return 12;
        if ($ordre <= 12) return 16;
        return 18;
    }

    private function getTarifExamen($ordre): float
    {
        if ($ordre <= 4) return 25.00;
        if ($ordre <= 8) return 40.00;
        if ($ordre <= 11) return 60.00;
        return 100.00;
    }

    private function getPrerequisTechniques($ordre): array
    {
        $techniques = [
            1 => ['Salut', 'Position de base', 'Marche'],
            2 => ['Blocages de base', 'Coups de poing directs', 'Coups de pied bas'],
            3 => ['Techniques de niveau moyen', 'Premiers katas'],
            4 => ['Combinations techniques', 'Kata Heian Shodan'],
            5 => ['Techniques avancées', 'Kata Heian Nidan'],
            6 => ['Kata Heian Sandan', 'Kumite de base'],
            7 => ['Kata Heian Yondan', 'Kumite intermédiaire'],
            8 => ['Kata Heian Godan', 'Kumite avancé'],
            9 => ['Kata Tekki Shodan', 'Bunkai'],
            10 => ['Kata Bassai Dai', 'Ippon Kumite'],
            11 => ['Kata Kanku Dai', 'Jiyu Kumite'],
        ];

        if ($ordre >= 12) {
            return ['Maîtrise complète du niveau précédent', 'Kata supérieur', 'Enseignement'];
        }

        return $techniques[$ordre] ?? ['Techniques du niveau précédent'];
    }
}
