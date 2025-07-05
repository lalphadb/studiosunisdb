#!/bin/bash

echo "🗺️ CARTOGRAPHIE COMPLÈTE D'USAGE DES FICHIERS"
echo "============================================="

USAGE_MAP="file_usage_map_$(date +%Y%m%d_%H%M%S).md"

cat > $USAGE_MAP << 'EOF'
# 🗺️ CARTOGRAPHIE D'USAGE - STUDIOSDB

## Méthodologie
Analyse des dépendances entre tous les fichiers du projet

EOF

echo "## 🎛️ CONTROLLERS → VUES" >> $USAGE_MAP

find app/Http/Controllers -name "*.php" -type f | while read controller; do
    echo "### $(basename $controller)" >> $USAGE_MAP
    echo "- **Chemin:** \`$controller\`" >> $USAGE_MAP
    
    # Extraire les vues utilisées
    views=$(grep -o "view(['\"][^'\"]*['\"]" "$controller" 2>/dev/null | sed "s/view(['\"]//g" | sed "s/['\"]//g")
    if [ ! -z "$views" ]; then
        echo "- **Vues utilisées:**" >> $USAGE_MAP
        echo "$views" | while read view; do
            view_file="resources/views/$(echo $view | sed 's/\./\//g').blade.php"
            if [ -f "$view_file" ]; then
                echo "  - ✅ \`$view\` → \`$view_file\`" >> $USAGE_MAP
            else
                echo "  - ❌ \`$view\` → **FICHIER MANQUANT**" >> $USAGE_MAP
            fi
        done
    else
        echo "- **Aucune vue détectée**" >> $USAGE_MAP
    fi
    
    # Extraire les routes
    routes=$(grep -o "route(['\"][^'\"]*['\"]" "$controller" 2>/dev/null | sed "s/route(['\"]//g" | sed "s/['\"]//g")
    if [ ! -z "$routes" ]; then
        echo "- **Routes utilisées:**" >> $USAGE_MAP
        echo "$routes" | while read route; do
            echo "  - \`$route\`" >> $USAGE_MAP
        done
    fi
    echo "" >> $USAGE_MAP
done

echo "## 🛣️ ROUTES → CONTROLLERS" >> $USAGE_MAP

find routes -name "*.php" -type f | while read route_file; do
    echo "### $(basename $route_file)" >> $USAGE_MAP
    echo "- **Chemin:** \`$route_file\`" >> $USAGE_MAP
    
    # Extraire les contrôleurs utilisés
    controllers=$(grep -o "[A-Za-z_][A-Za-z0-9_]*Controller::" "$route_file" 2>/dev/null | sed 's/:://g' | sort | uniq)
    if [ ! -z "$controllers" ]; then
        echo "- **Contrôleurs référencés:**" >> $USAGE_MAP
        echo "$controllers" | while read ctrl; do
            ctrl_file=$(find app/Http/Controllers -name "$ctrl.php" -type f)
            if [ ! -z "$ctrl_file" ]; then
                echo "  - ✅ \`$ctrl\` → \`$ctrl_file\`" >> $USAGE_MAP
            else
                echo "  - ❌ \`$ctrl\` → **FICHIER MANQUANT**" >> $USAGE_MAP
            fi
        done
    fi
    echo "" >> $USAGE_MAP
done

echo "## 👁️ VUES → INCLUDES/EXTENDS" >> $USAGE_MAP

find resources/views -name "*.blade.php" -type f | while read view; do
    view_name=$(echo "$view" | sed 's|resources/views/||')
    
    # Chercher extends et includes
    extends=$(grep -o "@extends(['\"][^'\"]*['\"])" "$view" 2>/dev/null)
    includes=$(grep -o "@include(['\"][^'\"]*['\"])" "$view" 2>/dev/null)
    components=$(grep -o "<x-[a-zA-Z0-9.-]*" "$view" 2>/dev/null | sed 's/<x-//g')
    
    if [ ! -z "$extends" ] || [ ! -z "$includes" ] || [ ! -z "$components" ]; then
        echo "### $view_name" >> $USAGE_MAP
        
        if [ ! -z "$extends" ]; then
            echo "- **Extends:**" >> $USAGE_MAP
            echo "$extends" | while read ext; do
                ext_clean=$(echo $ext | sed "s/@extends(['\"]//g" | sed "s/['\"])//g")
                ext_file="resources/views/$(echo $ext_clean | sed 's/\./\//g').blade.php"
                if [ -f "$ext_file" ]; then
                    echo "  - ✅ \`$ext_clean\` → \`$ext_file\`" >> $USAGE_MAP
                else
                    echo "  - ❌ \`$ext_clean\` → **FICHIER MANQUANT**" >> $USAGE_MAP
                fi
            done
        fi
        
        if [ ! -z "$includes" ]; then
            echo "- **Includes:**" >> $USAGE_MAP
            echo "$includes" | while read inc; do
                inc_clean=$(echo $inc | sed "s/@include(['\"]//g" | sed "s/['\"])//g")
                inc_file="resources/views/$(echo $inc_clean | sed 's/\./\//g').blade.php"
                if [ -f "$inc_file" ]; then
                    echo "  - ✅ \`$inc_clean\` → \`$inc_file\`" >> $USAGE_MAP
                else
                    echo "  - ❌ \`$inc_clean\` → **FICHIER MANQUANT**" >> $USAGE_MAP
                fi
            done
        fi
        
        if [ ! -z "$components" ]; then
            echo "- **Components:**" >> $USAGE_MAP
            echo "$components" | while read comp; do
                comp_file="resources/views/components/$(echo $comp | sed 's/\./\//g').blade.php"
                if [ -f "$comp_file" ]; then
                    echo "  - ✅ \`<x-$comp>\` → \`$comp_file\`" >> $USAGE_MAP
                else
                    echo "  - ❌ \`<x-$comp>\` → **FICHIER MANQUANT**" >> $USAGE_MAP
                fi
            done
        fi
        echo "" >> $USAGE_MAP
    fi
done

echo "✅ Cartographie complète générée: $USAGE_MAP"
