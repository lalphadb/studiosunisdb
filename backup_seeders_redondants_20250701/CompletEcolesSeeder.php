<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ecole;

class CompletEcolesSeeder extends Seeder
{
    public function run(): void
    {
        $ecoles = [
            // Déjà existantes (vérifier)
            ['nom' => 'Studios Unis Montréal Centre', 'code' => 'MTL', 'ville' => 'Montréal'],
            ['nom' => 'Studios Unis Québec', 'code' => 'QBC', 'ville' => 'Québec'],
            ['nom' => 'Studios Unis St-Émile', 'code' => 'STE', 'ville' => 'St-Émile'],
            
            // Nouvelles écoles à ajouter
            ['nom' => 'Studios Unis Trois-Rivières', 'code' => 'TR', 'ville' => 'Trois-Rivières'],
            ['nom' => 'Studios Unis Sherbrooke', 'code' => 'SHB', 'ville' => 'Sherbrooke'],
            ['nom' => 'Studios Unis Gatineau', 'code' => 'GAT', 'ville' => 'Gatineau'],
            ['nom' => 'Studios Unis Laval', 'code' => 'LAV', 'ville' => 'Laval'],
            ['nom' => 'Studios Unis Longueuil', 'code' => 'LNG', 'ville' => 'Longueuil'],
            ['nom' => 'Studios Unis Saguenay', 'code' => 'SAG', 'ville' => 'Saguenay'],
            ['nom' => 'Studios Unis Rimouski', 'code' => 'RIM', 'ville' => 'Rimouski'],
            ['nom' => 'Studios Unis Val-d\'Or', 'code' => 'VAL', 'ville' => 'Val-d\'Or'],
            ['nom' => 'Studios Unis Chicoutimi', 'code' => 'CHI', 'ville' => 'Chicoutimi'],
            ['nom' => 'Studios Unis Drummondville', 'code' => 'DRU', 'ville' => 'Drummondville'],
            ['nom' => 'Studios Unis Blainville', 'code' => 'BLV', 'ville' => 'Blainville'],
            ['nom' => 'Studios Unis Lac-Mégantic', 'code' => 'LAC', 'ville' => 'Lac-Mégantic'],
            ['nom' => 'Studios Unis Athabaska', 'code' => 'ATB', 'ville' => 'Athabaska'],
            ['nom' => 'Studios Unis Saint-Hyacinthe', 'code' => 'STH', 'ville' => 'Saint-Hyacinthe'],
            ['nom' => 'Studios Unis Bromont', 'code' => 'BRO', 'ville' => 'Bromont'],
            ['nom' => 'Studios Unis Saint-Jérôme', 'code' => 'STJ', 'ville' => 'Saint-Jérôme'],
            ['nom' => 'Studios Unis Magog', 'code' => 'MAG', 'ville' => 'Magog'],
            ['nom' => 'Studios Unis Roberval', 'code' => 'ROY', 'ville' => 'Roberval'],
            ['nom' => 'Studios Unis Anjou', 'code' => 'ANJ', 'ville' => 'Anjou'],
        ];

        foreach ($ecoles as $ecoleData) {
            Ecole::firstOrCreate(
                ['code' => $ecoleData['code']], // Rechercher par code unique
                [
                    'nom' => $ecoleData['nom'],
                    'ville' => $ecoleData['ville'],
                    'province' => 'QC',
                    'adresse' => '123 Rue Principal', // Adresse générique
                    'code_postal' => 'G0A 0A0', // Code postal générique
                    'active' => true,
                    'description' => 'École de karaté des Studios Unis du Québec',
                ]
            );
        }
        
        $this->command->info('✅ 22 écoles Studios Unis créées/vérifiées');
    }
}
