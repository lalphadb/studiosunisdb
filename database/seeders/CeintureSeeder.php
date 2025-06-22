<?php
namespace Database\Seeders;

use App\Models\Ceinture;
use Illuminate\Database\Seeder;

class CeintureSeeder extends Seeder
{
    public function run()
    {
        $ceintures = [
            ['nom' => 'Blanche', 'couleur' => '#FFFFFF', 'ordre' => 1, 'description' => 'Ceinture débutant'],
            ['nom' => 'Jaune', 'couleur' => '#FFFF00', 'ordre' => 2, 'description' => 'Premier niveau coloré'],
            ['nom' => 'Orange', 'couleur' => '#FFA500', 'ordre' => 3, 'description' => 'Progression intermédiaire'],
            ['nom' => 'Verte', 'couleur' => '#008000', 'ordre' => 4, 'description' => 'Niveau intermédiaire'],
            ['nom' => 'Bleue', 'couleur' => '#0000FF', 'ordre' => 5, 'description' => 'Niveau avancé débutant'],
            ['nom' => 'Brune', 'couleur' => '#8B4513', 'ordre' => 6, 'description' => 'Niveau avancé'],
            ['nom' => 'Noire 1er Dan', 'couleur' => '#000000', 'ordre' => 7, 'description' => 'Expert niveau 1'],
            ['nom' => 'Noire 2e Dan', 'couleur' => '#000000', 'ordre' => 8, 'description' => 'Expert niveau 2'],
            ['nom' => 'Noire 3e Dan', 'couleur' => '#000000', 'ordre' => 9, 'description' => 'Expert niveau 3'],
            ['nom' => 'Noire 4e Dan', 'couleur' => '#000000', 'ordre' => 10, 'description' => 'Maître niveau 1'],
        ];

        foreach ($ceintures as $ceinture) {
            Ceinture::firstOrCreate(['ordre' => $ceinture['ordre']], $ceinture);
        }
        
        $this->command->info('✅ 10 ceintures créées!');
    }
}
