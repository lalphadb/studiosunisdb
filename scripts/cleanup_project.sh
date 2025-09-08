#!/bin/bash

# ===================================================================
# Script de nettoyage complet du projet StudiosDB
# ===================================================================

echo "🧹 Début du nettoyage complet du projet StudiosDB..."
echo "=================================================="
echo ""

# Répertoire du projet
PROJECT_DIR="/home/studiosdb/studiosunisdb"
QUARANTINE_DIR="$PROJECT_DIR/quarantine/cleanup_$(date +%Y%m%d_%H%M%S)"

# Créer le répertoire de quarantaine
mkdir -p "$QUARANTINE_DIR"

# Fonction pour déplacer un fichier en quarantaine
move_to_quarantine() {
    local file="$1"
    if [ -e "$file" ]; then
        echo "  📦 Déplacement: $(basename "$file")"
        mv "$file" "$QUARANTINE_DIR/" 2>/dev/null
    fi
}

# ===================================================================
# 1. FICHIERS DE SCRIPTS TEMPORAIRES ET DEBUG
# ===================================================================
echo "1️⃣ Nettoyage des scripts temporaires et de debug..."
echo "-----------------------------------------------------"

# Scripts de test temporaires
for file in "$PROJECT_DIR"/test_*.sh; do
    move_to_quarantine "$file"
done

# Scripts de debug et diagnostic
move_to_quarantine "$PROJECT_DIR/debug_permissions_utilisateurs.sh"
move_to_quarantine "$PROJECT_DIR/diagnostic_db.sh"
move_to_quarantine "$PROJECT_DIR/diagnostic-user-permissions.php"
move_to_quarantine "$PROJECT_DIR/cleanup_membres_references.sh"
move_to_quarantine "$PROJECT_DIR/migrate_membres_to_users.sh"

# Scripts de création de données de test
move_to_quarantine "$PROJECT_DIR/create-test-data-membres.sh"
move_to_quarantine "$PROJECT_DIR/demo-tests-membres.sh"

# ===================================================================
# 2. SCRIPTS GITHUB REDONDANTS
# ===================================================================
echo ""
echo "2️⃣ Nettoyage des scripts GitHub redondants..."
echo "-----------------------------------------------------"

# Scripts GitHub dupliqués (on garde seulement le principal)
move_to_quarantine "$PROJECT_DIR/GITHUB.sh"
move_to_quarantine "$PROJECT_DIR/GITHUB_FIX.sh"
move_to_quarantine "$PROJECT_DIR/GITHUB_SUMMARY.sh"
move_to_quarantine "$PROJECT_DIR/FIX_GITHUB.sh"
move_to_quarantine "$PROJECT_DIR/GH.sh"
move_to_quarantine "$PROJECT_DIR/GIT_CHECK.sh"
move_to_quarantine "$PROJECT_DIR/SETUP_GITHUB.sh"
move_to_quarantine "$PROJECT_DIR/init_github.sh"

# ===================================================================
# 3. SCRIPTS DE DÉMARRAGE REDONDANTS
# ===================================================================
echo ""
echo "3️⃣ Consolidation des scripts de démarrage..."
echo "-----------------------------------------------------"

# Garder seulement start-dev.sh et supprimer les autres
move_to_quarantine "$PROJECT_DIR/START.sh"
move_to_quarantine "$PROJECT_DIR/start-studiosdb.sh"
move_to_quarantine "$PROJECT_DIR/start-server.sh"
move_to_quarantine "$PROJECT_DIR/quickstart.sh"
move_to_quarantine "$PROJECT_DIR/restart-project.sh"
move_to_quarantine "$PROJECT_DIR/restart-quick.sh"

# ===================================================================
# 4. SCRIPTS DE SAUVEGARDE REDONDANTS
# ===================================================================
echo ""
echo "4️⃣ Consolidation des scripts de sauvegarde..."
echo "-----------------------------------------------------"

move_to_quarantine "$PROJECT_DIR/BACKUP.sh"
move_to_quarantine "$PROJECT_DIR/BACKUP_DATABASE.sh"
move_to_quarantine "$PROJECT_DIR/SAVE.sh"
move_to_quarantine "$PROJECT_DIR/SAVE_PROJECT.sh"
move_to_quarantine "$PROJECT_DIR/SAUVEGARDE_COMPLETE.sh"
move_to_quarantine "$PROJECT_DIR/SAUVEGARDE_COMPLETE_PROJET.sh"
move_to_quarantine "$PROJECT_DIR/commit-sauvegarde.sh"
move_to_quarantine "$PROJECT_DIR/quick-commit.sh"

# ===================================================================
# 5. SCRIPTS KILL DANGEREUX
# ===================================================================
echo ""
echo "5️⃣ Suppression des scripts kill dangereux..."
echo "-----------------------------------------------------"

move_to_quarantine "$PROJECT_DIR/kill-all.sh"
move_to_quarantine "$PROJECT_DIR/kill-quick.sh"

# ===================================================================
# 6. FICHIERS DE CLEANUP OBSOLÈTES
# ===================================================================
echo ""
echo "6️⃣ Nettoyage des anciens scripts de cleanup..."
echo "-----------------------------------------------------"

move_to_quarantine "$PROJECT_DIR/cleanup-phase1.sh"
move_to_quarantine "$PROJECT_DIR/cleanup-phase2-auto.sh"
move_to_quarantine "$PROJECT_DIR/cleanup-diagnostic.sh"
move_to_quarantine "$PROJECT_DIR/clean-cache.sh"

# ===================================================================
# 7. FICHIERS DE PATCH ET CORRECTIONS APPLIQUÉS
# ===================================================================
echo ""
echo "7️⃣ Nettoyage des patches appliqués..."
echo "-----------------------------------------------------"

move_to_quarantine "$PROJECT_DIR/cours_controller_dropdown_fix.patch"
move_to_quarantine "$PROJECT_DIR/course_index_ui_patch.diff"
move_to_quarantine "$PROJECT_DIR/CORRECTED_plan_correction.sh"
move_to_quarantine "$PROJECT_DIR/quick-fix.sh"

# ===================================================================
# 8. FICHIERS DE DOCUMENTATION OBSOLÈTES
# ===================================================================
echo ""
echo "8️⃣ Nettoyage de la documentation obsolète..."
echo "-----------------------------------------------------"

move_to_quarantine "$PROJECT_DIR/AUDIT_CLEANUP_REPORT.md"
move_to_quarantine "$PROJECT_DIR/FIX_NAVIGATION.md"
move_to_quarantine "$PROJECT_DIR/GITHUB_GUIDE.md"
move_to_quarantine "$PROJECT_DIR/README_SCRIPTS.md"
move_to_quarantine "$PROJECT_DIR/README_STATUS.md"
move_to_quarantine "$PROJECT_DIR/PLAN_CORRECTIONS_DB.md"
move_to_quarantine "$PROJECT_DIR/CRQ-20250828-01-laravel-update.yaml"

# ===================================================================
# 9. FICHIERS DE STATUS TEMPORAIRES
# ===================================================================
echo ""
echo "9️⃣ Nettoyage des fichiers de status temporaires..."
echo "-----------------------------------------------------"

move_to_quarantine "$PROJECT_DIR/.backup_status"
move_to_quarantine "$PROJECT_DIR/.studiosdb_status"
move_to_quarantine "$PROJECT_DIR/.sync_status"
move_to_quarantine "$PROJECT_DIR/STATUS.sh"
move_to_quarantine "$PROJECT_DIR/status.sh"

# ===================================================================
# 10. FICHIERS DIVERS
# ===================================================================
echo ""
echo "🔟 Nettoyage des fichiers divers..."
echo "-----------------------------------------------------"

move_to_quarantine "$PROJECT_DIR/GO.sh"
move_to_quarantine "$PROJECT_DIR/HELP.sh"
move_to_quarantine "$PROJECT_DIR/exit"
move_to_quarantine "$PROJECT_DIR/audit_backend.txt"
move_to_quarantine "$PROJECT_DIR/db_analyze_backup.sh"
move_to_quarantine "$PROJECT_DIR/membre_system_access_component.vue"

# ===================================================================
# 11. RÉPERTOIRES TEMPORAIRES
# ===================================================================
echo ""
echo "1️⃣1️⃣ Nettoyage des répertoires temporaires..."
echo "-----------------------------------------------------"

# Déplacer les répertoires temporaires
[ -d "$PROJECT_DIR/temp_cleanup" ] && mv "$PROJECT_DIR/temp_cleanup" "$QUARANTINE_DIR/"
[ -d "$PROJECT_DIR/mkdir" ] && mv "$PROJECT_DIR/mkdir" "$QUARANTINE_DIR/"

# ===================================================================
# 12. FICHIERS PLAYWRIGHT TEMPORAIRES
# ===================================================================
echo ""
echo "1️⃣2️⃣ Nettoyage des fichiers Playwright temporaires..."
echo "-----------------------------------------------------"

move_to_quarantine "$PROJECT_DIR/check-playwright.js"
move_to_quarantine "$PROJECT_DIR/complete-frontend-test.js"
move_to_quarantine "$PROJECT_DIR/test-playwright-visual.sh"
move_to_quarantine "$PROJECT_DIR/playwright-headless.config.js"
move_to_quarantine "$PROJECT_DIR/PLAYWRIGHT_VISUAL_GUIDE.md"
move_to_quarantine "$PROJECT_DIR/README-frontend-tests.md"

# Répertoires Playwright temporaires
[ -d "$PROJECT_DIR/playwright-report" ] && mv "$PROJECT_DIR/playwright-report" "$QUARANTINE_DIR/"
[ -d "$PROJECT_DIR/test-results" ] && mv "$PROJECT_DIR/test-results" "$QUARANTINE_DIR/"

# ===================================================================
# 13. FICHIERS DE BACKUP PACKAGE.JSON
# ===================================================================
echo ""
echo "1️⃣3️⃣ Nettoyage des backups package.json..."
echo "-----------------------------------------------------"

move_to_quarantine "$PROJECT_DIR/package.json.backup.1757268918329"

# ===================================================================
# RÉSUMÉ
# ===================================================================
echo ""
echo "====================================================="
echo "✅ Nettoyage terminé!"
echo "====================================================="
echo ""
echo "📊 Résumé:"
echo "----------"

# Compter les fichiers déplacés
if [ -d "$QUARANTINE_DIR" ]; then
    FILE_COUNT=$(find "$QUARANTINE_DIR" -type f | wc -l)
    DIR_COUNT=$(find "$QUARANTINE_DIR" -type d -mindepth 1 | wc -l)
    echo "  • Fichiers déplacés: $FILE_COUNT"
    echo "  • Répertoires déplacés: $DIR_COUNT"
    echo "  • Emplacement quarantaine: $QUARANTINE_DIR"
else
    echo "  • Aucun fichier à nettoyer"
fi

echo ""
echo "💡 Conseils:"
echo "  1. Vérifiez le contenu de la quarantaine avant suppression définitive"
echo "  2. Pour supprimer définitivement: rm -rf $QUARANTINE_DIR"
echo "  3. Les scripts essentiels sont conservés dans /scripts"
echo ""

# ===================================================================
# CRÉATION D'UN SCRIPT DE DÉMARRAGE UNIQUE
# ===================================================================
echo "🚀 Création d'un script de démarrage unifié..."
echo "-----------------------------------------------------"

cat > "$PROJECT_DIR/start.sh" << 'EOF'
#!/bin/bash

# Script de démarrage unifié pour StudiosDB
echo "🚀 Démarrage de StudiosDB..."

# Vérifier l'environnement
if [ ! -f .env ]; then
    echo "❌ Fichier .env manquant!"
    exit 1
fi

# Nettoyer le cache
php artisan optimize:clear

# Démarrer les services
echo "📦 Installation des dépendances..."
composer install --no-interaction
npm install

echo "🔨 Construction des assets..."
npm run build

echo "🗄️ Migrations de base de données..."
php artisan migrate --force

echo "🌐 Démarrage du serveur..."
php artisan serve --host=0.0.0.0 --port=8000 &
npm run dev &

echo "✅ StudiosDB démarré sur http://localhost:8000"
echo "   Appuyez sur Ctrl+C pour arrêter"
wait
EOF

chmod +x "$PROJECT_DIR/start.sh"

echo "✅ Script de démarrage unifié créé: start.sh"
echo ""
echo "🎉 Nettoyage complet terminé!"
