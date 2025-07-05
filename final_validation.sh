#!/bin/bash

echo "✅ VALIDATION FINALE STUDIOSDB"
echo "=============================="

echo "🧪 TESTS POST-CORRECTION:"
echo "========================="

# Test syntaxe PHP tous contrôleurs
echo "1. Vérification syntaxe PHP:"
syntax_errors=0
find app/Http/Controllers/Admin -name "*.php" | while read controller; do
    if ! php -l "$controller" >/dev/null 2>&1; then
        echo "❌ Erreur: $(basename $controller)"
        syntax_errors=$((syntax_errors + 1))
    else
        echo "✅ OK: $(basename $controller)"
    fi
done

echo ""
echo "2. Test compilation vues:"
if php artisan view:clear >/dev/null 2>&1; then
    echo "✅ Cache vues vidé"
    
    if php artisan view:cache >/dev/null 2>&1; then
        echo "✅ Vues recompilées avec succès"
    else
        echo "⚠️ Problème compilation - components manquants possibles"
    fi
else
    echo "❌ Problème cache vues"
fi

echo ""
echo "3. Test routes admin:"
php artisan route:list --path=admin >/dev/null 2>&1 && echo "✅ Routes admin OK" || echo "❌ Problème routes admin"

echo ""
echo "📊 STATISTIQUES FINALES:"
echo "========================"
echo "- Pages admin: $(find resources/views/pages/admin -name '*.blade.php' 2>/dev/null | wc -l)"
echo "- Components: $(find resources/views/components -name '*.blade.php' 2>/dev/null | wc -l)"
echo "- Partials restants: $(find resources/views/partials -name '*.blade.php' 2>/dev/null | wc -l)"
echo "- Layouts: $(find resources/views/layouts -name '*.blade.php' 2>/dev/null | wc -l)"

echo ""
echo "🌳 STRUCTURE FINALE:"
echo "==================="
tree resources/views -I "*.bak|*.backup*" 2>/dev/null | head -25

echo ""
echo "🎯 PROCHAINES ÉTAPES:"
echo "===================="
echo "1. Tester manuellement quelques pages admin"
echo "2. Compiler les assets: npm run build"
echo "3. Tester l'interface admin complète"
echo "4. Si tout fonctionne, supprimer les fichiers .backup"
echo "5. Committer les changements"
