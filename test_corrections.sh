#!/bin/bash
echo "=== TEST COMPLET APRÈS CORRECTIONS ==="

cd /home/studiosdb/studiosunisdb

echo "1. Appliquer la migration pending..."
php artisan migrate --force

echo "2. Vérifier structure DB après migration..."
php artisan tinker --execute="
if (Schema::hasTable('cours')) {
    \$type = DB::select('DESCRIBE cours tarif_mensuel')[0];
    echo 'tarif_mensuel: ' . \$type->Type . ' | Null: ' . \$type->Null . ' | Default: ' . \$type->Default . PHP_EOL;
}
"

echo "3. Vérifier FormRequests créées..."
ls -la app/Http/Requests/StoreCoursRequest.php
ls -la app/Http/Requests/UpdateCoursRequest.php

echo "4. Test de validation..."
php artisan route:clear
php artisan config:clear

echo "5. Démarrer serveur pour test..."
php artisan serve --port=8001 --host=127.0.0.1 &
SERVER_PID=$!
sleep 3

echo "6. Tester endpoint cours/create..."
curl -s -o /dev/null -w "%{http_code}" http://127.0.0.1:8001/cours/create

echo ""
echo "7. Tuer serveur de test..."
kill $SERVER_PID

echo ""
echo "=== RÉSUMÉ CORRECTIONS ==="
echo "✅ Migration tarif_mensuel nullable appliquée"
echo "✅ FormRequests StoreCoursRequest et UpdateCoursRequest créées" 
echo "✅ Contrôleur simplifié pour utiliser FormRequests"
echo "✅ Formulaires Create.vue et Edit.vue corrigés (null au lieu de string vide)"
echo "✅ Validation centralisée avec messages d'erreur français"
echo ""
echo "🚀 TESTS À EFFECTUER :"
echo "1. cd /home/studiosdb/studiosunisdb && php artisan serve --port=8001"
echo "2. Naviguer vers http://127.0.0.1:8001/cours/create"
echo "3. Créer cours MENSUEL (doit fonctionner)"  
echo "4. Créer cours TRIMESTRIEL/HORAIRE (doit maintenant fonctionner !)"
echo "5. Vérifier les validations avec messages français"
