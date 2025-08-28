#!/bin/bash

# Script d'insertion des 21 ceintures officielles StudiosUnis

php artisan tinker --execute="
// Désactiver les contraintes FK
DB::statement('SET FOREIGN_KEY_CHECKS=0');

// Vider la table
DB::table('ceintures')->truncate();

// Réactiver les contraintes
DB::statement('SET FOREIGN_KEY_CHECKS=1');

// Les 21 ceintures officielles
\$ceintures = [
    ['order' => 1,  'name' => 'Blanche',         'color_hex' => '#FFFFFF'],
    ['order' => 2,  'name' => 'Jaune',           'color_hex' => '#FFD700'],
    ['order' => 3,  'name' => 'Orange',          'color_hex' => '#FF8C00'],
    ['order' => 4,  'name' => 'Violette',        'color_hex' => '#8B008B'],
    ['order' => 5,  'name' => 'Bleue',           'color_hex' => '#0000CD'],
    ['order' => 6,  'name' => 'Bleue Rayée',     'color_hex' => '#4169E1'],
    ['order' => 7,  'name' => 'Verte',           'color_hex' => '#228B22'],
    ['order' => 8,  'name' => 'Verte Rayée',     'color_hex' => '#32CD32'],
    ['order' => 9,  'name' => 'Marron 1 Rayée',  'color_hex' => '#8B4513'],
    ['order' => 10, 'name' => 'Marron 2 Rayées', 'color_hex' => '#A0522D'],
    ['order' => 11, 'name' => 'Marron 3 Rayées', 'color_hex' => '#654321'],
    ['order' => 12, 'name' => 'Noire Shodan',    'color_hex' => '#1C1C1C'],
    ['order' => 13, 'name' => 'Noire Nidan',     'color_hex' => '#1C1C1C'],
    ['order' => 14, 'name' => 'Noire Sandan',    'color_hex' => '#1C1C1C'],
    ['order' => 15, 'name' => 'Noire Yondan',    'color_hex' => '#1C1C1C'],
    ['order' => 16, 'name' => 'Noire Godan',     'color_hex' => '#1C1C1C'],
    ['order' => 17, 'name' => 'Noire Rokudan',   'color_hex' => '#1C1C1C'],
    ['order' => 18, 'name' => 'Noire Nanadan',   'color_hex' => '#1C1C1C'],
    ['order' => 19, 'name' => 'Noire Hachidan',  'color_hex' => '#1C1C1C'],
    ['order' => 20, 'name' => 'Noire Kyudan',    'color_hex' => '#1C1C1C'],
    ['order' => 21, 'name' => 'Noire Judan',     'color_hex' => '#1C1C1C'],
];

// Insérer chaque ceinture
foreach(\$ceintures as \$c) {
    DB::table('ceintures')->insert(array_merge(\$c, [
        'created_at' => now(),
        'updated_at' => now()
    ]));
}

echo '✅ 21 ceintures officielles insérées!' . PHP_EOL;

// Afficher le résultat
\$result = DB::table('ceintures')->orderBy('order')->get(['order', 'name', 'color_hex']);
echo PHP_EOL . 'Liste des ceintures:' . PHP_EOL;
echo '-------------------' . PHP_EOL;
foreach(\$result as \$r) {
    echo sprintf('%2d. %-20s %s', \$r->order, \$r->name, \$r->color_hex) . PHP_EOL;
}
echo PHP_EOL . 'Total: ' . \$result->count() . ' ceintures' . PHP_EOL;
"

# Clear cache
php artisan optimize:clear

echo ""
echo "=== Vérification dans la BD ==="
mysql -u root -p"$DB_PASSWORD" studiosdb -e "SELECT id, \`order\`, name, color_hex FROM ceintures ORDER BY \`order\`;" 2>/dev/null || echo "Entrez le mot de passe MySQL manuellement"
