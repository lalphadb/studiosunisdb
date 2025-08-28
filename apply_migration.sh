#!/bin/bash
cd /home/studiosdb/studiosunisdb
echo "=== Application de la migration pending ==="
php artisan migrate --force
echo "=== Vérification structure DB après migration ==="
php artisan tinker --execute="
if (Schema::hasTable('cours')) {
    \$type = DB::select('DESCRIBE cours tarif_mensuel')[0];
    echo 'tarif_mensuel: ' . \$type->Type . ' | Null: ' . \$type->Null . ' | Default: ' . \$type->Default . PHP_EOL;
}
"
