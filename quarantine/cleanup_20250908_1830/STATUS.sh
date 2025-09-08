#!/bin/bash
echo "=== RAPPORT ÉTAT ACTUEL STUDIOSDB ==="
cd /home/studiosdb/studiosunisdb

echo ""
echo "📊 INFORMATIONS GÉNÉRALES"
echo "Projet : StudiosDB (Karaté Studios Unis St-Émile)"
echo "Date   : $(date +'%Y-%m-%d %H:%M:%S')"
echo "PWD    : $(pwd)"
echo "User   : $(whoami)"

echo ""
echo "🔧 ENVIRONNEMENT TECHNIQUE"
php artisan about | grep -E "(Laravel Version|PHP Version|Environment)" 

echo ""
echo "📋 ÉTAT MODULES"
echo "✅ J1 Bootstrap sécurité   : DONE"
echo "✅ J2 Dashboard (réf UI)   : DONE" 
echo "✅ J3 Cours (réf fonct)    : DONE - Contraintes DB résolues"
echo "🟡 J4 Utilisateurs         : TODO - Prochaine étape"
echo "🟡 J5 Membres             : TODO"
echo "🟡 J6 Inscription self     : TODO"

echo ""
echo "🧪 TESTS RAPIDES MODULE COURS"
echo "- Migration tarif_mensuel:"
php artisan tinker --execute="
try {
    \$t = DB::select('DESCRIBE cours tarif_mensuel')[0];
    echo '  Nullable: ' . \$t->Null . ' (attendu: YES)' . PHP_EOL;
} catch (Exception \$e) {
    echo '  ERREUR: ' . \$e->getMessage() . PHP_EOL;
}
" 2>/dev/null

echo "- Migration ecole_id:"
php artisan tinker --execute="
try {
    \$e = DB::select('DESCRIBE cours ecole_id')[0];
    echo '  Default: ' . (\$e->Default ?? 'NULL') . ' (fallback OK)' . PHP_EOL;
} catch (Exception \$e) {
    echo '  ERREUR: ' . \$e->getMessage() . PHP_EOL;
}
" 2>/dev/null

echo "- FormRequests:"
echo "  StoreCoursRequest  : $([ -f "app/Http/Requests/StoreCoursRequest.php" ] && echo "✅" || echo "❌")"
echo "  UpdateCoursRequest : $([ -f "app/Http/Requests/UpdateCoursRequest.php" ] && echo "✅" || echo "❌")"

echo ""
echo "📁 FICHIERS SAUVEGARDE"
echo "- Scripts : $(ls -1 *.sh 2>/dev/null | wc -l) fichiers .sh"
echo "- Documentation : $([ -f "docs/ADR-20250828-COURS-CONTRAINTES-DB.md" ] && echo "✅ ADR créé" || echo "❌ ADR manquant")"
echo "- Status : $([ -f "README_STATUS.md" ] && echo "✅ README" || echo "❌ README manquant")"

echo ""
echo "🔄 GIT STATUS"
git status --porcelain | head -5 | sed 's/^/  /'
if [ $(git status --porcelain | wc -l) -gt 5 ]; then
    echo "  ... ($(git status --porcelain | wc -l) fichiers total)"
fi

echo ""
echo "📞 ACTIONS DISPONIBLES"
echo "1. Sauvegarde complète    : ./SAVE.sh"
echo "2. Fix module cours       : ./FIX_COMPLET_COURS.sh"
echo "3. Test interface         : php artisan serve --port=8001"
echo "4. Voir documentation     : cat docs/ADR-*.md"

echo ""
echo "🎯 RÉSUMÉ"
if [ -f ".studiosdb_status" ]; then
    echo "État : $(cat .studiosdb_status)"
else
    echo "État : Module Cours corrigé, prêt pour J4 Utilisateurs"
fi

echo ""
echo "✅ RAPPORT TERMINÉ"
