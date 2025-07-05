#!/bin/bash

echo "🗑️ DÉTECTION FICHIERS INUTILISÉS STUDIOSDB"
echo "=========================================="

UNUSED_REPORT="unused_files_$(date +%Y%m%d_%H%M%S).md"

cat > $UNUSED_REPORT << 'EOF'
# 🗑️ FICHIERS POTENTIELLEMENT INUTILISÉS

## Méthodologie
- Recherche de références dans tout le codebase
- Analyse des @include, @extends, use, et route()
- ⚠️ Vérification manuelle requise pour confirmation

EOF

echo "## 🎯 ANALYSE DES VUES BLADE" >> $UNUSED_REPORT

# Analyser chaque vue Blade
find resources/views -name "*.blade.php" -type f 2>/dev/null | while read view_file; do
    # Obtenir le nom relatif de la vue
    view_name=$(echo "$view_file" | sed 's|resources/views/||' | sed 's|\.blade\.php||' | sed 's|/|.|g')
    
    # Chercher les références à cette vue
    references=0
    
    # Dans les contrôleurs (view(), return view())
    controllers_ref=$(find app/Http/Controllers -name "*.php" -exec grep -l "view.*['\"]$view_name['\"]" {} \; 2>/dev/null | wc -l)
    
    # Dans les routes (return view())
    routes_ref=$(find routes -name "*.php" -exec grep -l "view.*['\"]$view_name['\"]" {} \; 2>/dev/null | wc -l)
    
    # Dans d'autres vues (@include, @extends)
    views_ref=$(find resources/views -name "*.blade.php" -exec grep -l "@include.*['\"]$view_name['\"]" {} \; 2>/dev/null | wc -l)
    extends_ref=$(find resources/views -name "*.blade.php" -exec grep -l "@extends.*['\"]$view_name['\"]" {} \; 2>/dev/null | wc -l)
    
    total_refs=$((controllers_ref + routes_ref + views_ref + extends_ref))
    
    if [ $total_refs -eq 0 ]; then
        echo "### ❌ POTENTIELLEMENT INUTILISÉ: $view_file" >> $UNUSED_REPORT
        echo "- Nom vue: \`$view_name\`" >> $UNUSED_REPORT
        echo "- Références trouvées: $total_refs" >> $UNUSED_REPORT
        echo "" >> $UNUSED_REPORT
    fi
done

echo "## 🎛️ ANALYSE DES CONTRÔLEURS" >> $UNUSED_REPORT

# Analyser les contrôleurs
find app/Http/Controllers -name "*.php" -type f 2>/dev/null | while read controller_file; do
    # Extraire le nom de classe
    class_name=$(basename "$controller_file" .php)
    
    # Chercher dans les routes
    routes_ref=$(find routes -name "*.php" -exec grep -l "$class_name" {} \; 2>/dev/null | wc -l)
    
    # Chercher dans les tests
    tests_ref=$(find tests -name "*.php" -exec grep -l "$class_name" {} \; 2>/dev/null | wc -l)
    
    total_refs=$((routes_ref + tests_ref))
    
    if [ $total_refs -eq 0 ]; then
        echo "### ❌ CONTRÔLEUR POTENTIELLEMENT INUTILISÉ: $controller_file" >> $UNUSED_REPORT
        echo "- Classe: \`$class_name\`" >> $UNUSED_REPORT
        echo "- Références routes: $routes_ref" >> $UNUSED_REPORT
        echo "- Références tests: $tests_ref" >> $UNUSED_REPORT
        echo "" >> $UNUSED_REPORT
    fi
done

echo "✅ Rapport unused généré: $UNUSED_REPORT"
