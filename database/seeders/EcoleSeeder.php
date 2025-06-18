<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ecole;

class EcoleSeeder extends Seeder
{
   public function run()
   {
       $ecoles = [
           [
               'nom' => 'Studios Unis Montréal Centre',
               'code' => 'MONTREAL',
               'adresse' => '123 Rue Principale',
               'ville' => 'Montréal', 
               'province' => 'QC',
               'code_postal' => 'H1A 1A1',
               'telephone' => '514-555-0100',
               'email' => 'montreal@studiosunisqc.com',
               'site_web' => 'https://montreal.studiosunisqc.com',
               'description' => 'École principale de Montréal, centre-ville',
               'active' => true
           ],
           [
               'nom' => 'Studios Unis Québec',
               'code' => 'QUEBEC',
               'adresse' => '456 Boulevard des Arts Martiaux',
               'ville' => 'Québec',
               'province' => 'QC', 
               'code_postal' => 'G1K 2K2',
               'telephone' => '418-555-0200',
               'email' => 'quebec@studiosunisqc.com',
               'site_web' => 'https://quebec.studiosunisqc.com',
               'description' => 'École de la capitale nationale',
               'active' => true
           ],
           [
               'nom' => 'Studios Unis Trois-Rivières',
               'code' => 'TROIRIV',
               'adresse' => '789 Rue du Karaté',
               'ville' => 'Trois-Rivières',
               'province' => 'QC',
               'code_postal' => 'G8A 3A3',
               'telephone' => '819-555-0300', 
               'email' => 'trois-rivieres@studiosunisqc.com',
               'site_web' => 'https://trois-rivieres.studiosunisqc.com',
               'description' => 'École de la Mauricie',
               'active' => true
           ],
           [
               'nom' => 'Studios Unis Sherbrooke',
               'code' => 'SHERB',
               'adresse' => '321 Avenue des Champions',
               'ville' => 'Sherbrooke',
               'province' => 'QC',
               'code_postal' => 'J1H 4H4',
               'telephone' => '819-555-0400',
               'email' => 'sherbrooke@studiosunisqc.com',
               'site_web' => 'https://sherbrooke.studiosunisqc.com', 
               'description' => 'École de l\'Estrie',
               'active' => true
           ],
           [
               'nom' => 'Studios Unis Gatineau',
               'code' => 'GATINEAU',
               'adresse' => '654 Rue de l\'Outaouais',
               'ville' => 'Gatineau',
               'province' => 'QC',
               'code_postal' => 'J8A 5A5',
               'telephone' => '819-555-0500',
               'email' => 'gatineau@studiosunisqc.com',
               'site_web' => 'https://gatineau.studiosunisqc.com',
               'description' => 'École de l\'Outaouais', 
               'active' => true
           ],
           [
               'nom' => 'Studios Unis St-Émile',
               'code' => 'STEMI',
               'adresse' => '987 Chemin du Dojo',
               'ville' => 'St-Émile',
               'province' => 'QC',
               'code_postal' => 'G3E 6E6',
               'telephone' => '418-555-0600',
               'email' => 'st-emile@studiosunisqc.com',
               'site_web' => 'https://st-emile.studiosunisqc.com',
               'description' => 'École de la région de Québec',
               'active' => true
           ],
           [
               'nom' => 'Studios Unis Chicoutimi',
               'code' => 'CHICOUT',
               'adresse' => '147 Rue du Saguenay',
               'ville' => 'Chicoutimi',
               'province' => 'QC',
               'code_postal' => 'G7H 7H7',
               'telephone' => '418-555-0700',
               'email' => 'chicoutimi@studiosunisqc.com',
               'site_web' => 'https://chicoutimi.studiosunisqc.com',
               'description' => 'École du Saguenay-Lac-Saint-Jean',
               'active' => true
           ],
           [
               'nom' => 'Studios Unis Rimouski',
               'code' => 'RIMOUSKI',
               'adresse' => '258 Avenue du Bas-Saint-Laurent',
               'ville' => 'Rimouski',
               'province' => 'QC',
               'code_postal' => 'G5L 8L8',
               'telephone' => '418-555-0800',
               'email' => 'rimouski@studiosunisqc.com',
               'site_web' => 'https://rimouski.studiosunisqc.com',
               'description' => 'École du Bas-Saint-Laurent',
               'active' => true
           ],
           [
               'nom' => 'Studios Unis Val-d\'Or',
               'code' => 'VALDOR',
               'adresse' => '369 Rue de l\'Abitibi',
               'ville' => 'Val-d\'Or',
               'province' => 'QC',
               'code_postal' => 'J9P 9P9',
               'telephone' => '819-555-0900',
               'email' => 'val-dor@studiosunisqc.com',
               'site_web' => 'https://val-dor.studiosunisqc.com',
               'description' => 'École de l\'Abitibi-Témiscamingue',
               'active' => true
           ],
           [
               'nom' => 'Studios Unis Sept-Îles',
               'code' => 'SEPTILES',
               'adresse' => '741 Boulevard de la Côte-Nord',
               'ville' => 'Sept-Îles',
               'province' => 'QC',
               'code_postal' => 'G4R 0R0',
               'telephone' => '418-555-1000',
               'email' => 'sept-iles@studiosunisqc.com',
               'site_web' => 'https://sept-iles.studiosunisqc.com',
               'description' => 'École de la Côte-Nord',
               'active' => true
           ],
           [
               'nom' => 'Studios Unis Longueuil',
               'code' => 'LONGUEUIL',
               'adresse' => '852 Chemin de Chambly',
               'ville' => 'Longueuil',
               'province' => 'QC',
               'code_postal' => 'J4H 1H1',
               'telephone' => '450-555-1100',
               'email' => 'longueuil@studiosunisqc.com',
               'site_web' => 'https://longueuil.studiosunisqc.com',
               'description' => 'École de la Montérégie',
               'active' => true
           ],
           [
               'nom' => 'Studios Unis Laval',
               'code' => 'LAVAL',
               'adresse' => '963 Boulevard Saint-Martin',
               'ville' => 'Laval',
               'province' => 'QC',
               'code_postal' => 'H7M 2M2',
               'telephone' => '450-555-1200',
               'email' => 'laval@studiosunisqc.com',
               'site_web' => 'https://laval.studiosunisqc.com',
               'description' => 'École de Laval',
               'active' => true
           ],
           [
               'nom' => 'Studios Unis Drummondville',
               'code' => 'DRUMMOND',
               'adresse' => '174 Rue Lindsay',
               'ville' => 'Drummondville',
               'province' => 'QC',
               'code_postal' => 'J2B 3B3',
               'telephone' => '819-555-1300',
               'email' => 'drummondville@studiosunisqc.com',
               'site_web' => 'https://drummondville.studiosunisqc.com',
               'description' => 'École du Centre-du-Québec',
               'active' => true
           ],
           [
               'nom' => 'Studios Unis Granby',
               'code' => 'GRANBY',
               'adresse' => '285 Rue Principale',
               'ville' => 'Granby',
               'province' => 'QC',
               'code_postal' => 'J2G 4G4',
               'telephone' => '450-555-1400',
               'email' => 'granby@studiosunisqc.com',
               'site_web' => 'https://granby.studiosunisqc.com',
               'description' => 'École de la Haute-Yamaska',
               'active' => true
           ],
           [
               'nom' => 'Studios Unis Saint-Hyacinthe',
               'code' => 'STHYAC',
               'adresse' => '396 Avenue des Cascades',
               'ville' => 'Saint-Hyacinthe',
               'province' => 'QC',
               'code_postal' => 'J2S 5S5',
               'telephone' => '450-555-1500',
               'email' => 'saint-hyacinthe@studiosunisqc.com',
               'site_web' => 'https://saint-hyacinthe.studiosunisqc.com',
               'description' => 'École de la région maskoutaine',
               'active' => true
           ],
           [
               'nom' => 'Studios Unis Joliette',
               'code' => 'JOLIETTE',
               'adresse' => '507 Boulevard Base-de-Roc',
               'ville' => 'Joliette',
               'province' => 'QC',
               'code_postal' => 'J6E 6E6',
               'telephone' => '450-555-1600',
               'email' => 'joliette@studiosunisqc.com',
               'site_web' => 'https://joliette.studiosunisqc.com',
               'description' => 'École de Lanaudière',
               'active' => true
           ],
           [
               'nom' => 'Studios Unis Victoriaville',
               'code' => 'VICTORIA',
               'adresse' => '618 Boulevard des Bois-Francs',
               'ville' => 'Victoriaville',
               'province' => 'QC',
               'code_postal' => 'G6P 7P7',
               'telephone' => '819-555-1700',
               'email' => 'victoriaville@studiosunisqc.com',
               'site_web' => 'https://victoriaville.studiosunisqc.com',
               'description' => 'École des Bois-Francs',
               'active' => true
           ],
           [
               'nom' => 'Studios Unis Alma',
               'code' => 'ALMA',
               'adresse' => '729 Rue Collard',
               'ville' => 'Alma',
               'province' => 'QC',
               'code_postal' => 'G8B 8B8',
               'telephone' => '418-555-1800',
               'email' => 'alma@studiosunisqc.com',
               'site_web' => 'https://alma.studiosunisqc.com',
               'description' => 'École du Lac-Saint-Jean',
               'active' => true
           ],
           [
               'nom' => 'Studios Unis Thetford Mines',
               'code' => 'THETFORD',
               'adresse' => '830 Boulevard Frontenac',
               'ville' => 'Thetford Mines',
               'province' => 'QC',
               'code_postal' => 'G6G 9G9',
               'telephone' => '418-555-1900',
               'email' => 'thetford@studiosunisqc.com',
               'site_web' => 'https://thetford.studiosunisqc.com',
               'description' => 'École des Appalaches',
               'active' => true
           ],
           [
               'nom' => 'Studios Unis Sorel-Tracy',
               'code' => 'SOREL',
               'adresse' => '941 Boulevard Fiset',
               'ville' => 'Sorel-Tracy',
               'province' => 'QC',
               'code_postal' => 'J3P 0P0',
               'telephone' => '450-555-2000',
               'email' => 'sorel-tracy@studiosunisqc.com',
               'site_web' => 'https://sorel-tracy.studiosunisqc.com',
               'description' => 'École de Pierre-De Saurel',
               'active' => true
           ],
           [
               'nom' => 'Studios Unis Matane',
               'code' => 'MATANE',
               'adresse' => '152 Avenue du Phare',
               'ville' => 'Matane',
               'province' => 'QC',
               'code_postal' => 'G4W 1W1',
               'telephone' => '418-555-2100',
               'email' => 'matane@studiosunisqc.com',
               'site_web' => 'https://matane.studiosunisqc.com',
               'description' => 'École de la Matanie',
               'active' => true
           ],
           [
               'nom' => 'Studios Unis Baie-Comeau',
               'code' => 'BAIECOM',
               'adresse' => '263 Boulevard Laflèche',
               'ville' => 'Baie-Comeau',
               'province' => 'QC',
               'code_postal' => 'G5C 2C2',
               'telephone' => '418-555-2200',
               'email' => 'baie-comeau@studiosunisqc.com',
               'site_web' => 'https://baie-comeau.studiosunisqc.com',
               'description' => 'École de Manicouagan',
               'active' => true
           ]
       ];

       foreach ($ecoles as $ecole) {
           Ecole::create($ecole);
       }
   }
}
