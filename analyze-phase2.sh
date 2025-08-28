#!/bin/bash

# StudiosDB - Analyse Phase 2 (COMPOSANTS)
# Vérification imports effectifs avant suppression

echo "=== STUDIOSDB - ANALYSE PHASE 2 ==="
echo "Vérification utilisation composants suspects..."
echo ""

# Fonction de recherche d'imports
check_component_usage() {
    local component_path="$1"
    local component_name=$(basename "$component_path" .vue)
    
    echo "🔍 Analysing: $component_name"
    
    # Rechercher imports directs
    local direct_imports=$(find resources/js -name "*.vue" -o -name "*.js" | xargs grep -l "import.*$component_name" 2>/dev/null | wc -l)
    
    # Rechercher utilisations dans templates
    local template_usage=$(find resources/js -name "*.vue" | xargs grep -l "<$component_name" 2>/dev/null | wc -l)
    
    echo "  📥 Imports directs: $direct_imports"
    echo "  🏷️  Utilisations template: $template_usage"
    
    if [ $direct_imports -eq 0 ] && [ $template_usage -eq 0 ]; then
        echo "  ❌ NON UTILISÉ - Candidat suppression"
        return 0
    else
        echo "  ✅ UTILISÉ - Conserver"
        return 1
    fi
}

# === ANALYSE COMPOSANTS SUSPECTS ===
echo "=== COMPOSANTS DOUBLONS POTENTIELS ==="

# Array des composants à analyser
SUSPECT_COMPONENTS=(
    "resources/js/Components/ActionCard.vue"
    "resources/js/Components/ModernStatsCard.vue"
    "resources/js/Components/Footer.vue"
    "resources/js/Components/UiButton.vue"
    "resources/js/Components/UiCard.vue"
    "resources/js/Components/UiInput.vue"
    "resources/js/Components/UiSelect.vue"
    "resources/js/Components/Checkbox.vue"
    "resources/js/Components/Dashboard/StatsCard.vue"
    "resources/js/Components/Dashboard/ProgressBar.vue"
)

SAFE_TO_DELETE=()
KEEP_COMPONENTS=()

for component in "${SUSPECT_COMPONENTS[@]}"; do
    if [ -f "$component" ]; then
        echo ""
        if check_component_usage "$component"; then
            SAFE_TO_DELETE+=("$component")
        else
            KEEP_COMPONENTS+=("$component")
        fi
    else
        echo "⚠️  $component n'existe pas"
    fi
done

echo ""
echo "=== COMPOSANTS UI MODERNES ==="

UI_MODERN_COMPONENTS=(
    "resources/js/Components/UI/ModernButton.vue"
    "resources/js/Components/UI/ModernNotification.vue" 
    "resources/js/Components/UI/ModernStatsCard.vue"
    "resources/js/Components/UI/AnimatedNumber.vue"
)

for component in "${UI_MODERN_COMPONENTS[@]}"; do
    if [ -f "$component" ]; then
        echo ""
        if check_component_usage "$component"; then
            SAFE_TO_DELETE+=("$component")
        else
            KEEP_COMPONENTS+=("$component")
        fi
    fi
done

echo ""
echo "=== MEMBRES MODALS (FUTURS) ==="

MEMBERS_COMPONENTS=(
    "resources/js/Components/Members/CreateModal.vue"
    "resources/js/Components/Members/EditModal.vue"
    "resources/js/Components/Members/FamilyLinksModal.vue"
)

for component in "${MEMBERS_COMPONENTS[@]}"; do
    if [ -f "$component" ]; then
        echo ""
        echo "🔍 Analysing: $(basename "$component" .vue) [MODULE FUTUR]"
        if check_component_usage "$component"; then
            echo "  ⚠️  Prévu pour module Membres - RECOMMANDATION: Conserver"
        else
            echo "  ✅ Module Membres actif - Conserver"
        fi
        KEEP_COMPONENTS+=("$component")
    fi
done

echo ""
echo "=== RÉSULTATS ANALYSE ==="
echo ""
echo "❌ SAFE TO DELETE (${#SAFE_TO_DELETE[@]} composants):"
for comp in "${SAFE_TO_DELETE[@]}"; do
    echo "   $comp"
done

echo ""
echo "✅ KEEP (${#KEEP_COMPONENTS[@]} composants):"
for comp in "${KEEP_COMPONENTS[@]}"; do
    echo "   $comp"
done

# Générer script de suppression
if [ ${#SAFE_TO_DELETE[@]} -gt 0 ]; then
    echo ""
    echo "📝 Génération script suppression..."
    
    cat > cleanup-phase2-auto.sh << 'EOF'
#!/bin/bash
# Généré automatiquement par analyse-phase2.sh

echo "=== NETTOYAGE PHASE 2 - COMPOSANTS INUTILISÉS ==="

BACKUP_DIR="backups/cleanup-phase2-$(date +%Y%m%d_%H%M%S)"
mkdir -p "$BACKUP_DIR"

echo "📁 Sauvegarde: $BACKUP_DIR"
EOF

    for comp in "${SAFE_TO_DELETE[@]}"; do
        echo "
if [ -f \"$comp\" ]; then
    cp \"$comp\" \"$BACKUP_DIR/\" && rm \"$comp\"
    echo \"  ✅ $(basename $comp) supprimé\"
fi" >> cleanup-phase2-auto.sh
    done
    
    echo '
echo ""
echo "✅ PHASE 2 TERMINÉE"
echo "📊 Composants supprimés: '$((${#SAFE_TO_DELETE[@]}))')"
echo "💾 Sauvegarde: $BACKUP_DIR"
echo ""
echo "🧪 TESTS REQUIS:"
echo "   npm run build && npm run dev"
echo "   Tester toutes les pages principales"
' >> cleanup-phase2-auto.sh

    chmod +x cleanup-phase2-auto.sh
    echo "   ➡️  cleanup-phase2-auto.sh créé"
fi

echo ""
echo "🎯 RECOMMANDATIONS:"
echo "   1. Tester le build: npm run build"
echo "   2. Si OK, exécuter: ./cleanup-phase2-auto.sh" 
echo "   3. Tester toutes les pages après suppression"
echo "   4. Vérifier console erreurs (imports manquants)"
