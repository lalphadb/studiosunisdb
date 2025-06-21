<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ecole;

class EcoleSeeder extends Seeder
{
    public function run(): void
    {
        $ecoles = [
            ['code' => 'QBC', 'nom' => 'Studios Unis Québec', 'ville' => 'Québec'],
            ['code' => 'STE', 'nom' => 'Studios Unis St-Émile', 'ville' => 'St-Émile'],
            ['code' => 'MTL', 'nom' => 'Studios Unis Montréal Centre', 'ville' => 'Montréal'],
        ];

        foreach ($ecoles as $ecole) {
            Ecole::firstOrCreate(
                ['code' => $ecole['code']],
                [
                    'nom' => $ecole['nom'],
                    'code' => $ecole['code'],
                    'adresse' => '123 Rue Test',
                    'ville' => $ecole['ville'],
                    'province' => 'QC',
                    'code_postal' => 'G1A 1A1',
                    'active' => true,
                ]
            );
        }

        $this->command->info('✅ Écoles de base créées');
    }
}
