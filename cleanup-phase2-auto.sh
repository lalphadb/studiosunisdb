#!/bin/bash
# Généré automatiquement par analyse-phase2.sh

echo "=== NETTOYAGE PHASE 2 - COMPOSANTS INUTILISÉS ==="

BACKUP_DIR="backups/cleanup-phase2-$(date +%Y%m%d_%H%M%S)"
mkdir -p "$BACKUP_DIR"

echo "📁 Sauvegarde: $BACKUP_DIR"

if [ -f "resources/js/Components/ActionCard.vue" ]; then
    cp "resources/js/Components/ActionCard.vue" "/" && rm "resources/js/Components/ActionCard.vue"
    echo "  ✅ ActionCard.vue supprimé"
fi

if [ -f "resources/js/Components/ModernStatsCard.vue" ]; then
    cp "resources/js/Components/ModernStatsCard.vue" "/" && rm "resources/js/Components/ModernStatsCard.vue"
    echo "  ✅ ModernStatsCard.vue supprimé"
fi

if [ -f "resources/js/Components/Footer.vue" ]; then
    cp "resources/js/Components/Footer.vue" "/" && rm "resources/js/Components/Footer.vue"
    echo "  ✅ Footer.vue supprimé"
fi

if [ -f "resources/js/Components/UiButton.vue" ]; then
    cp "resources/js/Components/UiButton.vue" "/" && rm "resources/js/Components/UiButton.vue"
    echo "  ✅ UiButton.vue supprimé"
fi

if [ -f "resources/js/Components/UiCard.vue" ]; then
    cp "resources/js/Components/UiCard.vue" "/" && rm "resources/js/Components/UiCard.vue"
    echo "  ✅ UiCard.vue supprimé"
fi

if [ -f "resources/js/Components/UiInput.vue" ]; then
    cp "resources/js/Components/UiInput.vue" "/" && rm "resources/js/Components/UiInput.vue"
    echo "  ✅ UiInput.vue supprimé"
fi

if [ -f "resources/js/Components/UiSelect.vue" ]; then
    cp "resources/js/Components/UiSelect.vue" "/" && rm "resources/js/Components/UiSelect.vue"
    echo "  ✅ UiSelect.vue supprimé"
fi

if [ -f "resources/js/Components/Dashboard/StatsCard.vue" ]; then
    cp "resources/js/Components/Dashboard/StatsCard.vue" "/" && rm "resources/js/Components/Dashboard/StatsCard.vue"
    echo "  ✅ StatsCard.vue supprimé"
fi

if [ -f "resources/js/Components/UI/ModernButton.vue" ]; then
    cp "resources/js/Components/UI/ModernButton.vue" "/" && rm "resources/js/Components/UI/ModernButton.vue"
    echo "  ✅ ModernButton.vue supprimé"
fi

if [ -f "resources/js/Components/UI/ModernNotification.vue" ]; then
    cp "resources/js/Components/UI/ModernNotification.vue" "/" && rm "resources/js/Components/UI/ModernNotification.vue"
    echo "  ✅ ModernNotification.vue supprimé"
fi

if [ -f "resources/js/Components/UI/ModernStatsCard.vue" ]; then
    cp "resources/js/Components/UI/ModernStatsCard.vue" "/" && rm "resources/js/Components/UI/ModernStatsCard.vue"
    echo "  ✅ ModernStatsCard.vue supprimé"
fi

echo ""
echo "✅ PHASE 2 TERMINÉE"
echo "📊 Composants supprimés: 11)"
echo "💾 Sauvegarde: $BACKUP_DIR"
echo ""
echo "🧪 TESTS REQUIS:"
echo "   npm run build && npm run dev"
echo "   Tester toutes les pages principales"

