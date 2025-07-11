<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ecole;

class EcolesSeeder extends Seeder
{
    public function run(): void
    {
        $ecoles = [
            [
                'nom' => 'StudiosUnis Siège Social',
                'code' => 'STU-001', // AJOUT DU CODE MANQUANT
                'adresse' => '123 Rue Principale',
                'ville' => 'Montréal',
                'province' => 'QC',
                'code_postal' => 'H1A 1A1',
                'telephone' => '514-555-0001',
                'email' => 'info@studiosdb.ca',
                'actif' => true,
            ],
            [
                'nom' => 'École St-Émile',
                'code' => 'STU-002',
                'adresse' => '123 Rue Principale',
                'ville' => 'St-Émile',
                'province' => 'QC',
                'code_postal' => 'G0A 4A0',
                'telephone' => '418-123-4567',
                'email' => 'info@karate-stemile.ca',
                'actif' => true,
            ],
            [
                'nom' => 'Académie Sainte-Foy',
                'code' => 'STU-003',
                'adresse' => '456 Boulevard Laurier',
                'ville' => 'Sainte-Foy',
                'province' => 'QC',
                'code_postal' => 'G1V 2L1',
                'telephone' => '418-234-5678',
                'email' => 'contact@academie-saintefoy.ca',
                'actif' => true,
            ],
            // Ajouter les autres écoles avec des codes uniques...
        ];

        $counter = 1;
        foreach ($ecoles as &$ecole) {
            if (!isset($ecole['code'])) {
                $ecole['code'] = 'STU-' . str_pad($counter, 3, '0', STR_PAD_LEFT);
                $counter++;
            }
            
            Ecole::updateOrCreate(
                ['code' => $ecole['code']],
                $ecole
            );
        }
        
        $this->command->info('✅ ' . count($ecoles) . ' écoles créées');
    }
}
