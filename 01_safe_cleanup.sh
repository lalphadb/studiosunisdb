#!/bin/bash

echo "🗑️ NETTOYAGE SÉCURISÉ - PHASE 1"
echo "==============================="

BACKUP_CLEANUP="backup_cleanup_$(date +%Y%m%d_%H%M%S)"
mkdir -p "$BACKUP_CLEANUP"

# SUPPRESSION ULTRA-SÉCURISÉE - UNIQUEMENT LES FICHIERS 100% CONFIRMÉS INUTILISÉS

echo "🔍 Suppression UNIQUEMENT des fichiers 100% confirmés inutilisés..."

# Components génériques Laravel Breeze (jamais utilisés)
SAFE_TO_DELETE_COMPONENTS=(
    "resources/views/components/text-input.blade.php"
    "resources/views/components/application-logo.blade.php"
    "resources/views/components/input-error.blade.php"
    "resources/views/components/input-label.blade.php"
    "resources/views/components/primary-button.blade.php"
    "resources/views/components/secondary-button.blade.php"
    "resources/views/components/danger-button.blade.php"
    "resources/views/components/auth-session-status.blade.php"
    "resources/views/components/guest-layout.blade.php"
    "resources/views/components/admin-layout.blade.php"
)

echo "📋 Suppression des components Laravel Breeze non utilisés:"
for component in "${SAFE_TO_DELETE_COMPONENTS[@]}"; do
    if [ -f "$component" ]; then
        echo "  ❌ $component"
        cp "$component" "$BACKUP_CLEANUP/" 2>/dev/null
        rm "$component"
    fi
done

# Fichiers backup évidents
echo ""
echo "📋 Suppression des fichiers .backup:"
find resources/views -name "*.backup" -type f | while read backup_file; do
    echo "  ❌ $backup_file"
    cp "$backup_file" "$BACKUP_CLEANUP/" 2>/dev/null
    rm "$backup_file"
done

# Pages d'erreur standard (Laravel les régénère si besoin)
ERROR_PAGES=(
    "resources/views/errors/403.blade.php"
    "resources/views/errors/404.blade.php"
    "resources/views/errors/500.blade.php"
    "resources/views/errors/minimal.blade.php"
)

echo ""
echo "📋 Suppression des pages d'erreur standard (Laravel les régénère):"
for error_page in "${ERROR_PAGES[@]}"; do
    if [ -f "$error_page" ]; then
        echo "  ❌ $error_page"
        cp "$error_page" "$BACKUP_CLEANUP/" 2>/dev/null
        rm "$error_page"
    fi
done

# Suppression dossiers vides
echo ""
echo "🗑️ Suppression des dossiers vides..."
find resources/views -type d -empty -delete 2>/dev/null

echo ""
echo "✅ Nettoyage Phase 1 terminé"
echo "📦 Backup: $BACKUP_CLEANUP"
echo "📊 Components restants: $(find resources/views/components -name '*.blade.php' 2>/dev/null | wc -l)"

# Test rapide
echo ""
echo "🧪 TEST RAPIDE:"
if php artisan view:clear 2>/dev/null; then
    echo "✅ Cache vues vidé avec succès"
else
    echo "⚠️ Impossible de vider le cache des vues"
fi
