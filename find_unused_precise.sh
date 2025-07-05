#!/bin/bash

echo "🗑️ IDENTIFICATION PRÉCISE FICHIERS INUTILISÉS"
echo "============================================="

UNUSED_PRECISE="unused_precise_$(date +%Y%m%d_%H%M%S).md"

cat > $UNUSED_PRECISE << 'EOF'
# 🗑️ FICHIERS INUTILISÉS - ANALYSE PRÉCISE

## Méthodologie Améliorée
- Recherche dans tout le projet (pas seulement resources/views)
- Analyse cross-référence entre contrôleurs, routes, et vues
- Vérification des noms de fichiers vs usages réels

EOF

echo "## 🎯 VUES POTENTIELLEMENT INUTILISÉES" >> $UNUSED_PRECISE

# Analyser chaque vue individuellement
find resources/views -name "*.blade.php" -type f | while read view_file; do
    view_name=$(echo "$view_file" | sed 's|resources/views/||' | sed 's|\.blade\.php||' | sed 's|/|.|g')
    base_name=$(basename "$view_file" .blade.php)
    
    # Recherche exhaustive dans tout le projet
    total_refs=0
    
    # Dans les contrôleurs - plusieurs patterns
    ctrl_refs=$(grep -r "view.*['\"]$view_name['\"]" app/ 2>/dev/null | wc -l)
    ctrl_refs2=$(grep -r "view.*['\"].*$base_name['\"]" app/ 2>/dev/null | wc -l)
    
    # Dans les routes
    route_refs=$(grep -r "view.*['\"]$view_name['\"]" routes/ 2>/dev/null | wc -l)
    
    # Dans d'autres vues (includes/extends)
    view_refs=$(grep -r "@include.*['\"]$view_name['\"]" resources/views/ 2>/dev/null | wc -l)
    extend_refs=$(grep -r "@extends.*['\"]$view_name['\"]" resources/views/ 2>/dev/null | wc -l)
    
    # Dans les tests
    test_refs=$(grep -r "$view_name\|$base_name" tests/ 2>/dev/null | wc -l)
    
    total_refs=$((ctrl_refs + ctrl_refs2 + route_refs + view_refs + extend_refs + test_refs))
    
    if [ $total_refs -eq 0 ]; then
        echo "### ❌ INUTILISÉ: $view_file" >> $UNUSED_PRECISE
        echo "- **Nom vue:** \`$view_name\`" >> $UNUSED_PRECISE
        echo "- **Base name:** \`$base_name\`" >> $UNUSED_PRECISE
        echo "- **Références totales:** $total_refs" >> $UNUSED_PRECISE
        echo "- **Taille:** $(wc -l < "$view_file" 2>/dev/null || echo "0") lignes" >> $UNUSED_PRECISE
        echo "" >> $UNUSED_PRECISE
    elif [ $total_refs -lt 3 ]; then
        echo "### ⚠️ PEU UTILISÉ: $view_file" >> $UNUSED_PRECISE
        echo "- **Nom vue:** \`$view_name\`" >> $UNUSED_PRECISE
        echo "- **Références:** $total_refs" >> $UNUSED_PRECISE
        echo "- **Détail:** ctrl:$ctrl_refs route:$route_refs view:$view_refs extend:$extend_refs test:$test_refs" >> $UNUSED_PRECISE
        echo "" >> $UNUSED_PRECISE
    fi
done

echo "## 🎛️ CONTRÔLEURS INUTILISÉS" >> $UNUSED_PRECISE

find app/Http/Controllers -name "*.php" -type f | while read controller; do
    controller_name=$(basename "$controller" .php)
    class_name=$(grep -o "class [A-Za-z_][A-Za-z0-9_]*" "$controller" 2>/dev/null | cut -d' ' -f2)
    
    # Recherche dans routes
    route_refs=$(grep -r "$controller_name\|$class_name" routes/ 2>/dev/null | wc -l)
    
    # Recherche dans tests
    test_refs=$(grep -r "$controller_name\|$class_name" tests/ 2>/dev/null | wc -l)
    
    # Recherche dans config/autres
    config_refs=$(grep -r "$controller_name\|$class_name" config/ 2>/dev/null | wc -l)
    
    total_refs=$((route_refs + test_refs + config_refs))
    
    if [ $total_refs -eq 0 ]; then
        echo "### ❌ CONTRÔLEUR INUTILISÉ: $controller" >> $UNUSED_PRECISE
        echo "- **Classe:** \`$class_name\`" >> $UNUSED_PRECISE
        echo "- **Fichier:** \`$controller_name.php\`" >> $UNUSED_PRECISE
        echo "- **Références:** $total_refs" >> $UNUSED_PRECISE
        echo "- **Taille:** $(wc -l < "$controller" 2>/dev/null || echo "0") lignes" >> $UNUSED_PRECISE
        echo "" >> $UNUSED_PRECISE
    fi
done

echo "✅ Analyse précise terminée: $UNUSED_PRECISE"
