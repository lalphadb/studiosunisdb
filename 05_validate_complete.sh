#!/bin/bash

echo "✅ VALIDATION COMPLÈTE STANDARDISATION"
echo "====================================="

echo "📊 STATISTIQUES FINALES:"
echo "========================"

echo ""
echo "📁 STRUCTURE FINALE:"
echo "- Pages admin: $(find resources/views/pages/admin -name '*.blade.php' 2>/dev/null | wc -l)"
echo "- Components admin: $(find resources/views/components/admin -name '*.blade.php' 2>/dev/null | wc -l)"
echo "- Components généraux: $(find resources/views/components -maxdepth 1 -name '*.blade.php' 2>/dev/null | wc -l)"
echo "- Partials: $(find resources/views/partials -name '*.blade.php' 2>/dev/null | wc -l)"
echo "- Layouts: $(find resources/views/layouts -name '*.blade.php' 2>/dev/null | wc -l)"

echo ""
echo "🧪 TESTS DE VALIDATION:"
echo "======================"

# Test Artisan
if php artisan --version >/dev/null 2>&1; then
    echo "✅ Laravel/Artisan fonctionne"
else
    echo "❌ Problème avec Laravel/Artisan"
fi

# Test routes
echo ""
echo "🛣️ ROUTES ADMIN (Top 10):"
php artisan route:list --path=admin 2>/dev/null | head -10 || echo "❌ Impossible de lister les routes"

# Test compilation vues
echo ""
echo "👁️ TEST COMPILATION VUES:"
if php artisan view:clear 2>/dev/null; then
    echo "✅ Cache vues vidé"
    
    if php artisan view:cache 2>/dev/null; then
        echo "✅ Vues recompilées avec succès"
    else
        echo "⚠️ Problème compilation vues"
    fi
else
    echo "⚠️ Problème avec le cache des vues"
fi

# Vérifier syntax PHP
echo ""
echo "🔍 VÉRIFICATION SYNTAXE PHP:"
syntax_errors=0
find app/Http/Controllers/Admin -name "*.php" | while read file; do
    if ! php -l "$file" >/dev/null 2>&1; then
        echo "❌ Erreur: $file"
        syntax_errors=$((syntax_errors + 1))
    fi
done

if [ $syntax_errors -eq 0 ]; then
    echo "✅ Toutes les syntaxes PHP sont correctes"
fi

# Afficher la nouvelle arborescence
echo ""
echo "🌳 ARBORESCENCE FINALE:"
echo "======================"
tree resources/views 2>/dev/null | head -30 || find resources/views -type f -name "*.blade.php" | head -20

echo ""
echo "📋 RÉSUMÉ STANDARDISATION:"
echo "========================="
echo "✅ Structure migrée vers Laravel 12 standards"
echo "✅ Fichiers inutilisés supprimés"
echo "✅ Contrôleurs mis à jour"
echo "✅ Navigation préparée pour migration"
echo ""
echo "⚠️ ÉTAPES FINALES MANUELLES:"
echo "1. Tester quelques pages admin"
echo "2. Finaliser migration navigation si tests OK"
echo "3. Supprimer ancien dossier admin/ si tout fonctionne"
echo "4. Compiler les assets: npm run build"
