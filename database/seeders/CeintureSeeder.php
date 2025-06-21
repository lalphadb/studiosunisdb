<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ceinture;

class CeintureSeeder extends Seeder
{
    public function run(): void
    {
        $ceintures = [
            ['nom' => 'Blanche', 'couleur' => '#FFFFFF', 'ordre' => 1],
            ['nom' => 'Jaune', 'couleur' => '#FFFF00', 'ordre' => 2],
            ['nom' => 'Orange', 'couleur' => '#FFA500', 'ordre' => 3],
            ['nom' => 'Violet', 'couleur' => '#8A2BE2', 'ordre' => 4],
            ['nom' => 'Bleue', 'couleur' => '#0000FF', 'ordre' => 5],
            ['nom' => 'Bleue I', 'couleur' => '#4169E1', 'ordre' => 6],
            ['nom' => 'Verte', 'couleur' => '#008000', 'ordre' => 7],
            ['nom' => 'Verte I', 'couleur' => '#32CD32', 'ordre' => 8],
            ['nom' => 'Brune I', 'couleur' => '#8B4513', 'ordre' => 9],
            ['nom' => 'Brune II', 'couleur' => '#A0522D', 'ordre' => 10],
            ['nom' => 'Brune III', 'couleur' => '#CD853F', 'ordre' => 11],
            ['nom' => 'Noire (1er Dan) — Shodan', 'couleur' => '#000000', 'ordre' => 12],
            ['nom' => 'Noire (2e Dan) — Nidan', 'couleur' => '#000000', 'ordre' => 13],
            ['nom' => 'Noire (3e Dan) — Sandan', 'couleur' => '#000000', 'ordre' => 14],
            ['nom' => 'Noire (4e Dan) — Yondan', 'couleur' => '#000000', 'ordre' => 15],
            ['nom' => 'Noire (5e Dan) — Godan', 'couleur' => '#000000', 'ordre' => 16],
            ['nom' => 'Noire (6e Dan) — Rokudan', 'couleur' => '#000000', 'ordre' => 17],
            ['nom' => 'Noire (7e Dan) — Nanadan', 'couleur' => '#000000', 'ordre' => 18],
            ['nom' => 'Noire (8e Dan) — Hachidan', 'couleur' => '#000000', 'ordre' => 19],
            ['nom' => 'Noire (9e Dan) — Kudan', 'couleur' => '#000000', 'ordre' => 20],
            ['nom' => 'Noire (10e Dan) — Jūdan', 'couleur' => '#000000', 'ordre' => 21],
        ];

        foreach ($ceintures as $ceinture) {
            Ceinture::firstOrCreate(
                ['ordre' => $ceinture['ordre']],
                $ceinture
            );
        }

        $this->command->info('✅ 21 ceintures créées avec succès');
    }
}
