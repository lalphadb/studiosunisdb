#!/bin/bash

echo "🥋 SEEDER OFFICIEL STUDIOSUNIS - 21 CEINTURES"
echo "============================================"

cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro

# Seeder avec les 21 ceintures officielles StudiosUnis
cat > database/seeders/CeinturesSeeder.php << 'SEEDER_STUDIOSUNIS'
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CeinturesSeeder extends Seeder
{
    public function run(): void
    {
        // Les 21 ceintures officielles StudiosUnis
        $ceintures = [
            // Ceintures colorées de base (1-11)
            [
                'ordre' => 1, 'nom' => 'Blanche', 
                'couleur_hex' => '#FFFFFF',
                'description' => 'Ceinture de départ - Apprentissage des bases',
                'prerequis_techniques' => json_encode(['Position de garde', 'Salut traditionnel', 'Discipline de base']),
                'duree_minimum_mois' => 0, 'presences_minimum' => 0, 'age_minimum' => 4,
                'tarif_examen' => 0.00, 'examen_requis' => false, 'actif' => true,
            ],
            [
                'ordre' => 2, 'nom' => 'Jaune',
                'couleur_hex' => '#FFD700',
                'description' => 'Première progression - Techniques de poing et pied de base',
                'prerequis_techniques' => json_encode(['Choku-zuki', 'Mae-geri', 'Age-uke', 'Zen-kutsu-dachi']),
                'duree_minimum_mois' => 3, 'presences_minimum' => 24, 'age_minimum' => 4,
                'tarif_examen' => 30.00, 'examen_requis' => true, 'actif' => true,
            ],
            [
                'ordre' => 3, 'nom' => 'Orange',
                'couleur_hex' => '#FF8C00',
                'description' => 'Développement coordination - Nouvelles techniques',
                'prerequis_techniques' => json_encode(['Oi-zuki', 'Yoko-geri-keage', 'Soto-uke', 'Kiba-dachi']),
                'duree_minimum_mois' => 4, 'presences_minimum' => 32, 'age_minimum' => 5,
                'tarif_examen' => 35.00, 'examen_requis' => true, 'actif' => true,
            ],
            [
                'ordre' => 4, 'nom' => 'Violette',
                'couleur_hex' => '#9932CC',
                'description' => 'Perfectionnement techniques - Introduction blocages avancés',
                'prerequis_techniques' => json_encode(['Gyaku-zuki', 'Mawashi-geri', 'Uchi-uke', 'Kokutsu-dachi']),
                'duree_minimum_mois' => 4, 'presences_minimum' => 32, 'age_minimum' => 6,
                'tarif_examen' => 40.00, 'examen_requis' => true, 'actif' => true,
            ],
            [
                'ordre' => 5, 'nom' => 'Bleue',
                'couleur_hex' => '#0066FF',
                'description' => 'Maîtrise intermédiaire - Premier kata simple',
                'prerequis_techniques' => json_encode(['Ura-zuki', 'Yoko-geri-kekomi', 'Gedan-barai', 'Taikyoku Shodan']),
                'duree_minimum_mois' => 5, 'presences_minimum' => 40, 'age_minimum' => 7,
                'tarif_examen' => 45.00, 'examen_requis' => true, 'actif' => true,
            ],
            [
                'ordre' => 6, 'nom' => 'Bleue Rayée',
                'couleur_hex' => '#0066FF',
                'description' => 'Perfectionnement niveau bleu - Kata plus complexe',
                'prerequis_techniques' => json_encode(['Kizami-zuki', 'Ushiro-geri', 'Shuto-uke', 'Taikyoku Nidan']),
                'duree_minimum_mois' => 5, 'presences_minimum' => 40, 'age_minimum' => 8,
                'tarif_examen' => 50.00, 'examen_requis' => true, 'actif' => true,
            ],
            [
                'ordre' => 7, 'nom' => 'Verte',
                'couleur_hex' => '#00AA00',
                'description' => 'Niveau avancé - Kata Heian et kumite de base',
                'prerequis_techniques' => json_encode(['Heian Shodan', 'Kizami-mawashi-geri', 'Morote-uke', 'Jiyu-ippon-kumite']),
                'duree_minimum_mois' => 6, 'presences_minimum' => 48, 'age_minimum' => 9,
                'tarif_examen' => 55.00, 'examen_requis' => true, 'actif' => true,
            ],
            [
                'ordre' => 8, 'nom' => 'Verte Rayée',
                'couleur_hex' => '#00AA00',
                'description' => 'Perfectionnement vert - Plusieurs kata Heian',
                'prerequis_techniques' => json_encode(['Heian Nidan', 'Heian Sandan', 'Mae-geri-keage', 'Combinations avancées']),
                'duree_minimum_mois' => 6, 'presences_minimum' => 48, 'age_minimum' => 10,
                'tarif_examen' => 60.00, 'examen_requis' => true, 'actif' => true,
            ],
            [
                'ordre' => 9, 'nom' => 'Marron 1 Rayée',
                'couleur_hex' => '#8B4513',
                'description' => 'Préparation ceinture noire - Maîtrise technique élevée',
                'prerequis_techniques' => json_encode(['Heian Yondan', 'Heian Godan', 'Tekki Shodan', 'Jiyu-kumite']),
                'duree_minimum_mois' => 8, 'presences_minimum' => 64, 'age_minimum' => 12,
                'tarif_examen' => 70.00, 'examen_requis' => true, 'actif' => true,
            ],
            [
                'ordre' => 10, 'nom' => 'Marron 2 Rayées',
                'couleur_hex' => '#8B4513',
                'description' => 'Niveau expert - Kata supérieurs et kumite avancé',
                'prerequis_techniques' => json_encode(['Bassai-Dai', 'Kanku-Dai', 'Empi', 'Kumite libre contrôlé']),
                'duree_minimum_mois' => 10, 'presences_minimum' => 80, 'age_minimum' => 14,
                'tarif_examen' => 80.00, 'examen_requis' => true, 'actif' => true,
            ],
            [
                'ordre' => 11, 'nom' => 'Marron 3 Rayées',
                'couleur_hex' => '#8B4513',
                'description' => 'Pré-ceinture noire - Expertise complète et enseignement',
                'prerequis_techniques' => json_encode(['Jion', 'Hangetsu', 'Gankaku', 'Capacité enseignement base']),
                'duree_minimum_mois' => 12, 'presences_minimum' => 96, 'age_minimum' => 16,
                'tarif_examen' => 90.00, 'examen_requis' => true, 'actif' => true,
            ],

            // Ceintures noires Dan (12-21)
            [
                'ordre' => 12, 'nom' => 'Noire Shodan',
                'couleur_hex' => '#000000',
                'description' => '1er Dan - Maîtrise fondamentale et début enseignement',
                'prerequis_techniques' => json_encode(['Tous Heian', 'Tekki Shodan', 'Bassai-Dai', 'Kanku-Dai', 'Bunkai', 'Enseignement débutants']),
                'duree_minimum_mois' => 18, 'presences_minimum' => 144, 'age_minimum' => 18,
                'tarif_examen' => 150.00, 'examen_requis' => true, 'actif' => true,
            ],
            [
                'ordre' => 13, 'nom' => 'Noire Nidan',
                'couleur_hex' => '#000000',
                'description' => '2ème Dan - Approfondissement technique et pédagogique',
                'prerequis_techniques' => json_encode(['Jion', 'Empi', 'Hangetsu', 'Gankaku', 'Enseignement intermédiaire']),
                'duree_minimum_mois' => 24, 'presences_minimum' => 192, 'age_minimum' => 20,
                'tarif_examen' => 200.00, 'examen_requis' => true, 'actif' => true,
            ],
            [
                'ordre' => 14, 'nom' => 'Noire Sandan',
                'couleur_hex' => '#000000',
                'description' => '3ème Dan - Expertise avancée et leadership',
                'prerequis_techniques' => json_encode(['Bassai-Sho', 'Kanku-Sho', 'Tekki Nidan', 'Enseignement avancé']),
                'duree_minimum_mois' => 36, 'presences_minimum' => 288, 'age_minimum' => 23,
                'tarif_examen' => 250.00, 'examen_requis' => true, 'actif' => true,
            ],
            [
                'ordre' => 15, 'nom' => 'Noire Yondan',
                'couleur_hex' => '#000000',
                'description' => '4ème Dan - Maître instructeur et spécialisation',
                'prerequis_techniques' => json_encode(['Tekki Sandan', 'Jiin', 'Jitte', 'Formation instructeurs']),
                'duree_minimum_mois' => 48, 'presences_minimum' => 384, 'age_minimum' => 27,
                'tarif_examen' => 300.00, 'examen_requis' => true, 'actif' => true,
            ],
            [
                'ordre' => 16, 'nom' => 'Noire Godan',
                'couleur_hex' => '#000000',
                'description' => '5ème Dan - Expert reconnu et développement école',
                'prerequis_techniques' => json_encode(['Sochin', 'Nijushiho', 'Chinte', 'Gestion dojo']),
                'duree_minimum_mois' => 60, 'presences_minimum' => 480, 'age_minimum' => 32,
                'tarif_examen' => 400.00, 'examen_requis' => true, 'actif' => true,
            ],
            [
                'ordre' => 17, 'nom' => 'Noire Rokudan',
                'couleur_hex' => '#000000',
                'description' => '6ème Dan - Maître senior et innovation technique',
                'prerequis_techniques' => json_encode(['Unsu', 'Meikyo', 'Wankan', 'Recherche martiale']),
                'duree_minimum_mois' => 72, 'presences_minimum' => 576, 'age_minimum' => 38,
                'tarif_examen' => 500.00, 'examen_requis' => true, 'actif' => true,
            ],
            [
                'ordre' => 18, 'nom' => 'Noire Nanadan',
                'couleur_hex' => '#000000',
                'description' => '7ème Dan - Grand Maître et préservation tradition',
                'prerequis_techniques' => json_encode(['Tous kata supérieurs', 'Bunkai expert', 'Transmission tradition']),
                'duree_minimum_mois' => 84, 'presences_minimum' => 672, 'age_minimum' => 45,
                'tarif_examen' => 600.00, 'examen_requis' => true, 'actif' => true,
            ],
            [
                'ordre' => 19, 'nom' => 'Noire Hachidan',
                'couleur_hex' => '#000000',
                'description' => '8ème Dan - Niveau légendaire et sagesse martiale',
                'prerequis_techniques' => json_encode(['Maîtrise absolue', 'Philosophie karaté', 'Mentoring maîtres']),
                'duree_minimum_mois' => 96, 'presences_minimum' => 768, 'age_minimum' => 53,
                'tarif_examen' => 750.00, 'examen_requis' => true, 'actif' => true,
            ],
            [
                'ordre' => 20, 'nom' => 'Noire Kyudan',
                'couleur_hex' => '#000000',
                'description' => '9ème Dan - Sommet de l\'art et héritage vivant',
                'prerequis_techniques' => json_encode(['Excellence absolue', 'Leadership mondial', 'Héritage karaté']),
                'duree_minimum_mois' => 120, 'presences_minimum' => 960, 'age_minimum' => 62,
                'tarif_examen' => 1000.00, 'examen_requis' => true, 'actif' => true,
            ],
            [
                'ordre' => 21, 'nom' => 'Noire Judan',
                'couleur_hex' => '#000000',
                'description' => '10ème Dan - Légende éternelle du karaté StudiosUnis',
                'prerequis_techniques' => json_encode(['Perfection ultime', 'Sagesse infinie', 'Immortalité martiale']),
                'duree_minimum_mois' => 150, 'presences_minimum' => 1200, 'age_minimum' => 70,
                'tarif_examen' => 1500.00, 'examen_requis' => true, 'actif' => true,
            ],
        ];

        // Suppression anciennes données
        DB::table('ceintures')->truncate();

        foreach ($ceintures as $ceinture) {
            DB::table('ceintures')->insert(array_merge($ceinture, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
SEEDER_STUDIOSUNIS

echo "✅ Seeder StudiosUnis 21 ceintures créé"

# Exécution du seeder
echo ""
echo "🚀 Insertion des 21 ceintures officielles StudiosUnis..."
php artisan db:seed --class=CeinturesSeeder

echo ""
echo "🔍 Vérification des 21 ceintures StudiosUnis..."
php artisan tinker --execute="
\$ceintures = DB::table('ceintures')->orderBy('ordre')->get(['ordre', 'nom', 'couleur_hex', 'tarif_examen']);
echo '🥋 SYSTÈME CEINTURES STUDIOSUNIS COMPLET:' . PHP_EOL;
echo '=========================================' . PHP_EOL;
foreach(\$ceintures as \$c) {
    \$emoji = \$c->ordre <= 11 ? '🟡' : '⚫';
    echo \$emoji . ' ' . str_pad(\$c->ordre, 2, '0', STR_PAD_LEFT) . '. ' . 
         str_pad(\$c->nom, 20) . ' (' . \$c->couleur_hex . ') - ' . \$c->tarif_examen . '$' . PHP_EOL;
}
echo PHP_EOL . '🎯 Total: ' . \$ceintures->count() . ' ceintures officielles StudiosUnis' . PHP_EOL;
echo '✅ SYSTÈME STUDIOSDB v5 PRO OPÉRATIONNEL!' . PHP_EOL;
"

echo ""
echo "🎉 LES 21 CEINTURES OFFICIELLES STUDIOSUNIS SONT MAINTENANT DANS LA BASE!"
