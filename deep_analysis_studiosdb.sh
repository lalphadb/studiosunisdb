#!/bin/bash

echo "🔍 ANALYSE APPROFONDIE STUDIOSDB"
echo "================================"

# Créer un rapport détaillé
DEEP_REPORT="deep_analysis_$(date +%Y%m%d_%H%M%S).md"

cat > $DEEP_REPORT << 'EOF'
# 🔍 ANALYSE APPROFONDIE STUDIOSDB

## 📁 STRUCTURE RÉELLE DÉTECTÉE
EOF

echo "## 📁 STRUCTURE RÉELLE DÉTECTÉE" >> $DEEP_REPORT
echo "" >> $DEEP_REPORT

# Afficher l'arborescence complète
echo "### Arborescence resources/views/" >> $DEEP_REPORT
echo "\`\`\`" >> $DEEP_REPORT
if [ -d "resources/views" ]; then
    tree resources/views/ -I "*.git" 2>/dev/null || find resources/views -type f -name "*.blade.php" | sort
fi >> $DEEP_REPORT
echo "\`\`\`" >> $DEEP_REPORT
echo "" >> $DEEP_REPORT

# Analyser le contenu des partials
echo "## 🧩 CONTENU DES PARTIALS" >> $DEEP_REPORT
if [ -d "resources/views/partials" ]; then
    find resources/views/partials -name "*.blade.php" | while read partial; do
        echo "### $(basename $partial)" >> $DEEP_REPORT
        echo "**Chemin:** \`$partial\`" >> $DEEP_REPORT
        echo "**Taille:** $(wc -l < "$partial" 2>/dev/null || echo "0") lignes" >> $DEEP_REPORT
        echo "" >> $DEEP_REPORT
        echo "**Contenu (premières lignes):**" >> $DEEP_REPORT
        echo "\`\`\`blade" >> $DEEP_REPORT
        head -10 "$partial" 2>/dev/null >> $DEEP_REPORT
        echo "\`\`\`" >> $DEEP_REPORT
        echo "" >> $DEEP_REPORT
        
        # Chercher où il est utilisé
        partial_name=$(echo "$partial" | sed 's|resources/views/||' | sed 's|\.blade\.php||' | sed 's|/|.|g')
        echo "**Utilisé dans:**" >> $DEEP_REPORT
        grep -r "@include.*$partial_name" resources/views/ 2>/dev/null | while read usage; do
            echo "- \`$(echo $usage | cut -d: -f1)\`" >> $DEEP_REPORT
        done
        echo "" >> $DEEP_REPORT
    done
fi

# Analyser le contenu des components
echo "## 🧩 CONTENU DES COMPONENTS" >> $DEEP_REPORT
if [ -d "resources/views/components" ]; then
    find resources/views/components -name "*.blade.php" | head -10 | while read component; do
        echo "### $(echo $component | sed 's|resources/views/components/||')" >> $DEEP_REPORT
        echo "**Chemin:** \`$component\`" >> $DEEP_REPORT
        echo "**Taille:** $(wc -l < "$component" 2>/dev/null || echo "0") lignes" >> $DEEP_REPORT
        echo "" >> $DEEP_REPORT
        echo "**Contenu (premières lignes):**" >> $DEEP_REPORT
        echo "\`\`\`blade" >> $DEEP_REPORT
        head -5 "$component" 2>/dev/null >> $DEEP_REPORT
        echo "\`\`\`" >> $DEEP_REPORT
        echo "" >> $DEEP_REPORT
    done
fi

# Analyser les contrôleurs pour identifier les inutilisés
echo "## 🎛️ ANALYSE CONTRÔLEURS" >> $DEEP_REPORT
if [ -d "app/Http/Controllers" ]; then
    find app/Http/Controllers -name "*Controller.php" | while read controller; do
        controller_name=$(basename "$controller" .php)
        
        # Chercher dans les routes
        route_usage=$(find routes/ -name "*.php" -exec grep -l "$controller_name" {} \; 2>/dev/null | wc -l)
        
        if [ $route_usage -eq 0 ]; then
            echo "### ❌ CONTRÔLEUR POTENTIELLEMENT INUTILISÉ" >> $DEEP_REPORT
            echo "- **Fichier:** \`$controller\`" >> $DEEP_REPORT
            echo "- **Classe:** \`$controller_name\`" >> $DEEP_REPORT
            echo "- **Références routes:** $route_usage" >> $DEEP_REPORT
            echo "" >> $DEEP_REPORT
        fi
    done
fi

# Analyser le layout admin pour comprendre la structure
echo "## 📄 ANALYSE LAYOUT ADMIN" >> $DEEP_REPORT
if [ -f "resources/views/layouts/admin.blade.php" ]; then
    echo "**Fichier:** \`resources/views/layouts/admin.blade.php\`" >> $DEEP_REPORT
    echo "" >> $DEEP_REPORT
    echo "**Includes détectés:**" >> $DEEP_REPORT
    grep "@include\|<x-" resources/views/layouts/admin.blade.php 2>/dev/null | while read include_line; do
        echo "- \`$include_line\`" >> $DEEP_REPORT
    done
fi

echo "✅ Analyse approfondie terminée: $DEEP_REPORT"
