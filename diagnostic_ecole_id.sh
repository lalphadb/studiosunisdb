#!/bin/bash
echo "=== DIAGNOSTIC ERREUR ECOLE_ID ==="
cd /home/studiosdb/studiosunisdb

echo "1. STRUCTURE TABLE COURS (ecole_id)"
php artisan tinker --execute="
if (Schema::hasTable('cours')) {
    \$type = DB::select('DESCRIBE cours ecole_id')[0];
    echo 'ecole_id: ' . \$type->Type . ' | Null: ' . \$type->Null . ' | Default: ' . (\$type->Default ?? 'NULL') . PHP_EOL;
} else {
    echo 'Table cours inexistante' . PHP_EOL;
}
"

echo ""
echo "2. STRUCTURE TABLE USERS (ecole_id)"
php artisan tinker --execute="
if (Schema::hasColumn('users', 'ecole_id')) {
    \$type = DB::select('DESCRIBE users ecole_id')[0];
    echo 'users.ecole_id: ' . \$type->Type . ' | Null: ' . \$type->Null . ' | Default: ' . (\$type->Default ?? 'NULL') . PHP_EOL;
} else {
    echo 'Colonne users.ecole_id inexistante' . PHP_EOL;
}
"

echo ""
echo "3. UTILISATEUR CONNECTÉ"
php artisan tinker --execute="
\$user = App\\Models\\User::first();
if (\$user) {
    echo 'Premier utilisateur ID: ' . \$user->id . PHP_EOL;
    echo 'Nom: ' . \$user->name . PHP_EOL; 
    echo 'Email: ' . \$user->email . PHP_EOL;
    if (isset(\$user->ecole_id)) {
        echo 'ecole_id: ' . (\$user->ecole_id ?? 'NULL') . PHP_EOL;
    } else {
        echo 'ecole_id: COLONNE INEXISTANTE' . PHP_EOL;
    }
} else {
    echo 'Aucun utilisateur trouvé' . PHP_EOL;
}
"

echo ""
echo "4. TABLE ECOLES"
php artisan tinker --execute="
if (Schema::hasTable('ecoles')) {
    \$count = DB::table('ecoles')->count();
    echo 'Table ecoles: ' . \$count . ' enregistrements' . PHP_EOL;
    if (\$count > 0) {
        \$ecole = DB::table('ecoles')->first();
        echo 'Première école ID: ' . \$ecole->id . PHP_EOL;
    }
} else {
    echo 'Table ecoles: INEXISTANTE (environnement mono-école ?)' . PHP_EOL;
}
"

echo ""
echo "5. VÉRIFICATION FORMREQUEST"
grep -A5 -B5 "ecole_id.*user" app/Http/Requests/StoreCoursRequest.php

echo ""
echo "=== RÉSUMÉ DIAGNOSTIC ==="
echo "🔍 Vérifier si l'utilisateur a un ecole_id"
echo "🔍 Vérifier contrainte NOT NULL sur cours.ecole_id"
echo "🔍 Corriger FormRequest si nécessaire"
