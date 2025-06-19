<?php

namespace Database\Seeders;

use App\Models\Ceinture;
use Illuminate\Database\Seeder;

class CeintureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ceintures = [
            [
                'nom' => 'Blanche',
                'couleur' => '#FFFFFF',
                'ordre' => 1,
                'description' => 'Ceinture débutant - Premier niveau'
            ],
            [
                'nom' => 'Jaune',
                'couleur' => '#FFFF00',
                'ordre' => 2,
                'description' => 'Première ceinture colorée'
            ],
            [
                'nom' => 'Orange',
                'couleur' => '#FFA500',
                'ordre' => 3,
                'description' => 'Deuxième ceinture colorée'
            ],
            [
                'nom' => 'Verte',
                'couleur' => '#008000',
                'ordre' => 4,
                'description' => 'Ceinture intermédiaire'
            ],
            [
                'nom' => 'Bleue',
                'couleur' => '#0000FF',
                'ordre' => 5,
                'description' => 'Ceinture avancée'
            ],
            [
                'nom' => 'Marron',
                'couleur' => '#8B4513',
                'ordre' => 6,
                'description' => 'Ceinture experte'
            ],
            [
                'nom' => 'Noire 1er Dan',
                'couleur' => '#000000',
                'ordre' => 7,
                'description' => 'Première ceinture noire'
            ],
            [
                'nom' => 'Noire 2ème Dan',
                'couleur' => '#000000',
                'ordre' => 8,
                'description' => 'Deuxième Dan'
            ],
            [
                'nom' => 'Noire 3ème Dan',
                'couleur' => '#000000',
                'ordre' => 9,
                'description' => 'Troisième Dan'
            ],
        ];

        foreach ($ceintures as $ceintureData) {
            // Utiliser firstOrCreate avec les bonnes colonnes
            Ceinture::firstOrCreate(
                ['nom' => $ceintureData['nom']], // Condition de recherche
                $ceintureData // Données à insérer si pas trouvé
            );
        }

        $this->command->info('✅ ' . count($ceintures) . ' ceintures créées/vérifiées');
    }
}
