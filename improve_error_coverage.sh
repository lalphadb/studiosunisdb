#!/bin/bash

echo "📈 AMÉLIORATION COUVERTURE @error"
echo "================================="

# Identifier les vues avec formulaires sans @error
echo "🔍 Vues avec formulaires sans @error:"
find resources/views -name "*.blade.php" -exec grep -L "@error" {} \; | xargs grep -l "<form" | while read file; do
    echo "📝 Analyse: $file"
    
    # Vérifier si c'est un formulaire POST
    if grep -q "method.*POST\|@csrf" "$file"; then
        echo "  ⚠️ Formulaire POST sans @error - doit être corrigé"
        
        # Ajouter @error générique après chaque input
        if grep -q "<input\|x-text-input" "$file"; then
            echo "  🔧 Inputs détectés - ajouter @error"
        fi
    else
        echo "  ℹ️ Formulaire GET - @error optionnel"
    fi
done

echo ""
echo "📊 Statistiques @error:"
total_with_error=$(find resources/views -name "*.blade.php" -exec grep -l "@error" {} \; | wc -l)
total_with_forms=$(find resources/views -name "*.blade.php" -exec grep -l "<form" {} \; | wc -l)
echo "Actuel: $total_with_error/$total_with_forms"

