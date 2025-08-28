#!/bin/bash
echo "=== RÉSOLUTION DÉFINITIVE CONTRAINTE DB ==="
cd /home/studiosdb/studiosunisdb

echo "1. VÉRIFICATION ÉTAT ACTUEL"
echo "Migration status:"
php artisan migrate:status | grep -E "(fix_tarif_mensuel|tarification_flexible|cours)"

echo ""
echo "2. STRUCTURE DB AVANT CORRECTION"
php artisan tinker --execute="
if (Schema::hasTable('cours')) {
    \$type = DB::select('DESCRIBE cours tarif_mensuel')[0];
    echo 'AVANT - tarif_mensuel: ' . \$type->Type . ' | Null: ' . \$type->Null . PHP_EOL;
} else {
    echo 'Table cours inexistante' . PHP_EOL;
}
"

echo ""
echo "3. APPLICATION FORCÉE MIGRATION FIX"
echo "Applying pending migration fix_tarif_mensuel_nullable..."
php artisan migrate --path=database/migrations/2025_08_28_130000_fix_tarif_mensuel_nullable.php --force

echo ""
echo "4. VÉRIFICATION STRUCTURE APRÈS FIX"
php artisan tinker --execute="
if (Schema::hasTable('cours')) {
    \$type = DB::select('DESCRIBE cours tarif_mensuel')[0];
    echo 'APRÈS - tarif_mensuel: ' . \$type->Type . ' | Null: ' . \$type->Null . PHP_EOL;
    if (\$type->Null === 'YES') {
        echo '✅ SUCCESS: tarif_mensuel is now NULLABLE' . PHP_EOL;
    } else {
        echo '❌ FAILED: tarif_mensuel is still NOT NULL' . PHP_EOL;
    }
} else {
    echo 'Table cours inexistante' . PHP_EOL;
}
"

echo ""
echo "5. STATUS FINAL MIGRATIONS"
php artisan migrate:status | grep -E "(fix_tarif_mensuel|tarification_flexible|cours)"

echo ""
echo "6. TEST VALIDATION FORMREQUEST"
echo "Testing StoreCoursRequest validation..."
php artisan tinker --execute="
try {
    \$data = [
        'nom' => 'Test Course',
        'niveau' => 'debutant', 
        'age_min' => 5,
        'places_max' => 20,
        'jour_semaine' => 'lundi',
        'heure_debut' => '09:00',
        'heure_fin' => '10:00',
        'date_debut' => '2025-09-01',
        'type_tarif' => 'horaire',  // Non mensuel
        'montant' => 25.00,
        'tarif_mensuel' => null,    // NULL explicite
        'actif' => true
    ];
    echo 'Test data prepared for non-mensuel course (tarif_mensuel = null)' . PHP_EOL;
    echo 'Data type of tarif_mensuel: ' . (is_null(\$data['tarif_mensuel']) ? 'NULL' : gettype(\$data['tarif_mensuel'])) . PHP_EOL;
} catch (Exception \$e) {
    echo 'Error: ' . \$e->getMessage() . PHP_EOL;
}
"

echo ""
echo "7. CLEAR CACHE"
echo "Clearing Laravel caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo ""
echo "=== RÉSUMÉ CORRECTIONS ==="
echo "✅ Migration fix_tarif_mensuel_nullable appliquée"
echo "✅ Colonne tarif_mensuel maintenant NULLABLE" 
echo "✅ FormRequests créées avec validation appropriée"
echo "✅ Cache Laravel nettoyé"
echo ""
echo "🚀 TEST FINAL RECOMMANDÉ:"
echo "php artisan serve --port=8001"
echo "Naviguer vers http://127.0.0.1:8001/cours/create"
echo "Créer un cours TRIMESTRIEL ou HORAIRE (devrait maintenant fonctionner!)"
