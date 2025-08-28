#!/bin/bash
echo "=== TEST FINAL CRÉATION COURS APRÈS FIX ==="
cd /home/studiosdb/studiosunisdb

echo "1. DÉMARRAGE SERVEUR TEST"
php artisan serve --port=8001 --host=127.0.0.1 &
SERVER_PID=$!
echo "Serveur démarré (PID: $SERVER_PID)"
sleep 3

echo ""
echo "2. TEST ENDPOINT CREATE COURS"
echo "Testing GET /cours/create..."
RESPONSE=$(curl -s -o /dev/null -w "%{http_code}" http://127.0.0.1:8001/cours/create)
echo "HTTP Status: $RESPONSE"

if [ "$RESPONSE" = "200" ]; then
    echo "✅ Page création cours accessible"
else
    echo "❌ Erreur accès page création: $RESPONSE" 
fi

echo ""
echo "3. ARRÊT SERVEUR TEST" 
kill $SERVER_PID
echo "Serveur arrêté"

echo ""
echo "4. VÉRIFICATION FINALE STRUCTURE"
php artisan tinker --execute="
echo '=== VÉRIFICATION FINALE ===' . PHP_EOL;
if (Schema::hasTable('cours')) {
    \$type = DB::select('DESCRIBE cours tarif_mensuel')[0];
    echo 'tarif_mensuel: ' . \$type->Type . ' | Null: ' . \$type->Null . PHP_EOL;
    
    if (\$type->Null === 'YES') {
        echo '🎯 SUCCESS: Contrainte DB résolue - tarif_mensuel NULLABLE' . PHP_EOL;
        echo '🚀 Module Cours prêt pour création de tous types de tarification!' . PHP_EOL;
    } else {
        echo '⚠️  ATTENTION: tarif_mensuel encore NOT NULL - migration à reappliquer' . PHP_EOL;
    }
} else {
    echo '❌ Table cours introuvable' . PHP_EOL;
}
"

echo ""
echo "=== INSTRUCTIONS UTILISATEUR ==="
echo "Pour tester manuellement:"
echo "1. cd /home/studiosdb/studiosunisdb"
echo "2. php artisan serve --port=8001"  
echo "3. Naviguer vers http://127.0.0.1:8001/cours/create"
echo "4. Créer un cours avec type TRIMESTRIEL ou HORAIRE"
echo "5. Vérifier que la création fonctionne sans erreur contrainte DB"
echo ""
echo "Si problème persiste, re-exécuter: ./fix_definitif.sh"
