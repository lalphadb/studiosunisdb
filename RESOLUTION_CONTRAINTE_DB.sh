#!/bin/bash
chmod +x /home/studiosdb/studiosunisdb/fix_definitif.sh
chmod +x /home/studiosdb/studiosunisdb/test_final.sh
chmod +x /home/studiosdb/studiosunisdb/audit_complet.sh

echo "Scripts rendus exécutables."
echo ""
echo "=== RÉSOLUTION PROBLÈME CONTRAINTE DB ==="
echo ""
echo "DIAGNOSTIC : Migration 'fix_tarif_mensuel_nullable' pas appliquée"
echo "SYMPTÔME   : 'Column tarif_mensuel cannot be null' lors création cours non-mensuels"
echo "SOLUTION   : Appliquer migration + vérification complète"
echo ""
echo "ÉTAPES POUR RÉSOUDRE :"
echo "1. ./fix_definitif.sh     # Applique la migration et vérifie" 
echo "2. ./test_final.sh        # Teste la résolution"
echo "3. Test manuel interface  # Créer cours trimestriel/horaire"
echo ""
echo "Si problème persiste après ces étapes, examiner logs détaillés."
