#!/bin/bash

echo "🧹 NETTOYAGE DU PROJET StudiosDB v5 Pro"
echo "======================================="

# Créer un dossier de sauvegarde avant suppression
mkdir -p cleanup_backup/scripts
mkdir -p cleanup_backup/docs
mkdir -p cleanup_backup/logs

echo "📦 Sauvegarde des fichiers importants..."

# Sauvegarder quelques scripts utiles
cp launch-dev.sh cleanup_backup/scripts/ 2>/dev/null
cp setup_database.sh cleanup_backup/scripts/ 2>/dev/null
cp backup_project.sh cleanup_backup/scripts/ 2>/dev/null

# Sauvegarder la documentation importante
cp README.md cleanup_backup/docs/ 2>/dev/null
cp TECHNICAL_REFERENCE.md cleanup_backup/docs/ 2>/dev/null

echo "🗑️  Suppression des fichiers inutiles..."

# 1. SUPPRESSION DES SCRIPTS DE DEBUG/FIXES TEMPORAIRES
echo "Suppression des scripts de debug..."
rm -f *fix*.sh
rm -f *diagnostic*.sh
rm -f *correction*.sh
rm -f *deploy*.sh
rm -f *execute*.sh
rm -f *compile*.sh
rm -f *verification*.sh
rm -f *create_*.sh
rm -f *install_*.sh
rm -f *update_*.sh
rm -f *test_*.sh
rm -f *reset*.sh
rm -f *solution*.sh
rm -f *reparation*.sh
rm -f *passage*.sh
rm -f *studiosdb_*.sh
rm -f *final*.sh
rm -f *analyse*.sh
rm -f *check*.sh
rm -f *vite*.sh
rm -f *recompile*.sh
rm -f *cleanup*.sh
rm -f ALL_IN_ONE.sh
rm -f EXECUTE_*.sh
rm -f RUN_NOW.sh
rm -f quick_fix.sh
rm -f reprise_rapide.sh
rm -f finaliser_audit.sh
rm -f commit_*.sh
rm -f deploy_to_main.sh
rm -f git_commit_complete.sh
rm -f verif_post_correction.sh
rm -f restore_project.sh
rm -f validation_finale.sh

# 2. SUPPRESSION DES FICHIERS DE DOCUMENTATION TEMPORAIRE
echo "Suppression de la documentation temporaire..."
rm -f ANALYSE_*.md
rm -f AUDIT_*.md
rm -f CONVERSATION_*.md
rm -f CORRECTION_*.md
rm -f ETAT_*.md
rm -f EXTENSIONS_*.md
rm -f RAPPORT_*.md
rm -f RESUME_*.md
rm -f PROJECT_STATUS.md
rm -f MAIN_DEPLOY_*.md
rm -f VITE_INSTRUCTIONS.md
rm -f GUIDE_*.md
rm -f PROMPT_*.md

# 3. SUPPRESSION DES LOGS TEMPORAIRES
echo "Suppression des logs temporaires..."
rm -f *.log
rm -f nohup.out

# 4. SUPPRESSION DES FICHIERS DE BACKUP ENV
echo "Suppression des backups .env..."
rm -f .env.backup*

# 5. SUPPRESSION DES DOSSIERS DE BACKUP TEMPORAIRES
echo "Suppression des dossiers de backup temporaires..."
rm -rf backup_dashboards_*

# 6. SUPPRESSION DES FICHIERS BIZARRES
echo "Suppression des fichiers étranges..."
rm -f "0," "0]," "[revenus_mois" "[total" "[total_semaine" "[utilisateurs_connectes"
rm -f cookies.txt

# 7. NETTOYAGE DES FICHIERS DE CACHE
echo "Nettoyage des caches..."
rm -f .phpunit.result.cache
rm -rf storage/debugbar/* 2>/dev/null
rm -rf storage/logs/* 2>/dev/null

# 8. NETTOYAGE NODE_MODULES si présent (peut être regénéré)
echo "Vérification de node_modules..."
if [ -d "node_modules" ]; then
    echo "⚠️  node_modules détecté ($(du -sh node_modules | cut -f1))"
    echo "Vous pouvez le supprimer avec: rm -rf node_modules && npm install"
fi

echo ""
echo "✅ NETTOYAGE TERMINÉ !"
echo "====================="
echo ""
echo "📊 RÉSUMÉ :"
echo "- Scripts temporaires supprimés"
echo "- Documentation temporaire supprimée"
echo "- Logs nettoyés"
echo "- Backups .env supprimés"
echo "- Dossiers de backup temporaires supprimés"
echo ""
echo "💾 Fichiers sauvegardés dans cleanup_backup/"
echo ""
echo "🚀 Votre projet est maintenant propre !"
echo ""

# Afficher la taille du projet après nettoyage
echo "📏 Taille du projet après nettoyage :"
du -sh . 2>/dev/null

echo ""
echo "⭐ Fichiers principaux conservés :"
echo "- app/ (code Laravel)"
echo "- resources/ (vues, CSS, JS)"
echo "- routes/ (routes)"
echo "- config/ (configuration)"
echo "- database/ (migrations, seeders)"
echo "- public/ (assets publics)"
echo "- vendor/ (dépendances PHP)"
echo "- package.json, composer.json"
echo "- .env (configuration)"
echo "- artisan (commandes Laravel)"
echo ""
