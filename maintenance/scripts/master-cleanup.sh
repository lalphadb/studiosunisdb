#!/bin/bash

echo "🎯 NETTOYAGE MAÎTRE STUDIOSDB V5 PRO"
echo "===================================="
echo ""
echo "Ce script va nettoyer votre projet en déplaçant tous les fichiers"
echo "non essentiels vers un dossier d'archive."
echo ""
echo "⚠️  IMPORTANT:"
echo "• Les fichiers seront DÉPLACÉS (pas supprimés)"
echo "• Vous pourrez les récupérer depuis l'archive"
echo "• Une validation sera effectuée après nettoyage"
echo ""

# Demander confirmation
read -p "🤔 Voulez-vous continuer avec le nettoyage ? (y/N): " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo "❌ Nettoyage annulé par l'utilisateur"
    exit 0
fi

cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro

# Étape 1: Sauvegarde préventive
echo ""
echo "📦 ÉTAPE 1: SAUVEGARDE PRÉVENTIVE"
echo "================================="

if [ -d ".git" ]; then
    echo "Git détecté - Création d'un commit de sauvegarde..."
    git add . > /dev/null 2>&1
    git commit -m "💾 Sauvegarde avant nettoyage automatique

Commit de sécurité avant le nettoyage du projet.
Tous les fichiers temporaires vont être archivés.

Date: $(date)
Action: Nettoyage projet StudiosDB v5 Pro" > /dev/null 2>&1
    
    if [ $? -eq 0 ]; then
        echo "✅ Commit de sauvegarde créé"
    else
        echo "ℹ️  Pas de modifications à sauvegarder"
    fi
else
    echo "ℹ️  Git non initialisé - Pas de sauvegarde Git"
fi

# Étape 2: Analyse pré-nettoyage
echo ""
echo "🔍 ÉTAPE 2: ANALYSE PRÉ-NETTOYAGE"
echo "================================="

TOTAL_FILES_BEFORE=$(find . -type f -not -path "./.git/*" | wc -l)
TOTAL_SIZE_BEFORE=$(du -sh . | cut -f1)

echo "📊 État actuel:"
echo "• Fichiers totaux: $TOTAL_FILES_BEFORE"
echo "• Taille totale: $TOTAL_SIZE_BEFORE"
echo "• Scripts détectés: $(find . -maxdepth 1 -name "*.sh" | wc -l)"

# Étape 3: Nettoyage principal
echo ""
echo "🧹 ÉTAPE 3: NETTOYAGE PRINCIPAL"
echo "==============================="

if [ -f "cleanup-project.sh" ]; then
    chmod +x cleanup-project.sh
    ./cleanup-project.sh
    CLEANUP_SUCCESS=$?
else
    echo "❌ Script cleanup-project.sh introuvable !"
    exit 1
fi

if [ $CLEANUP_SUCCESS -ne 0 ]; then
    echo "❌ Erreur durant le nettoyage !"
    exit 1
fi

# Étape 4: Validation post-nettoyage
echo ""
echo "✅ ÉTAPE 4: VALIDATION POST-NETTOYAGE"
echo "====================================="

if [ -f "validate-cleanup.sh" ]; then
    chmod +x validate-cleanup.sh
    ./validate-cleanup.sh
    VALIDATION_SUCCESS=$?
else
    echo "❌ Script validate-cleanup.sh introuvable !"
    VALIDATION_SUCCESS=1
fi

# Étape 5: Statistiques finales
echo ""
echo "📊 ÉTAPE 5: STATISTIQUES FINALES"
echo "================================"

TOTAL_FILES_AFTER=$(find . -type f -not -path "./.git/*" -not -path "./archive_cleanup*" | wc -l)
TOTAL_SIZE_AFTER=$(du -sh . --exclude="archive_cleanup*" | cut -f1)
FILES_ARCHIVED=$(find . -maxdepth 1 -name "archive_cleanup_*" -type d -exec find {} -type f \; | wc -l)

echo "📈 Comparaison avant/après:"
echo "• Fichiers avant: $TOTAL_FILES_BEFORE"
echo "• Fichiers après: $TOTAL_FILES_AFTER"
echo "• Fichiers archivés: $FILES_ARCHIVED"
echo "• Réduction: $((TOTAL_FILES_BEFORE - TOTAL_FILES_AFTER)) fichiers"
echo ""
echo "💾 Taille:"
echo "• Taille avant: $TOTAL_SIZE_BEFORE" 
echo "• Taille après: $TOTAL_SIZE_AFTER"

# Étape 6: Recommandations finales
echo ""
echo "🎯 ÉTAPE 6: RECOMMANDATIONS FINALES"
echo "==================================="

if [ $VALIDATION_SUCCESS -eq 0 ]; then
    echo "🎉 NETTOYAGE RÉUSSI AVEC SUCCÈS !"
    echo ""
    echo "✅ Actions réalisées:"
    echo "• Fichiers temporaires archivés"
    echo "• Scripts de debug déplacés"
    echo "• Documentation consolidée"
    echo "• Structure Laravel préservée"
    echo "• Validation système réussie"
    echo ""
    echo "📋 Prochaines étapes recommandées:"
    echo "1. Testez le dashboard: http://localhost:8000/dashboard"
    echo "2. Vérifiez les fonctionnalités critiques"
    echo "3. Exécutez 'npm run build' si nécessaire"
    echo "4. Si tout fonctionne, supprimez les archives:"
    find . -maxdepth 1 -name "archive_cleanup_*" -type d | while read archive; do
        echo "   rm -rf '$archive'"
    done
else
    echo "⚠️  NETTOYAGE TERMINÉ AVEC AVERTISSEMENTS"
    echo ""
    echo "🔧 Actions requises:"
    echo "• Vérifiez les erreurs de validation ci-dessus"
    echo "• Testez manuellement les fonctionnalités"
    echo "• Récupérez des fichiers depuis l'archive si nécessaire"
fi

echo ""
echo "📦 Archives créées:"
find . -maxdepth 1 -name "archive_cleanup_*" -type d | while read archive; do
    archive_size=$(du -sh "$archive" | cut -f1)
    echo "• $archive ($archive_size)"
done

echo ""
echo "🔗 Scripts utiles conservés:"
echo "• sauvegarde-complete.sh - Sauvegarde Git complète"
echo "• commit-sauvegarde.sh - Commit professionnel"
echo "• apply-dashboard.sh - Mise à jour dashboard"
echo "• quick-fix.sh - Corrections rapides"
echo "• validate-cleanup.sh - Validation système"
echo ""
echo "📚 Documentation:"
echo "• README.md - Guide complet du projet"
echo "• CHANGELOG.md - Historique des versions"
echo "• SCRIPTS_UTILES.md - Guide des scripts conservés"
echo ""
echo "🚀 StudiosDB v5 Pro - Projet nettoyé et optimisé !"