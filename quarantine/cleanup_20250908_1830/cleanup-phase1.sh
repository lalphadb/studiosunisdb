#!/bin/bash

# StudiosDB - Nettoyage Phase 1 (SANS RISQUE)
# Suppression fichiers test/dev/obsolètes confirmés

echo "=== STUDIOSDB - NETTOYAGE PHASE 1 ==="
echo "Suppression fichiers obsolètes sans risque..."
echo ""

# Sauvegarde de sécurité
BACKUP_DIR="backups/cleanup-phase1-$(date +%Y%m%d_%H%M%S)"
mkdir -p "$BACKUP_DIR"

echo "📁 Sauvegarde dans: $BACKUP_DIR"

# === PUBLIC - Fichiers de test ===
echo ""
echo "🗑️  PUBLIC - Fichiers test/dev..."

if [ -f "public/cache-cleared.php" ]; then
    cp public/cache-cleared.php "$BACKUP_DIR/" && rm public/cache-cleared.php
    echo "  ✅ cache-cleared.php supprimé"
fi

if [ -f "public/phptest.php" ]; then
    cp public/phptest.php "$BACKUP_DIR/" && rm public/phptest.php  
    echo "  ✅ phptest.php supprimé"
fi

if [ -f "public/session-cleanup.html" ]; then
    cp public/session-cleanup.html "$BACKUP_DIR/" && rm public/session-cleanup.html
    echo "  ✅ session-cleanup.html supprimé"
fi

if [ -f "public/dashboard-test.html" ]; then
    cp public/dashboard-test.html "$BACKUP_DIR/" && rm public/dashboard-test.html
    echo "  ✅ dashboard-test.html supprimé"
fi

if [ -f "public/test-dashboard.html" ]; then
    cp public/test-dashboard.html "$BACKUP_DIR/" && rm public/test-dashboard.html
    echo "  ✅ test-dashboard.html supprimé"
fi

# Artefacts Vite
if [ -f "public/hot" ]; then
    cp public/hot "$BACKUP_DIR/" 2>/dev/null && rm public/hot
    echo "  ✅ hot (Vite dev) supprimé"
fi

if [ -L "public/storage" ]; then
    rm public/storage
    echo "  ✅ storage (lien symbolique) supprimé"
fi

if [ -d "public/build" ]; then
    cp -r public/build "$BACKUP_DIR/" 2>/dev/null && rm -rf public/build
    echo "  ✅ build/ (artefacts assets) supprimé"
fi

# === CONFIG - Ziggy obsolète ===
echo ""
echo "⚙️  CONFIG - Fichiers obsolètes..."

if [ -f "config/ziggy.php" ]; then
    cp config/ziggy.php "$BACKUP_DIR/" && rm config/ziggy.php
    echo "  ✅ ziggy.php supprimé (Ziggy retiré du projet)"
fi

# === PAGES - Démo/test confirmées ===
echo ""
echo "🖼️  PAGES - Fichiers démo/test..."

if [ -f "resources/js/Pages/Welcome.vue" ]; then
    cp resources/js/Pages/Welcome.vue "$BACKUP_DIR/" && rm resources/js/Pages/Welcome.vue
    echo "  ✅ Welcome.vue supprimé (exemple Laravel)"
fi

if [ -f "resources/js/Pages/ExempleTheme.vue" ]; then
    cp resources/js/Pages/ExempleTheme.vue "$BACKUP_DIR/" && rm resources/js/Pages/ExempleTheme.vue
    echo "  ✅ ExempleTheme.vue supprimé (PoC thème)"
fi

if [ -f "resources/js/Pages/TestSimple.vue" ]; then
    cp resources/js/Pages/TestSimple.vue "$BACKUP_DIR/" && rm resources/js/Pages/TestSimple.vue
    echo "  ✅ TestSimple.vue supprimé (test composant)"
fi

if [ -f "resources/js/Pages/Cours/IndexNew.vue" ]; then
    cp resources/js/Pages/Cours/IndexNew.vue "$BACKUP_DIR/" && rm resources/js/Pages/Cours/IndexNew.vue
    echo "  ✅ Cours/IndexNew.vue supprimé (variante non routée)"
fi

# === RÉSUMÉ ===
echo ""
echo "=== RÉSUMÉ PHASE 1 ==="
DELETED_COUNT=$(ls -1 "$BACKUP_DIR" 2>/dev/null | wc -l)
echo "📊 Fichiers supprimés: $DELETED_COUNT"
echo "💾 Sauvegarde: $BACKUP_DIR"
echo ""
echo "✅ NETTOYAGE PHASE 1 TERMINÉ (SANS RISQUE)"
echo ""
echo "🧪 TESTS RECOMMANDÉS:"
echo "   npm run build    # Vérifier build OK"
echo "   npm run dev      # Vérifier serveur démarre"
echo "   # Tester pages: /dashboard, /membres"
echo ""
echo "🔄 ROLLBACK POSSIBLE:"
echo "   cp $BACKUP_DIR/* public/ config/ resources/js/Pages/ (selon fichier)"
echo ""
echo "📋 NEXT: Voir AUDIT_CLEANUP_REPORT.md pour PHASE 2 (composants doublons)"
