#!/bin/bash

echo "🚀 STANDARDISATION SÉCURISÉE STUDIOSDB - EXÉCUTION COMPLÈTE"
echo "=========================================================="

# Vérifier que tous les scripts existent
SCRIPTS=(
    "00_prepare_safe_standardization.sh"
    "01_safe_cleanup.sh"
    "02_migrate_admin_progressive.sh"
    "03_update_controllers_safe.sh"
    "04_migrate_navigation_safe.sh"
    "05_validate_complete.sh"
)

echo "🔍 Vérification scripts..."
for script in "${SCRIPTS[@]}"; do
    if [ ! -f "$script" ]; then
        echo "❌ Script manquant: $script"
        exit 1
    fi
    echo "  ✅ $script"
done

echo ""
echo "⚠️ ATTENTION: Cette standardisation va modifier votre projet"
echo "📦 Des backups seront créés à chaque étape"
echo ""
read -p "Continuer la standardisation? (y/N): " -n 1 -r
echo

if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo "❌ Standardisation annulée"
    exit 1
fi

echo ""
echo "🚀 DÉBUT STANDARDISATION..."

# Phase 0: Préparation
echo ""
echo "========== PHASE 0: PRÉPARATION =========="
./00_prepare_safe_standardization.sh
if [ $? -ne 0 ]; then
    echo "❌ Erreur phase 0 - Arrêt"
    exit 1
fi

read -p "Phase 0 OK. Continuer? (y/N): " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then exit 1; fi

# Phase 1: Nettoyage
echo ""
echo "========== PHASE 1: NETTOYAGE SÉCURISÉ =========="
./01_safe_cleanup.sh
if [ $? -ne 0 ]; then
    echo "❌ Erreur phase 1 - Arrêt"
    exit 1
fi

read -p "Phase 1 OK. Continuer? (y/N): " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then exit 1; fi

# Phase 2: Migration
echo ""
echo "========== PHASE 2: MIGRATION ADMIN → PAGES =========="
./02_migrate_admin_progressive.sh
if [ $? -ne 0 ]; then
    echo "❌ Erreur phase 2 - Arrêt"
    exit 1
fi

read -p "Phase 2 OK. Continuer? (y/N): " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then exit 1; fi

# Phase 3: Contrôleurs
echo ""
echo "========== PHASE 3: MISE À JOUR CONTRÔLEURS =========="
./03_update_controllers_safe.sh
if [ $? -ne 0 ]; then
    echo "❌ Erreur phase 3 - Arrêt"
    exit 1
fi

read -p "Phase 3 OK. Continuer? (y/N): " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then exit 1; fi

# Phase 4: Navigation
echo ""
echo "========== PHASE 4: MIGRATION NAVIGATION =========="
./04_migrate_navigation_safe.sh
if [ $? -ne 0 ]; then
    echo "❌ Erreur phase 4 - Arrêt"
    exit 1
fi

read -p "Phase 4 OK. Continuer validation? (y/N): " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then exit 1; fi

# Phase 5: Validation
echo ""
echo "========== PHASE 5: VALIDATION COMPLÈTE =========="
./05_validate_complete.sh

echo ""
echo "🎉 STANDARDISATION TERMINÉE!"
echo "=========================="
echo ""
echo "📋 ÉTAPES SUIVANTES:"
echo "1. Testez votre application admin"
echo "2. Si tout fonctionne:"
echo "   - Remplacez admin.blade.php par admin.blade.php.test"
echo "   - Supprimez partials/admin-navigation.blade.php"
echo "   - Supprimez l'ancien dossier admin/"
echo "3. Compilez les assets: npm run build"
echo "4. Committez les changements"
echo ""
echo "📦 Backups disponibles dans les dossiers backup_*"
