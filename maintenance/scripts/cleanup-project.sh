#!/bin/bash

echo "🧹 NETTOYAGE COMPLET STUDIOSDB V5 PRO"
echo "====================================="

cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro

# Créer le dossier d'archive
ARCHIVE_DIR="archive_cleanup_$(date +%Y%m%d_%H%M%S)"
mkdir -p "$ARCHIVE_DIR"

echo "📦 Dossier d'archive créé: $ARCHIVE_DIR"
echo ""

# Compteurs
MOVED_FILES=0
MOVED_DIRS=0

# Fonction pour déplacer un fichier/dossier
move_item() {
    local item="$1"
    local reason="$2"
    
    if [ -e "$item" ]; then
        mv "$item" "$ARCHIVE_DIR/"
        echo "📦 Déplacé: $item ($reason)"
        if [ -d "$ARCHIVE_DIR/$(basename "$item")" ]; then
            ((MOVED_DIRS++))
        else
            ((MOVED_FILES++))
        fi
    fi
}

echo "🔍 ANALYSE ET NETTOYAGE EN COURS..."
echo "=================================="

# 1. SCRIPTS DE DEBUG/DIAGNOSTIC (nombreux et non nécessaires)
echo ""
echo "1. Scripts de debug et diagnostic..."
move_item "diagnose_dashboard_vs_membres.sh" "Script de diagnostic temporaire"
move_item "diagnostic-dashboard.sh" "Script de diagnostic temporaire"
move_item "diagnostic-post-fix.sh" "Script de diagnostic temporaire"
move_item "diagnostic.sh" "Script de diagnostic temporaire"
move_item "diagnostic_complet.sh" "Script de diagnostic temporaire"
move_item "diagnostic_final_page_blanche.sh" "Script de diagnostic temporaire"
move_item "diagnostic_page_blanche_complet.sh" "Script de diagnostic temporaire"
move_item "diagnostic_rapide.sh" "Script de diagnostic temporaire"
move_item "diagnostic_serveur_complet.sh" "Script de diagnostic temporaire"
move_item "diagnostic_studiosdb.sh" "Script de diagnostic temporaire"
move_item "diagnostic_urgence.sh" "Script de diagnostic temporaire"
move_item "debug-assets.sh" "Script de debug temporaire"
move_item "debug_final.sh" "Script de debug temporaire"
move_item "debug_page_blanche_temps_reel.sh" "Script de debug temporaire"

# 2. SCRIPTS DE FIX TEMPORAIRES
echo ""
echo "2. Scripts de correction temporaires..."
move_item "fix-login-now.sh" "Script de fix temporaire"
move_item "fix-login-urgent.sh" "Script de fix temporaire"
move_item "fix-monolog.sh" "Script de fix temporaire"
move_item "fix-npm.sh" "Script de fix temporaire"
move_item "fix-permissions-urgent.sh" "Script de fix temporaire"
move_item "fix-permissions.sh" "Script de fix temporaire"
move_item "fix-perms-now.sh" "Script de fix temporaire"
move_item "fix-simple.sh" "Script de fix temporaire"
move_item "fix-sql-error.sh" "Script de fix temporaire"
move_item "fix_dashboard_complet.sh" "Script de fix temporaire"
move_item "fix_dashboard_final.sh" "Script de fix temporaire"
move_item "fix_dashboard_final_execution.sh" "Script de fix temporaire"
move_item "fix_dashboard_typescript.sh" "Script de fix temporaire"
move_item "fix_dashboard_ultra_pro.sh" "Script de fix temporaire"
move_item "fix_design_moderne.sh" "Script de fix temporaire"
move_item "fix_page_blanche_definitif.sh" "Script de fix temporaire"
move_item "fix_page_blanche_definitif_final.sh" "Script de fix temporaire"
move_item "fix_page_blanche_definitif_v2.sh" "Script de fix temporaire"
move_item "fix_quick.sh" "Script de fix temporaire"
move_item "fix_urgence_permissions_assets.sh" "Script de fix temporaire"
move_item "correction_urgente_finale.sh" "Script de correction temporaire"
move_item "reparation-urgence.sh" "Script de réparation temporaire"
move_item "repair.sh" "Script de réparation temporaire"

# 3. FICHIERS DE BACKUP
echo ""
echo "3. Fichiers de sauvegarde et backup..."
move_item "backup_20250801_105440" "Dossier de backup ancien"
move_item "backup_20250801_105527" "Dossier de backup ancien"
move_item "backups" "Dossier de backups"
move_item "cleanup_backup" "Dossier de backup cleanup"
move_item "backup_conflict_dashboard_before_fix.php" "Backup conflit dashboard"
move_item "backup_conflict_dashboard_good.php" "Backup conflit dashboard"
move_item "backup_conflict_dashboard_simple.php" "Backup conflit dashboard"
move_item "dashboard_backup_old.php.bak" "Backup dashboard ancien"
move_item "dashboard_vue_backup.vue.bak" "Backup vue ancien"
move_item "README.md.backup.20250801_093424" "Backup README ancien"
move_item "hot_backup" "Backup temporaire"

# 4. LOGS ET FICHIERS DE DÉPLOIEMENT
echo ""
echo "4. Logs et fichiers de déploiement..."
move_item "audit_report.txt" "Rapport d'audit temporaire"
move_item "audit_report_20250801.txt" "Rapport d'audit temporaire"
move_item "build.log" "Log de build"
move_item "correction_20250801_105440.log" "Log de correction"
move_item "deployment.log" "Log de déploiement"
move_item "laravel.log" "Log Laravel temporaire"
move_item "vite.log" "Log Vite"

# 5. SCRIPTS D'AUDIT ET TEST
echo ""
echo "5. Scripts d'audit et test..."
move_item "audit_studiosdb.sh" "Script d'audit temporaire"
move_item "audit_studiosdb_v2.sh" "Script d'audit temporaire"
move_item "finaliser_audit.sh" "Script d'audit temporaire"
move_item "test-final.sh" "Script de test temporaire"
move_item "test_avant_correction.sh" "Script de test temporaire"
move_item "test_corrections_membres.sh" "Script de test temporaire"
move_item "test_server_status.sh" "Script de test temporaire"
move_item "test_transformation.sh" "Script de test temporaire"
move_item "validation_finale.sh" "Script de validation temporaire"
move_item "verification_complete.sh" "Script de vérification temporaire"

# 6. DOCUMENTATION TEMPORAIRE
echo ""
echo "6. Documentation temporaire..."
move_item "ANALYSE_VUES_VUE.md" "Documentation temporaire"
move_item "CORRECTION_FINALE.md" "Documentation temporaire"
move_item "TECHNICAL_REFERENCE.md" "Documentation temporaire"
move_item "TRANSFORMATION_ULTRA_PRO.md" "Documentation temporaire"
move_item "dashboard_ultra_pro_final.txt" "Documentation temporaire"
move_item "README" "Dossier README ancien"
move_item "docs" "Dossier docs temporaire"
move_item "explications des modules" "Dossier explications temporaire"

# 7. SCRIPTS DE TRANSFORMATION ET INSTALLATION
echo ""
echo "7. Scripts de transformation..."
move_item "apply_membres_ultra_pro.sh" "Script de transformation temporaire"
move_item "install_modules_complets.sh" "Script d'installation temporaire"
move_item "transformation_summary.sh" "Script de transformation temporaire"
move_item "recompile-full.sh" "Script de recompilation temporaire"
move_item "recompile_assets_ultra_pro.sh" "Script de recompilation temporaire"
move_item "compile-assets.sh" "Script de compilation temporaire"

# 8. SCRIPTS DE DÉPLOIEMENT TEMPORAIRES
echo ""
echo "8. Scripts de déploiement temporaires..."
move_item "master-deployment-script.sh" "Script de déploiement temporaire"
move_item "post-deployment-verification.sh" "Script de déploiement temporaire"
move_item "execution-finale-commands.sh" "Script d'exécution temporaire"
move_item "github-commit-script.sh" "Script GitHub temporaire"
move_item "commit-github.sh" "Script commit temporaire"

# 9. SCRIPTS DE MAINTENANCE TEMPORAIRES
echo ""
echo "9. Scripts de maintenance temporaires..."
move_item "backup_project.sh" "Script de backup temporaire"
move_item "restore_project.sh" "Script de restore temporaire"
move_item "clean_after_fix.sh" "Script de nettoyage temporaire"
move_item "cleanup_components.sh" "Script de nettoyage temporaire"
move_item "cleanup_extensions.sh" "Script de nettoyage temporaire"
move_item "cleanup_project.sh" "Script de nettoyage temporaire"
move_item "change_dashboard.sh" "Script de changement temporaire"
move_item "switch-dashboard.sh" "Script de switch temporaire"
move_item "switch_dashboard.sh" "Script de switch temporaire"

# 10. SCRIPTS DE SERVEUR ET PERMISSIONS
echo ""
echo "10. Scripts de serveur temporaires..."
move_item "restart-now.sh" "Script de redémarrage temporaire"
move_item "start_server_now.sh" "Script de serveur temporaire"
move_item "set_permissions.sh" "Script de permissions temporaire"
move_item "reprise_rapide.sh" "Script de reprise temporaire"

# 11. FICHIERS DE CONFIGURATION DUPLIQUÉS
echo ""
echo "11. Fichiers de configuration dupliqués..."
move_item "postcss.config.cjs" "Config PostCSS dupliquée"
move_item "tailwind.config.cjs" "Config Tailwind dupliquée"

# 12. FICHIERS TEMPORAIRES ET DIVERS
echo ""
echo "12. Fichiers temporaires et divers..."
move_item ".laravel_pid" "Fichier PID Laravel temporaire"
move_item ".phpunit.result.cache" "Cache PHPUnit"
move_item "stream" "Fichier stream temporaire"
move_item "vite" "Fichier vite temporaire"
move_item "studiosdb-v5-pro@5.0.0" "Fichier de version temporaire"
move_item "temp_test" "Dossier de test temporaire"
move_item "extract_dashboard_errors.sh" "Script d'extraction temporaire"
move_item "optimize-dashboard.sh" "Script d'optimisation temporaire"
move_item "update-readme-script.sh" "Script de mise à jour temporaire"
move_item "sauvegarde_complete_studiosdb_v5.sh" "Script de sauvegarde ancien"
move_item "dashboard_controller_pro.sh" "Script de contrôleur temporaire"

# 13. Vérifier les dossiers vides
echo ""
echo "13. Vérification des dossiers vides..."
find . -maxdepth 1 -type d -empty | while read empty_dir; do
    if [ "$empty_dir" != "." ] && [ "$empty_dir" != "./$ARCHIVE_DIR" ]; then
        move_item "${empty_dir#./}" "Dossier vide"
    fi
done

# Résumé final
echo ""
echo "✅ NETTOYAGE TERMINÉ !"
echo "====================="
echo "📦 Fichiers déplacés: $MOVED_FILES"
echo "📁 Dossiers déplacés: $MOVED_DIRS"
echo "🗂️  Archive créée: $ARCHIVE_DIR"
echo ""

# Afficher ce qui reste (fichiers essentiels)
echo "📋 FICHIERS ET DOSSIERS CONSERVÉS:"
echo "=================================="
ls -la | grep -E "^d|^-" | grep -v "^total" | while read line; do
    filename=$(echo "$line" | awk '{print $NF}')
    if [ "$filename" != "." ] && [ "$filename" != ".." ] && [ "$filename" != "$ARCHIVE_DIR" ]; then
        echo "✅ $filename"
    fi
done

echo ""
echo "🎯 PROJET NETTOYÉ ET OPTIMISÉ !"
echo "=============================="
echo "Le projet ne contient plus que les fichiers essentiels :"
echo "• Structure Laravel standard"
echo "• Scripts utiles de maintenance"
echo "• Documentation professionnelle" 
echo "• Configuration de production"
echo ""
echo "📦 Tous les fichiers temporaires sont dans: $ARCHIVE_DIR"
echo "🗑️  Vous pouvez supprimer ce dossier si tout fonctionne bien"