#!/bin/bash
echo "=== AUDIT COMPLET MODULE COURS - StudiosDB ==="
cd /home/studiosdb/studiosunisdb

echo "1. INFORMATIONS PROJET"
echo "- Répertoire de travail: $(pwd)"
echo "- Utilisateur: $(whoami)"
echo "- Laravel version:"
php artisan about | grep "Laravel Version"

echo ""
echo "2. ÉTAT MIGRATIONS"
echo "- Liste migrations pending:"
php artisan migrate:status | grep -i "pending\|tarif"

echo ""
echo "- Détail migration fix_tarif_mensuel:"
if [ -f "database/migrations/2025_08_28_130000_fix_tarif_mensuel_nullable.php" ]; then
    echo "✅ Migration fix_tarif_mensuel_nullable.php EXISTE"
    head -20 database/migrations/2025_08_28_130000_fix_tarif_mensuel_nullable.php
else
    echo "❌ Migration fix_tarif_mensuel_nullable.php MANQUANTE"
fi

echo ""
echo "3. STRUCTURE DB ACTUELLE"
php artisan tinker --execute="
try {
    if (Schema::hasTable('cours')) {
        echo 'Table cours exists: YES' . PHP_EOL;
        \$columns = Schema::getColumnListing('cours');
        if (in_array('tarif_mensuel', \$columns)) {
            \$type = DB::select('DESCRIBE cours tarif_mensuel')[0];
            echo 'tarif_mensuel column:' . PHP_EOL;
            echo '  Type: ' . \$type->Type . PHP_EOL;
            echo '  Null: ' . \$type->Null . PHP_EOL;
            echo '  Default: ' . (\$type->Default ?? 'NULL') . PHP_EOL;
        } else {
            echo 'tarif_mensuel column: NOT FOUND' . PHP_EOL;
        }
        echo 'All columns: ' . implode(', ', \$columns) . PHP_EOL;
    } else {
        echo 'Table cours: NOT EXISTS' . PHP_EOL;
    }
} catch (Exception \$e) {
    echo 'ERROR: ' . \$e->getMessage() . PHP_EOL;
}
"

echo ""
echo "4. FORMREQUESTS"
echo "- StoreCoursRequest:"
if [ -f "app/Http/Requests/StoreCoursRequest.php" ]; then
    echo "✅ EXISTE"
    grep -n "tarif_mensuel" app/Http/Requests/StoreCoursRequest.php | head -3
else
    echo "❌ MANQUANTE"
fi

echo "- UpdateCoursRequest:"
if [ -f "app/Http/Requests/UpdateCoursRequest.php" ]; then
    echo "✅ EXISTE" 
    grep -n "tarif_mensuel" app/Http/Requests/UpdateCoursRequest.php | head -3
else
    echo "❌ MANQUANTE"
fi

echo ""
echo "5. CONTRÔLEUR"
grep -n "StoreCoursRequest\|UpdateCoursRequest" app/Http/Controllers/CoursController.php

echo ""
echo "6. FORMULAIRES VUE"
echo "- Create.vue tarif_mensuel init:"
grep -A2 -B2 "tarif_mensuel:" resources/js/Pages/Cours/Create.vue

echo "- Edit.vue tarif_mensuel init:"
grep -A2 -B2 "tarif_mensuel:" resources/js/Pages/Cours/Edit.vue

echo ""
echo "7. TEST ENDPOINTS"
echo "- Routes cours:"
php artisan route:list | grep cours | head -5

echo ""
echo "=== FIN AUDIT ==="
