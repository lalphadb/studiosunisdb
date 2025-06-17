<?php

namespace Database\Seeders;

use App\Models\Ecole;
use App\Models\Membre;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        $ecoles = Ecole::take(3)->get();

        if ($ecoles->isEmpty()) {
            echo "❌ Aucune école trouvée!\n";

            return;
        }

        // Vérifier colonnes disponibles
        $columns = Schema::getColumnListing('membres');
        echo 'Colonnes disponibles: '.implode(', ', $columns)."\n";

        foreach ($ecoles as $ecole) {
            for ($i = 1; $i <= 3; $i++) {
                $data = [
                    'ecole_id' => $ecole->id,
                    'prenom' => 'Membre'.$i,
                    'nom' => 'Test'.$ecole->id,
                    'date_inscription' => now()->subDays(rand(1, 365)),
                ];

                // Ajouter colonnes optionnelles si elles existent
                if (in_array('statut', $columns)) {
                    $data['statut'] = 'actif';
                }
                if (in_array('type_membre', $columns)) {
                    $data['type_membre'] = 'adulte';
                }
                if (in_array('niveau_experience', $columns)) {
                    $data['niveau_experience'] = 'debutant';
                }

                Membre::create($data);
            }
        }

        echo '✅ '.($ecoles->count() * 3)." membres de test créés!\n";
    }
}
