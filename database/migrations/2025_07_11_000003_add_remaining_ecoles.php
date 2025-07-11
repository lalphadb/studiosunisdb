<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Ecole;

return new class extends Migration
{
    /**
     * Ajouter les 21 écoles manquantes de StudiosUnisDB
     */
    public function up(): void
    {
        $ecoles = [
            [
                'nom' => 'Ancienne-Lorette',
                'code' => 'ANL',
                'adresse' => '7050 boul. Hamel Ouest, suite 80',
                'ville' => 'Ancienne-Lorette',
                'code_postal' => 'G2G 1B5',
                'telephone' => null,
                'email' => null,
                'actif' => true
            ],
            [
                'nom' => 'Beauce',
                'code' => 'BEA',
                'adresse' => '17118 boul. Lacroix, Suite 2',
                'ville' => 'St-Georges-de-Beauce',
                'code_postal' => 'G5Y 8G9',
                'telephone' => null,
                'email' => null,
                'actif' => true
            ],
            [
                'nom' => 'Beauport',
                'code' => 'BPT',
                'adresse' => '2204 boul. Louis-XIV',
                'ville' => 'Beauport',
                'code_postal' => 'G1C 1A2',
                'telephone' => null,
                'email' => null,
                'actif' => true
            ],
            [
                'nom' => 'Charlesbourg',
                'code' => 'CHA',
                'adresse' => '13061, Boul. Henri-Bourassa',
                'ville' => 'Charlesbourg',
                'code_postal' => 'G1G 3Y6',
                'telephone' => null,
                'email' => null,
                'actif' => true
            ],
            [
                'nom' => 'Chicoutimi',
                'code' => 'CHI',
                'adresse' => null,
                'ville' => 'Chicoutimi',
                'code_postal' => null,
                'telephone' => null,
                'email' => 'chicoutimi@studiosunis.com',
                'actif' => true
            ],
            [
                'nom' => 'Côte-de-Beaupré',
                'code' => 'CDB',
                'adresse' => '6218 Boul St-Anne, suite 102',
                'ville' => 'L\'Ange-Gardien',
                'code_postal' => 'G0A 2K0',
                'telephone' => null,
                'email' => null,
                'actif' => true
            ],
            [
                'nom' => 'Dolbeau',
                'code' => 'DOL',
                'adresse' => '1350 bl. Wallberg',
                'ville' => 'Dolbeau-Mistassini',
                'code_postal' => 'G8L 1H1',
                'telephone' => null,
                'email' => null,
                'actif' => true
            ],
            [
                'nom' => 'Donnacona',
                'code' => 'DON',
                'adresse' => '120 Armand Bombardier, local 260',
                'ville' => 'Donnacona',
                'code_postal' => 'G3M 1V3',
                'telephone' => null,
                'email' => null,
                'actif' => true
            ],
            [
                'nom' => 'Duberger',
                'code' => 'DUB',
                'adresse' => '2300 Père-Lelièvre',
                'ville' => 'Québec',
                'code_postal' => 'G1P 2X5',
                'telephone' => null,
                'email' => null,
                'actif' => true
            ],
            [
                'nom' => 'Lac St-Charles (NDL)',
                'code' => 'LSC',
                'adresse' => '876, Jacques Bédard',
                'ville' => 'Lac St-Charles',
                'code_postal' => 'G2N 1E3',
                'telephone' => null,
                'email' => null,
                'actif' => true
            ],
            [
                'nom' => 'Limoilou',
                'code' => 'LIM',
                'adresse' => '320, 1ere Rue',
                'ville' => 'Québec',
                'code_postal' => 'G1L 2E9',
                'telephone' => null,
                'email' => null,
                'actif' => true
            ],
            [
                'nom' => 'Lotbinière (St-Apollinaire)',
                'code' => 'LOT',
                'adresse' => '425-A, route 273',
                'ville' => 'Saint-Apollinaire',
                'code_postal' => 'G0S 2E0',
                'telephone' => null,
                'email' => null,
                'actif' => true
            ],
            [
                'nom' => 'Montmagny',
                'code' => 'MON',
                'adresse' => '139 Rue St-Jean Baptiste E',
                'ville' => 'Montmagny',
                'code_postal' => 'G5V 1K3',
                'telephone' => null,
                'email' => null,
                'actif' => true
            ],
            [
                'nom' => 'Pont-Rouge',
                'code' => 'PRO',
                'adresse' => '145 Rue Dupont',
                'ville' => 'Pont-Rouge',
                'code_postal' => 'G3H 1N4',
                'telephone' => null,
                'email' => null,
                'actif' => true
            ],
            [
                'nom' => 'Québec (Fleur de Lys)',
                'code' => 'QFL',
                'adresse' => '1985 Fleurs de Lys',
                'ville' => 'Québec',
                'code_postal' => 'G1M 2W7',
                'telephone' => null,
                'email' => null,
                'actif' => true
            ],
            [
                'nom' => 'Shannon',
                'code' => 'SHA',
                'adresse' => '40, rue de la Piste',
                'ville' => 'Shannon',
                'code_postal' => 'G3S 0V2',
                'telephone' => null,
                'email' => null,
                'actif' => true
            ],
            [
                'nom' => 'St-Étienne-de-Lauzon',
                'code' => 'SEL',
                'adresse' => '2760 route Lagueux',
                'ville' => 'Lévis',
                'code_postal' => 'G6J 1A2',
                'telephone' => null,
                'email' => null,
                'actif' => true
            ],
            [
                'nom' => 'St-Nicolas',
                'code' => 'SNI',
                'adresse' => '896 route des Rivières, Suite #103',
                'ville' => 'Lévis (St-Nicolas)',
                'code_postal' => 'G7A 2V1',
                'telephone' => null,
                'email' => null,
                'actif' => true
            ],
            [
                'nom' => 'Sainte-Foy',
                'code' => 'SFO',
                'adresse' => '3111, chemin St-Louis, local 102',
                'ville' => 'Québec',
                'code_postal' => 'G1W 4R4',
                'telephone' => null,
                'email' => null,
                'actif' => true
            ],
            [
                'nom' => 'Val-Bélair',
                'code' => 'VBE',
                'adresse' => '1531 rue de l\'Innovation',
                'ville' => 'Val-Bélair',
                'code_postal' => 'G3K 2P9',
                'telephone' => null,
                'email' => null,
                'actif' => true
            ],
            [
                'nom' => 'Vanier',
                'code' => 'VAN',
                'adresse' => '425 rue soumande, local 70B',
                'ville' => 'Québec',
                'code_postal' => 'G1M 1A9',
                'telephone' => null,
                'email' => null,
                'actif' => true
            ]
        ];

        foreach ($ecoles as $ecole) {
            // Vérifier si l'école n'existe pas déjà (par code)
            if (!Ecole::where('code', $ecole['code'])->exists()) {
                Ecole::create($ecole);
            }
        }
    }

    /**
     * Supprimer les écoles ajoutées (rollback)
     */
    public function down(): void
    {
        $codes = [
            'ANL', 'BEA', 'BPT', 'CHA', 'CHI', 'CDB', 'DOL', 'DON', 'DUB', 'LSC',
            'LIM', 'LOT', 'MON', 'PRO', 'QFL', 'SHA', 'SEL', 'SNI', 'SFO', 'VBE', 'VAN'
        ];

        Ecole::whereIn('code', $codes)->delete();
    }
};
