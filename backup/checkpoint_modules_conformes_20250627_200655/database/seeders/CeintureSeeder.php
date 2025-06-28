<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ceinture;

class CeintureSeeder extends Seeder
{
    public function run(): void
    {
        $ceintures = [
            // CEINTURES KYU (Grades inférieurs)
            ['nom' => 'Ceinture Blanche', 'couleur' => '#FFFFFF', 'ordre' => 1, 'description' => 'Débutant - Premier grade'],
            ['nom' => 'Ceinture Jaune', 'couleur' => '#FFD700', 'ordre' => 2, 'description' => '9e Kyu - Bases du karaté'],
            ['nom' => 'Ceinture Orange', 'couleur' => '#FFA500', 'ordre' => 3, 'description' => '8e Kyu - Progression technique'],
            ['nom' => 'Ceinture Verte', 'couleur' => '#32CD32', 'ordre' => 4, 'description' => '7e Kyu - Techniques intermédiaires'],
            ['nom' => 'Ceinture Bleue', 'couleur' => '#1E90FF', 'ordre' => 5, 'description' => '6e Kyu - Maîtrise technique'],
            ['nom' => 'Ceinture Marron 1', 'couleur' => '#8B4513', 'ordre' => 6, 'description' => '3e Kyu - Préparation ceinture noire'],
            ['nom' => 'Ceinture Marron 2', 'couleur' => '#8B4513', 'ordre' => 7, 'description' => '2e Kyu - Techniques avancées'],
            ['nom' => 'Ceinture Marron 3', 'couleur' => '#8B4513', 'ordre' => 8, 'description' => '1er Kyu - Pré-ceinture noire'],
            
            // CEINTURES DAN (Grades supérieurs)
            ['nom' => 'Ceinture Noire 1er Dan', 'couleur' => '#000000', 'ordre' => 9, 'description' => '1er Dan - Shodan'],
            ['nom' => 'Ceinture Noire 2e Dan', 'couleur' => '#000000', 'ordre' => 10, 'description' => '2e Dan - Nidan'],
            ['nom' => 'Ceinture Noire 3e Dan', 'couleur' => '#000000', 'ordre' => 11, 'description' => '3e Dan - Sandan'],
            ['nom' => 'Ceinture Noire 4e Dan', 'couleur' => '#000000', 'ordre' => 12, 'description' => '4e Dan - Yondan'],
            ['nom' => 'Ceinture Noire 5e Dan', 'couleur' => '#000000', 'ordre' => 13, 'description' => '5e Dan - Godan'],
            ['nom' => 'Ceinture Rouge', 'couleur' => '#DC143C', 'ordre' => 14, 'description' => 'Maître - Hauts grades'],
        ];

        foreach ($ceintures as $ceinture) {
            Ceinture::create($ceinture);
        }

        $this->command->info('✅ ' . count($ceintures) . ' ceintures créées avec succès !');
    }
}
