#!/bin/bash

echo "🚨 RESET COMPLET STUDIOSDB - STRUCTURE ULTRA-PRO"
echo "=============================================="

cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro

read -p "⚠️ SUPPRIMER toutes données existantes et refaire? (y/N): " confirm

if [[ $confirm != "y" && $confirm != "Y" ]]; then
    echo "❌ Reset annulé"
    exit 1
fi

echo ""
echo "1️⃣ Suppression tables existantes conflictuelles..."
php artisan tinker --execute="
DB::statement('SET FOREIGN_KEY_CHECKS=0');
\$tables = ['ceintures', 'membres', 'cours', 'presences', 'examens_ceintures', 'cours_inscriptions', 'membres_historique'];
foreach(\$tables as \$table) {
    try {
        DB::statement('DROP TABLE IF EXISTS ' . \$table);
        echo 'Supprimé: ' . \$table . PHP_EOL;
    } catch(Exception \$e) {
        echo 'Erreur: ' . \$table . ' - ' . \$e->getMessage() . PHP_EOL;
    }
}
DB::statement('SET FOREIGN_KEY_CHECKS=1');
"

echo ""
echo "2️⃣ Reset migrations Laravel..."
php artisan migrate:reset --force

echo ""
echo "3️⃣ Exécution nouvelles migrations ultra-pro..."
php artisan migrate --force

echo ""
echo "4️⃣ Seeders données ultra-pro..."
php artisan db:seed

echo ""
echo "5️⃣ Vérification finale..."
php artisan tinker --execute="
echo '📋 TABLES STUDIOSDB v5 PRO ULTRA:' . PHP_EOL;
\$tables = DB::select('SHOW TABLES');
foreach(\$tables as \$table) {
    \$tableName = array_values((array)\$table)[0];
    if(str_contains(\$tableName, 'ceintures') || str_contains(\$tableName, 'membres') || str_contains(\$tableName, 'cours')) {
        \$count = DB::table(\$tableName)->count();
        echo '✅ ' . \$tableName . ' (' . \$count . ' enregistrements)' . PHP_EOL;
    }
}
"

echo ""
echo "🎯 STUDIOSDB v5 PRO ULTRA-PROFESSIONNEL OPÉRATIONNEL!"
