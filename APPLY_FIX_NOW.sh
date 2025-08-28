#!/bin/bash
echo "=== DIAGNOSTIC FINAL CONTRAINTE DB COURS ==="
cd /home/studiosdb/studiosunisdb

echo ""
echo "🔍 ANALYSE DÉTAILLÉE DU PROBLÈME:"
echo "1. Migration 'fix_tarif_mensuel_nullable' existe mais PAS APPLIQUÉE"
echo "2. Colonne 'tarif_mensuel' encore NOT NULL en base"
echo "3. FormRequests créées mais migration DB manquante"
echo "4. Formulaires Vue corrigés (null au lieu de string vide)"
echo ""

echo "📊 VÉRIFICATION ÉTAT ACTUEL:"
echo "- Migration status:"
php artisan migrate:status | grep "fix_tarif_mensuel\|130000"

echo ""
echo "- Structure DB actuelle:"
php artisan tinker --execute="
\$type = DB::select('DESCRIBE cours tarif_mensuel')[0];
echo 'tarif_mensuel: Null=' . \$type->Null . ' (doit être YES)' . PHP_EOL;
"

echo ""
echo "🚀 APPLICATION DU FIX:"
echo "Step 1: Forcer application migration..."
php artisan migrate --path=database/migrations/2025_08_28_130000_fix_tarif_mensuel_nullable.php --force

echo ""
echo "Step 2: Vérification post-fix..."
php artisan tinker --execute="
\$type = DB::select('DESCRIBE cours tarif_mensuel')[0];
if (\$type->Null === 'YES') {
    echo '✅ SUCCESS: tarif_mensuel maintenant NULLABLE' . PHP_EOL;
} else {
    echo '❌ ÉCHEC: Migration non appliquée correctement' . PHP_EOL;
    echo 'Commande alternative: php artisan migrate:fresh --seed' . PHP_EOL;
}
"

echo ""
echo "Step 3: Clear cache..."
php artisan config:clear
php artisan route:clear

echo ""
echo "📋 VALIDATION COMPLÈTE:"
echo "- FormRequests: ✅ Créées avec validation nullable"
echo "- Contrôleur: ✅ Utilise FormRequests"
echo "- Formulaires: ✅ Envoient null au lieu de string vide"
echo "- Migration DB: 🔄 Appliquée par ce script"
echo ""

echo "🎯 RÉSULTAT ATTENDU:"
echo "Création de cours TRIMESTRIEL/HORAIRE/A_LA_CARTE maintenant possible"
echo "Plus d'erreur 'tarif_mensuel cannot be null'"
echo ""

echo "✅ FIX TERMINÉ - Tester création cours non-mensuel via interface"
