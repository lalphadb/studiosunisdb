#!/bin/bash

echo "🌱 CRÉATION SEEDERS STUDIOSDB v5 PRO"
echo "===================================="

cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro

# Seeder Ceintures Karaté
php artisan make:seeder CeinturesSeeder

cat > database/seeders/CeinturesSeeder.php << 'SEEDER_CEINTURES'
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CeinturesSeeder extends Seeder
{
    public function run(): void
    {
        $ceintures = [
            [
                'nom' => 'Blanche',
                'nom_en' => 'White',
                'couleur_hex' => '#FFFFFF',
                'ordre' => 1,
                'niveau_difficulte' => 1,
                'est_dan' => false,
                'duree_minimum_mois' => 0,
                'presences_minimum' => 0,
                'age_minimum' => 5,
                'cout_examen' => 0.00,
                'description' => 'Ceinture de départ - Initiation aux bases du karaté',
                'active' => true,
                'visible_membres' => true,
            ],
            [
                'nom' => 'Jaune',
                'nom_en' => 'Yellow', 
                'couleur_hex' => '#FFE135',
                'ordre' => 2,
                'niveau_difficulte' => 2,
                'duree_minimum_mois' => 3,
                'presences_minimum' => 24,
                'cout_examen' => 35.00,
                'description' => 'Première progression - Techniques de base',
                'active' => true,
            ],
            [
                'nom' => 'Orange',
                'nom_en' => 'Orange',
                'couleur_hex' => '#FF8C00',
                'ordre' => 3,
                'niveau_difficulte' => 3,
                'duree_minimum_mois' => 4,
                'presences_minimum' => 32,
                'cout_examen' => 40.00,
                'description' => 'Développement coordination et équilibre',
                'active' => true,
            ],
            [
                'nom' => 'Verte',
                'nom_en' => 'Green',
                'couleur_hex' => '#00B04F',
                'ordre' => 4,
                'niveau_difficulte' => 4,
                'duree_minimum_mois' => 5,
                'presences_minimum' => 40,
                'cout_examen' => 45.00,
                'description' => 'Maîtrise techniques intermédiaires',
                'active' => true,
            ],
            [
                'nom' => 'Bleue',
                'nom_en' => 'Blue',
                'couleur_hex' => '#0066CC',
                'ordre' => 5,
                'niveau_difficulte' => 5,
                'duree_minimum_mois' => 6,
                'presences_minimum' => 48,
                'cout_examen' => 50.00,
                'description' => 'Techniques avancées et premier kata',
                'active' => true,
            ],
            [
                'nom' => 'Marron',
                'nom_en' => 'Brown',
                'couleur_hex' => '#8B4513',
                'ordre' => 6,
                'niveau_difficulte' => 7,
                'duree_minimum_mois' => 8,
                'presences_minimum' => 64,
                'cout_examen' => 75.00,
                'description' => 'Préparation ceinture noire - Expertise technique',
                'active' => true,
            ],
            [
                'nom' => 'Noire 1er Dan',
                'nom_en' => 'Black 1st Dan',
                'couleur_hex' => '#000000',
                'ordre' => 10,
                'niveau_difficulte' => 10,
                'est_dan' => true,
                'dan_niveau' => 1,
                'duree_minimum_mois' => 12,
                'presences_minimum' => 96,
                'cout_examen' => 150.00,
                'description' => 'Maîtrise complète - Début enseignement',
                'active' => true,
            ],
        ];

        foreach ($ceintures as $ceinture) {
            DB::table('ceintures')->insert(array_merge($ceinture, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
SEEDER_CEINTURES

echo "✅ Seeder Ceintures créé"

# Seeder Utilisateurs Test
php artisan make:seeder TestUsersSeeder

cat > database/seeders/TestUsersSeeder.php << 'SEEDER_USERS'
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class TestUsersSeeder extends Seeder
{
    public function run(): void
    {
        // Admin principal
        User::create([
            'name' => 'Louis Administrateur',
            'email' => 'louis@4lb.ca',
            'password' => Hash::make('StudiosDB2025!'),
            'email_verified_at' => now(),
        ]);

        // Instructeur test
        User::create([
            'name' => 'Sensei Yamamoto',
            'email' => 'instructeur@studiosdb.local',
            'password' => Hash::make('karate2025'),
            'email_verified_at' => now(),
        ]);

        // Membre test
        User::create([
            'name' => 'Élève Débutant',
            'email' => 'eleve@studiosdb.local', 
            'password' => Hash::make('student2025'),
            'email_verified_at' => now(),
        ]);
    }
}
SEEDER_USERS

echo "✅ Seeder Users créé"

# Mise à jour DatabaseSeeder
cat > database/seeders/DatabaseSeeder.php << 'DATABASE_SEEDER'
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CeinturesSeeder::class,
            TestUsersSeeder::class,
        ]);
    }
}
DATABASE_SEEDER

echo "✅ DatabaseSeeder mis à jour"
echo ""
echo "🌱 Pour lancer les seeders:"
echo "php artisan db:seed"
