<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ecole;

class EcoleSeeder extends Seeder
{
    public function run()
    {
        $nouvelles_ecoles = [
            ['nom' => 'Studios Unis Montréal Centre', 'ville' => 'Montréal', 'directeur' => 'Pierre Dubois'],
            ['nom' => 'Studios Unis Sherbrooke', 'ville' => 'Sherbrooke', 'directeur' => 'Claude Bélanger'],
            ['nom' => 'Studios Unis Gatineau', 'ville' => 'Gatineau', 'directeur' => 'André Morin'],
            ['nom' => 'Studios Unis Trois-Rivières', 'ville' => 'Trois-Rivières', 'directeur' => 'Robert Lafleur'],
            ['nom' => 'Studios Unis Chicoutimi', 'ville' => 'Saguenay', 'directeur' => 'Véronique Simard'],
            ['nom' => 'Studios Unis Rimouski', 'ville' => 'Rimouski', 'directeur' => 'Martine Bérubé'],
            ['nom' => 'Studios Unis Hull', 'ville' => 'Gatineau', 'directeur' => 'Nathalie Côté'],
            ['nom' => 'Studios Unis Lévis', 'ville' => 'Lévis', 'directeur' => 'Isabelle Gagnon'],
            ['nom' => 'Studios Unis Longueuil', 'ville' => 'Longueuil', 'directeur' => 'Sophie Bouchard'],
            ['nom' => 'Studios Unis Sainte-Foy', 'ville' => 'Québec', 'directeur' => 'Martin Roy'],
            ['nom' => 'Studios Unis Brossard', 'ville' => 'Brossard', 'directeur' => 'Michel Leblanc'],
            ['nom' => 'Studios Unis Magog', 'ville' => 'Magog', 'directeur' => 'Sylvie Paradis'],
            ['nom' => 'Studios Unis Granby', 'ville' => 'Granby', 'directeur' => 'Daniel Poulin'],
            ['nom' => 'Studios Unis Shawinigan', 'ville' => 'Shawinigan', 'directeur' => 'Lucie Pellerin'],
            ['nom' => 'Studios Unis Jonquière', 'ville' => 'Saguenay', 'directeur' => 'Éric Bouchard'],
            ['nom' => 'Studios Unis Rouyn-Noranda', 'ville' => 'Rouyn-Noranda', 'directeur' => 'Sylvain Trottier'],
            ['nom' => 'Studios Unis Val-d\'Or', 'ville' => 'Val-d\'Or', 'directeur' => 'Stéphane Lacroix'],
            ['nom' => 'Studios Unis Sept-Îles', 'ville' => 'Sept-Îles', 'directeur' => 'Julie Tremblay']
        ];

        $compteur = 0;
        foreach ($nouvelles_ecoles as $ecole) {
            if (!Ecole::where('nom', $ecole['nom'])->exists()) {
                Ecole::create([
                    'nom' => $ecole['nom'],
                    'adresse' => '123 Rue Principale',
                    'ville' => $ecole['ville'],
                    'province' => 'QC',
                    'code_postal' => 'G1A 1A1',
                    'telephone' => '418-555-0100',
                    'email' => strtolower(str_replace([' ', '\''], ['.', ''], $ecole['nom'])) . '@studiosunisqc.com',
                    'directeur' => $ecole['directeur'],
                    'capacite_max' => 75,
                    'statut' => 'actif'
                ]);
                $compteur++;
            }
        }

        $this->command->info("Ajouté {$compteur} nouvelles écoles. Total: " . Ecole::count());
    }
}
