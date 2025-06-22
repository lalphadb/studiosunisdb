<?php
namespace Database\Seeders;

use App\Models\Ecole;
use Illuminate\Database\Seeder;

class EcoleSeeder extends Seeder
{
    public function run()
    {
        $ecoles = [
            ['nom' => 'École St-Émile', 'code' => 'STE', 'adresse' => '123 Rue Principale', 'ville' => 'St-Émile', 'province' => 'QC', 'code_postal' => 'G0A 4A0', 'telephone' => '418-123-4567', 'email' => 'contact@st-emile.ca', 'active' => true],
            ['nom' => 'Dojo Alpha Québec', 'code' => 'DAQ', 'adresse' => '456 Boulevard Charest', 'ville' => 'Québec', 'province' => 'QC', 'code_postal' => 'G1K 1K1', 'telephone' => '418-987-6543', 'email' => 'info@alphadojo.ca', 'active' => true],
            ['nom' => 'Centre Martial Sainte-Foy', 'code' => 'CMSF', 'adresse' => '789 Avenue Maguire', 'ville' => 'Sainte-Foy', 'province' => 'QC', 'code_postal' => 'G1X 1X1', 'telephone' => '418-555-0123', 'email' => 'admin@centremartialsf.ca', 'active' => true],
            ['nom' => 'Karaté Beauport', 'code' => 'KB', 'adresse' => '321 Avenue Royale', 'ville' => 'Beauport', 'province' => 'QC', 'code_postal' => 'G1E 1E1', 'telephone' => '418-666-7777', 'email' => 'karate@beauport.ca', 'active' => true],
            ['nom' => 'Arts Martiaux Lévis', 'code' => 'AML', 'adresse' => '654 Rue de la Rive-Sud', 'ville' => 'Lévis', 'province' => 'QC', 'code_postal' => 'G6V 6V6', 'telephone' => '418-777-8888', 'email' => 'contact@amlevis.ca', 'active' => true],
            ['nom' => 'Dojo Montréal Centre', 'code' => 'DMC', 'adresse' => '1234 Rue Sainte-Catherine', 'ville' => 'Montréal', 'province' => 'QC', 'code_postal' => 'H3G 1P1', 'telephone' => '514-111-2222', 'email' => 'info@dojomtl.ca', 'active' => true],
            ['nom' => 'Karaté Plateau Mont-Royal', 'code' => 'KPMR', 'adresse' => '567 Avenue du Mont-Royal', 'ville' => 'Montréal', 'province' => 'QC', 'code_postal' => 'H2T 1T1', 'telephone' => '514-333-4444', 'email' => 'plateau@karate.ca', 'active' => true],
            ['nom' => 'Centre Martial Verdun', 'code' => 'CMV', 'adresse' => '890 Boulevard LaSalle', 'ville' => 'Verdun', 'province' => 'QC', 'code_postal' => 'H4G 2A1', 'telephone' => '514-555-6666', 'email' => 'verdun@martial.ca', 'active' => true],
            ['nom' => 'Arts Martiaux Laval', 'code' => 'AML2', 'adresse' => '1111 Boulevard Saint-Martin', 'ville' => 'Laval', 'province' => 'QC', 'code_postal' => 'H7S 1N1', 'telephone' => '450-777-9999', 'email' => 'laval@artsmartiaux.ca', 'active' => true],
            ['nom' => 'Dojo Longueuil', 'code' => 'DL', 'adresse' => '2222 Chemin de Chambly', 'ville' => 'Longueuil', 'province' => 'QC', 'code_postal' => 'J4L 1M1', 'telephone' => '450-888-1111', 'email' => 'longueuil@dojo.ca', 'active' => true],
            ['nom' => 'Karaté Gatineau', 'code' => 'KG', 'adresse' => '333 Boulevard Maloney', 'ville' => 'Gatineau', 'province' => 'QC', 'code_postal' => 'J8T 3R1', 'telephone' => '819-222-3333', 'email' => 'gatineau@karate.ca', 'active' => true],
            ['nom' => 'Centre Martial Hull', 'code' => 'CMH', 'adresse' => '444 Rue Laurier', 'ville' => 'Hull', 'province' => 'QC', 'code_postal' => 'J8Y 3Z1', 'telephone' => '819-444-5555', 'email' => 'hull@martial.ca', 'active' => true],
            ['nom' => 'Dojo Sherbrooke', 'code' => 'DS', 'adresse' => '555 Rue King Ouest', 'ville' => 'Sherbrooke', 'province' => 'QC', 'code_postal' => 'J1H 1S1', 'telephone' => '819-666-7777', 'email' => 'sherbrooke@dojo.ca', 'active' => true],
            ['nom' => 'Arts Martiaux Estrie', 'code' => 'AME', 'adresse' => '666 Boulevard Portland', 'ville' => 'Sherbrooke', 'province' => 'QC', 'code_postal' => 'J1L 2Y1', 'telephone' => '819-888-9999', 'email' => 'estrie@artsmartiaux.ca', 'active' => true],
            ['nom' => 'Karaté Trois-Rivières', 'code' => 'KTR', 'adresse' => '777 Rue des Forges', 'ville' => 'Trois-Rivières', 'province' => 'QC', 'code_postal' => 'G9A 2G1', 'telephone' => '819-111-2222', 'email' => 'troisrivieres@karate.ca', 'active' => true],
            ['nom' => 'Centre Martial Mauricie', 'code' => 'CMM', 'adresse' => '888 Boulevard des Récollets', 'ville' => 'Trois-Rivières', 'province' => 'QC', 'code_postal' => 'G8Z 3Z1', 'telephone' => '819-333-4444', 'email' => 'mauricie@martial.ca', 'active' => true],
            ['nom' => 'Dojo Saguenay', 'code' => 'DSG', 'adresse' => '999 Rue Racine', 'ville' => 'Chicoutimi', 'province' => 'QC', 'code_postal' => 'G7H 1S1', 'telephone' => '418-555-7777', 'email' => 'saguenay@dojo.ca', 'active' => true],
            ['nom' => 'Karaté Jonquière', 'code' => 'KJ', 'adresse' => '101 Boulevard Harvey', 'ville' => 'Jonquière', 'province' => 'QC', 'code_postal' => 'G7X 8L1', 'telephone' => '418-777-8888', 'email' => 'jonquiere@karate.ca', 'active' => true],
            ['nom' => 'Arts Martiaux Rimouski', 'code' => 'AMR', 'adresse' => '202 Rue de la Cathédrale', 'ville' => 'Rimouski', 'province' => 'QC', 'code_postal' => 'G5L 5J1', 'telephone' => '418-999-1111', 'email' => 'rimouski@artsmartiaux.ca', 'active' => true],
            ['nom' => 'Centre Martial Abitibi', 'code' => 'CMA', 'adresse' => '303 Avenue Principale', 'ville' => 'Rouyn-Noranda', 'province' => 'QC', 'code_postal' => 'J9X 4P1', 'telephone' => '819-222-4444', 'email' => 'abitibi@martial.ca', 'active' => true],
            ['nom' => 'Dojo Côte-Nord', 'code' => 'DCN', 'adresse' => '404 Boulevard Laure', 'ville' => 'Sept-Îles', 'province' => 'QC', 'code_postal' => 'G4R 1X1', 'telephone' => '418-444-6666', 'email' => 'cotenord@dojo.ca', 'active' => true],
            ['nom' => 'Karaté Gaspésie', 'code' => 'KGS', 'adresse' => '505 Boulevard de Gaspé', 'ville' => 'Gaspé', 'province' => 'QC', 'code_postal' => 'G4X 1A1', 'telephone' => '418-666-8888', 'email' => 'gaspesie@karate.ca', 'active' => true],
        ];

        foreach ($ecoles as $ecole) {
            Ecole::firstOrCreate(['nom' => $ecole['nom']], $ecole);
        }
        
        $this->command->info('✅ 22 écoles créées!');
    }
}
