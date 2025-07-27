<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ceinture;

class CeinturesSeeder extends Seeder
{
    public function run()
    {
        // Suppression des anciennes ceintures pour éviter les doublons
        Ceinture::query()->delete();
        
        // Les 21 ceintures officielles StudiosUnis
        $ceintures = [
            ['ordre' => 1,  'nom' => 'Blanche',         'couleur' => 'white',   'couleur_hex' => '#FFFFFF', 'description' => 'Ceinture de débutant'],
            ['ordre' => 2,  'nom' => 'Jaune',           'couleur' => 'yellow',  'couleur_hex' => '#FFD700', 'description' => 'Première progression'],
            ['ordre' => 3,  'nom' => 'Orange',          'couleur' => 'orange',  'couleur_hex' => '#FFA500', 'description' => 'Techniques de base acquises'],
            ['ordre' => 4,  'nom' => 'Violette',        'couleur' => 'purple',  'couleur_hex' => '#8A2BE2', 'description' => 'Progression intermédiaire'],
            ['ordre' => 5,  'nom' => 'Bleue',           'couleur' => 'blue',    'couleur_hex' => '#0066CC', 'description' => 'Techniques avancées'],
            ['ordre' => 6,  'nom' => 'Bleue Rayée',     'couleur' => 'blue',    'couleur_hex' => '#0066CC', 'description' => 'Perfectionnement bleu'],
            ['ordre' => 7,  'nom' => 'Verte',           'couleur' => 'green',   'couleur_hex' => '#228B22', 'description' => 'Niveau vert de base'],
            ['ordre' => 8,  'nom' => 'Verte Rayée',     'couleur' => 'green',   'couleur_hex' => '#228B22', 'description' => 'Perfectionnement vert'],
            ['ordre' => 9,  'nom' => 'Marron 1 Rayée',  'couleur' => 'brown',   'couleur_hex' => '#8B4513', 'description' => 'Premier niveau marron'],
            ['ordre' => 10, 'nom' => 'Marron 2 Rayées', 'couleur' => 'brown',   'couleur_hex' => '#8B4513', 'description' => 'Deuxième niveau marron'],
            ['ordre' => 11, 'nom' => 'Marron 3 Rayées', 'couleur' => 'brown',   'couleur_hex' => '#8B4513', 'description' => 'Troisième niveau marron'],
            ['ordre' => 12, 'nom' => 'Noire Shodan',    'couleur' => 'black',   'couleur_hex' => '#000000', 'description' => '1er Dan - Ceinture noire'],
            ['ordre' => 13, 'nom' => 'Noire Nidan',     'couleur' => 'black',   'couleur_hex' => '#000000', 'description' => '2e Dan - Expertise confirmée'],
            ['ordre' => 14, 'nom' => 'Noire Sandan',    'couleur' => 'black',   'couleur_hex' => '#000000', 'description' => '3e Dan - Maîtrise technique'],
            ['ordre' => 15, 'nom' => 'Noire Yondan',    'couleur' => 'black',   'couleur_hex' => '#000000', 'description' => '4e Dan - Expert technique'],
            ['ordre' => 16, 'nom' => 'Noire Godan',     'couleur' => 'black',   'couleur_hex' => '#000000', 'description' => '5e Dan - Maître rensei'],
            ['ordre' => 17, 'nom' => 'Noire Rokudan',   'couleur' => 'black',   'couleur_hex' => '#000000', 'description' => '6e Dan - Maître supérieur'],
            ['ordre' => 18, 'nom' => 'Noire Nanadan',   'couleur' => 'black',   'couleur_hex' => '#000000', 'description' => '7e Dan - Grand maître'],
            ['ordre' => 19, 'nom' => 'Noire Hachidan',  'couleur' => 'black',   'couleur_hex' => '#000000', 'description' => '8e Dan - Hanshi'],
            ['ordre' => 20, 'nom' => 'Noire Kyudan',    'couleur' => 'black',   'couleur_hex' => '#000000', 'description' => '9e Dan - Kyoshi'],
            ['ordre' => 21, 'nom' => 'Noire Judan',     'couleur' => 'black',   'couleur_hex' => '#000000', 'description' => '10e Dan - Meijin'],
        ];

        foreach ($ceintures as $ceinture) {
            Ceinture::create([
                'nom' => $ceinture['nom'],
                'couleur_hex' => $ceinture['couleur_hex'],
                'ordre' => $ceinture['ordre'],
                'description' => $ceinture['description'],
                'duree_minimum_mois' => $this->getDureeMinimum($ceinture['ordre']),
                'presences_minimum' => $this->getPresencesMinimum($ceinture['ordre']),
                'age_minimum' => $this->getAgeMinimum($ceinture['ordre']),
                'tarif_examen' => $this->getTarifExamen($ceinture['ordre']),
                'examen_requis' => $ceinture['ordre'] > 1,
                'actif' => true,
                'prerequis_techniques' => $this->getPrerequisTechniques($ceinture['ordre']),
            ]);
        }

        $this->command->info('✅ 21 ceintures officielles StudiosUnis créées avec succès !');
    }

    private function getDureeMinimum($ordre)
    {
        if ($ordre <= 4) return 3; // Ceintures colorées : 3 mois
        if ($ordre <= 8) return 6; // Ceintures intermédiaires : 6 mois
        if ($ordre <= 11) return 12; // Marrons : 12 mois
        return 24; // Noires : 24 mois minimum
    }

    private function getPresencesMinimum($ordre)
    {
        if ($ordre <= 4) return 20;
        if ($ordre <= 8) return 40;
        if ($ordre <= 11) return 60;
        return 80;
    }

    private function getAgeMinimum($ordre)
    {
        if ($ordre <= 8) return 5;
        if ($ordre <= 11) return 12;
        if ($ordre <= 12) return 16;
        return 18;
    }

    private function getTarifExamen($ordre)
    {
        if ($ordre <= 4) return 25.00;
        if ($ordre <= 8) return 40.00;
        if ($ordre <= 11) return 60.00;
        return 100.00;
    }

    private function getPrerequisTechniques($ordre)
    {
        $techniques = [
            1 => ['Salut', 'Position de base', 'Marche'],
            2 => ['Blocages de base', 'Coups de poing directs', 'Coups de pied bas'],
            3 => ['Techniques de niveau moyen', 'Premiers katas'],
            4 => ['Combinations techniques', 'Kata Heian Shodan'],
            5 => ['Techniques avancées', 'Kata Heian Nidan'],
            // ... etc pour les autres niveaux
        ];

        return $techniques[$ordre] ?? ['Techniques avancées du niveau précédent'];
    }
}
