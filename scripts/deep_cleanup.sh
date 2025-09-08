#!/bin/bash

# ================================================
# Script de nettoyage complet pour StudiosDB
# ================================================

echo "🧹 Nettoyage complet du projet StudiosDB"
echo "========================================="
echo ""

# Répertoire du projet
PROJECT_DIR="/home/studiosdb/studiosunisdb"
QUARANTINE_DIR="$PROJECT_DIR/quarantine/cleanup_$(date +%Y%m%d_%H%M%S)"

# Créer le répertoire de quarantaine avec timestamp
mkdir -p "$QUARANTINE_DIR"

echo "📦 Répertoire de quarantaine créé: $QUARANTINE_DIR"
echo ""

# ================================================
# 1. FICHIERS DE TEST À SUPPRIMER
# ================================================
echo "1️⃣ Déplacement des fichiers de test temporaires..."

TEST_FILES=(
    "test_*.sh"
    "demo-tests-*.sh"
    "debug_*.sh"
    "diagnostic_*.sh"
    "check-playwright.js"
    "complete-frontend-test.js"
    "create-test-data-*.sh"
    "cleanup_membres_references.sh"
    "migrate_membres_to_users.sh"
    "membre_system_access_component.vue"
)

for pattern in "${TEST_FILES[@]}"; do
    for file in $PROJECT_DIR/$pattern; do
        if [ -f "$file" ]; then
            echo "  📄 Déplacement: $(basename $file)"
            mv "$file" "$QUARANTINE_DIR/" 2>/dev/null
        fi
    done
done

# ================================================
# 2. SCRIPTS TEMPORAIRES ET OBSOLÈTES
# ================================================
echo ""
echo "2️⃣ Déplacement des scripts temporaires et obsolètes..."

TEMP_SCRIPTS=(
    "BACKUP.sh"
    "BACKUP_DATABASE.sh"
    "CORRECTED_plan_correction.sh"
    "FIX_GITHUB.sh"
    "GH.sh"
    "GITHUB.sh"
    "GITHUB_FIX.sh"
    "GITHUB_SUMMARY.sh"
    "GIT_CHECK.sh"
    "GO.sh"
    "HELP.sh"
    "SAVE.sh"
    "SAVE_PROJECT.sh"
    "SAUVEGARDE_COMPLETE.sh"
    "SAUVEGARDE_COMPLETE_PROJET.sh"
    "SETUP_GITHUB.sh"
    "START.sh"
    "STATUS.sh"
    "kill-all.sh"
    "kill-quick.sh"
    "quick-commit.sh"
    "quick-fix.sh"
    "quickstart.sh"
    "restart-project.sh"
    "restart-quick.sh"
    "start-dev.sh"
    "start-server.sh"
    "start-studiosdb.sh"
    "status.sh"
    "commit-sauvegarde.sh"
    "clean-cache.sh"
    "cleanup-diagnostic.sh"
    "cleanup-phase*.sh"
    "init_github.sh"
    "db_analyze_backup.sh"
)

for script in "${TEMP_SCRIPTS[@]}"; do
    if [ -f "$PROJECT_DIR/$script" ]; then
        echo "  📜 Déplacement: $script"
        mv "$PROJECT_DIR/$script" "$QUARANTINE_DIR/" 2>/dev/null
    fi
done

# ================================================
# 3. FICHIERS DE DOCUMENTATION TEMPORAIRES
# ================================================
echo ""
echo "3️⃣ Déplacement des documentations temporaires..."

TEMP_DOCS=(
    "AUDIT_CLEANUP_REPORT.md"
    "CRQ-20250828-01-laravel-update.yaml"
    "FIX_NAVIGATION.md"
    "GITHUB_GUIDE.md"
    "PLAN_CORRECTIONS_DB.md"
    "PLAYWRIGHT_VISUAL_GUIDE.md"
    "README-frontend-tests.md"
    "README_SCRIPTS.md"
    "README_STATUS.md"
)

for doc in "${TEMP_DOCS[@]}"; do
    if [ -f "$PROJECT_DIR/$doc" ]; then
        echo "  📚 Déplacement: $doc"
        mv "$PROJECT_DIR/$doc" "$QUARANTINE_DIR/" 2>/dev/null
    fi
done

# ================================================
# 4. FICHIERS PATCH ET DIFF
# ================================================
echo ""
echo "4️⃣ Déplacement des fichiers patch et diff..."

PATCH_FILES=(
    "*.patch"
    "*.diff"
)

for pattern in "${PATCH_FILES[@]}"; do
    for file in $PROJECT_DIR/$pattern; do
        if [ -f "$file" ]; then
            echo "  🔧 Déplacement: $(basename $file)"
            mv "$file" "$QUARANTINE_DIR/" 2>/dev/null
        fi
    done
done

# ================================================
# 5. FICHIERS DE STATUT ET TEMPORAIRES
# ================================================
echo ""
echo "5️⃣ Déplacement des fichiers de statut temporaires..."

STATUS_FILES=(
    ".backup_status"
    ".studiosdb_status"
    ".sync_status"
    "audit_backend.txt"
    "exit"
)

for file in "${STATUS_FILES[@]}"; do
    if [ -f "$PROJECT_DIR/$file" ]; then
        echo "  📊 Déplacement: $file"
        mv "$PROJECT_DIR/$file" "$QUARANTINE_DIR/" 2>/dev/null
    fi
done

# ================================================
# 6. FICHIERS DIAGNOSTIC PHP
# ================================================
echo ""
echo "6️⃣ Déplacement des fichiers diagnostic PHP..."

DIAG_FILES=(
    "diagnostic-user-permissions.php"
    "start.php"
    "vite-manager.php"
)

for file in "${DIAG_FILES[@]}"; do
    if [ -f "$PROJECT_DIR/$file" ]; then
        echo "  🔍 Déplacement: $file"
        mv "$PROJECT_DIR/$file" "$QUARANTINE_DIR/" 2>/dev/null
    fi
done

# ================================================
# 7. RÉPERTOIRES TEMPORAIRES
# ================================================
echo ""
echo "7️⃣ Déplacement des répertoires temporaires..."

TEMP_DIRS=(
    "temp_cleanup"
    "mkdir"
    "playwright-report"
    "test-results"
)

for dir in "${TEMP_DIRS[@]}"; do
    if [ -d "$PROJECT_DIR/$dir" ]; then
        echo "  📁 Déplacement: $dir/"
        mv "$PROJECT_DIR/$dir" "$QUARANTINE_DIR/" 2>/dev/null
    fi
done

# ================================================
# 8. FICHIERS DE BACKUP
# ================================================
echo ""
echo "8️⃣ Nettoyage des fichiers backup..."

# Trouver et déplacer tous les fichiers .backup et .backup.*
find "$PROJECT_DIR" -maxdepth 3 -type f -name "*.backup*" ! -path "*/quarantine/*" ! -path "*/.git/*" | while read -r file; do
    echo "  💾 Déplacement: $(basename $file)"
    mv "$file" "$QUARANTINE_DIR/" 2>/dev/null
done

# ================================================
# 9. CONFIGURATION PLAYWRIGHT
# ================================================
echo ""
echo "9️⃣ Déplacement des configs Playwright temporaires..."

PLAYWRIGHT_FILES=(
    "playwright-headless.config.js"
    "test-playwright-visual.sh"
)

for file in "${PLAYWRIGHT_FILES[@]}"; do
    if [ -f "$PROJECT_DIR/$file" ]; then
        echo "  🎭 Déplacement: $file"
        mv "$PROJECT_DIR/$file" "$QUARANTINE_DIR/" 2>/dev/null
    fi
done

# ================================================
# STATISTIQUES FINALES
# ================================================
echo ""
echo "📊 Statistiques du nettoyage:"
echo "================================"

# Compter les fichiers déplacés
MOVED_COUNT=$(find "$QUARANTINE_DIR" -type f 2>/dev/null | wc -l)
echo "  ✅ Fichiers déplacés: $MOVED_COUNT"

# Calculer l'espace libéré
if [ -d "$QUARANTINE_DIR" ]; then
    SPACE_FREED=$(du -sh "$QUARANTINE_DIR" 2>/dev/null | cut -f1)
    echo "  💾 Espace libéré: $SPACE_FREED"
fi

# Lister les fichiers restants à la racine
echo ""
echo "📋 Fichiers restants à la racine du projet:"
echo "==========================================="
ls -la "$PROJECT_DIR" | grep -E "^-" | awk '{print "  ✓", $9}'

echo ""
echo "✨ Nettoyage terminé!"
echo ""
echo "💡 Les fichiers ont été déplacés vers:"
echo "   $QUARANTINE_DIR"
echo ""
echo "⚠️  Pour supprimer définitivement les fichiers en quarantaine:"
echo "   rm -rf $QUARANTINE_DIR"
echo ""
echo "📌 Pour restaurer un fichier spécifique:"
echo "   mv $QUARANTINE_DIR/[nom_fichier] $PROJECT_DIR/"
