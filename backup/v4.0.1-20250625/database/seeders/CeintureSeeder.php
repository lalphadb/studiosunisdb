<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ceinture;

class CeintureSeeder extends Seeder
{
    public function run()
    {
        $ceintures = [
            ['nom' => 'Blanche', 'couleur' => '#FFFFFF', 'ordre' => 1, 'description' => 'Débutant'],
            ['nom' => 'Jaune', 'couleur' => '#FFFF00', 'ordre' => 2, 'description' => 'Premier niveau'],
            ['nom' => 'Orange', 'couleur' => '#FFA500', 'ordre' => 3, 'description' => 'Deuxième niveau'],
            ['nom' => 'Verte', 'couleur' => '#008000', 'ordre' => 4, 'description' => 'Troisième niveau'],
            ['nom' => 'Bleue', 'couleur' => '#0000FF', 'ordre' => 5, 'description' => 'Quatrième niveau'],
            ['nom' => 'Brune', 'couleur' => '#8B4513', 'ordre' => 6, 'description' => 'Cinquième niveau'],
            ['nom' => 'Noire 1er Dan', 'couleur' => '#000000', 'ordre' => 7, 'description' => 'Premier Dan'],
            ['nom' => 'Noire 2e Dan', 'couleur' => '#000000', 'ordre' => 8, 'description' => 'Deuxième Dan'],
            ['nom' => 'Noire 3e Dan', 'couleur' => '#000000', 'ordre' => 9, 'description' => 'Troisième Dan'],
            ['nom' => 'Noire 4e Dan', 'couleur' => '#000000', 'ordre' => 10, 'description' => 'Quatrième Dan'],
        ];

        foreach ($ceintures as $ceinture) {
            Ceinture::updateOrCreate(
                ['nom' => $ceinture['nom']],
                $ceinture
            );
        }
    }
}
