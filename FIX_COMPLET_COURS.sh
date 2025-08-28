#!/bin/bash
echo "=== RÉSOLUTION COMPLÈTE ERREURS CRÉATION COURS ==="
cd /home/studiosdb/studiosunisdb

echo "🚨 PROBLÈMES IDENTIFIÉS :"
echo "1. tarif_mensuel cannot be null"
echo "2. ecole_id doesn't have default value"
echo ""

echo "🔧 ÉTAPE 1: DIAGNOSTIC INITIAL"
echo "- Vérification tarif_mensuel:"
php artisan tinker --execute="
try {
    \$tarif = DB::select('DESCRIBE cours tarif_mensuel')[0];
    echo 'tarif_mensuel Null: ' . \$tarif->Null . PHP_EOL;
} catch (Exception \$e) {
    echo 'Erreur tarif_mensuel: ' . \$e->getMessage() . PHP_EOL;
}
"

echo "- Vérification ecole_id:"
php artisan tinker --execute="
try {
    \$ecole = DB::select('DESCRIBE cours ecole_id')[0];
    echo 'ecole_id Null: ' . \$ecole->Null . ' | Default: ' . (\$ecole->Default ?? 'NULL') . PHP_EOL;
} catch (Exception \$e) {
    echo 'Erreur ecole_id: ' . \$e->getMessage() . PHP_EOL;
}
"

echo ""
echo "🔧 ÉTAPE 2: APPLICATION MIGRATIONS CORRECTIVES"
echo "- Fix tarif_mensuel nullable..."
php artisan migrate --path=database/migrations/2025_08_28_130000_fix_tarif_mensuel_nullable.php --force

echo "- Fix ecole_id default..."
php artisan migrate --path=database/migrations/2025_08_28_140000_fix_ecole_id_default_cours.php --force

echo ""
echo "🔧 ÉTAPE 3: VÉRIFICATION POST-FIX"
echo "- tarif_mensuel après fix:"
php artisan tinker --execute="
\$tarif = DB::select('DESCRIBE cours tarif_mensuel')[0];
echo 'tarif_mensuel Null: ' . \$tarif->Null . ' (attendu: YES)' . PHP_EOL;
"

echo "- ecole_id après fix:"
php artisan tinker --execute="
\$ecole = DB::select('DESCRIBE cours ecole_id')[0];
echo 'ecole_id Null: ' . \$ecole->Null . ' | Default: ' . (\$ecole->Default ?? 'NULL') . PHP_EOL;
"

echo ""
echo "🔧 ÉTAPE 4: CLEAR CACHE"
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo ""
echo "🔧 ÉTAPE 5: VÉRIFICATION UTILISATEUR"
php artisan tinker --execute="
\$user = App\\Models\\User::first();
if (\$user) {
    echo 'Utilisateur test: ' . \$user->name . PHP_EOL;
    echo 'ecole_id: ' . (\$user->ecole_id ?? 'NULL') . PHP_EOL;
} else {
    echo 'Aucun utilisateur trouvé' . PHP_EOL;
}
"

echo ""
echo "🎯 RÉSULTAT ATTENDU:"
echo "✅ tarif_mensuel: NULLABLE (YES)"
echo "✅ ecole_id: DEFAULT VALUE ou ROBUSTE FALLBACK"
echo "✅ FormRequests: Assignation ecole_id robuste"
echo "✅ Création cours: Fonctionne tous types"
echo ""

echo "🚀 TEST FINAL:"
echo "php artisan serve --port=8001"
echo "Naviguer vers http://127.0.0.1:8001/cours/create"
echo "Créer cours TRIMESTRIEL - devrait maintenant fonctionner !"

echo ""
echo "✅ FIX COMPLET TERMINÉ"
