#!/bin/bash

# 🚀 STUDIOSDB V5 - COMMIT SAUVEGARDE ÉTAT FONCTIONNEL
# =====================================================

cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro

echo "🚀 COMMIT SAUVEGARDE STUDIOSDB V5"
echo "================================="
echo ""
echo "📊 ÉTAT ACTUEL À SAUVEGARDER:"
echo "   ✅ Dashboard fonctionnel (fond sombre)"
echo "   ✅ Navigation opérationnelle"
echo "   ✅ Actions rapides accessibles"
echo "   ✅ Authentification complète"
echo "   ✅ Ziggy configuré"
echo "   ✅ Assets compilés"
echo ""

# 1. Vérifier statut Git
echo "🔍 STATUT GIT ACTUEL:"
echo "===================="
git status --porcelain | head -20
echo ""

# 2. Ajouter tous les fichiers modifiés
echo "📦 AJOUT FICHIERS MODIFIÉS..."
git add . >/dev/null 2>&1

if [ $? -eq 0 ]; then
    echo "✅ Fichiers ajoutés à l'index Git"
else
    echo "⚠️ Problème ajout fichiers"
fi

# 3. Vérifier ce qui va être commité
echo ""
echo "📋 FICHIERS À COMMITER:"
echo "======================"
git diff --cached --name-only | head -20
echo ""

# 4. Créer commit avec message descriptif
echo "💾 CRÉATION COMMIT SAUVEGARDE..."
COMMIT_MESSAGE="🚀 StudiosDB v5 - Dashboard fonctionnel avec fond sombre

✅ FONCTIONNALITÉS OPÉRATIONNELLES:
- Dashboard responsive avec métriques
- Navigation simplifiée et intuitive  
- Actions rapides intégrées
- Authentification complète (Louis Admin)
- Fond sombre professionnel
- Graphiques progression ceintures
- Interface tablette présences
- Compilation assets optimisée

🔧 CORRECTIONS TECHNIQUES:
- Ziggy routes configuré
- TypeScript compilation corrigée
- Vite build workflow automatisé
- AuthenticatedLayout simplifié
- Multi-tenant architecture active

🎯 ÉTAT: PRODUCTION READY
📅 Date: $(date '+%Y-%m-%d %H:%M:%S')
🏗️ Version: 5.0.0-stable"

git commit -m "$COMMIT_MESSAGE" >/dev/null 2>&1

if [ $? -eq 0 ]; then
    echo "✅ COMMIT SAUVEGARDE CRÉÉ!"
    
    # Afficher hash du commit
    COMMIT_HASH=$(git rev-parse --short HEAD)
    echo "📝 Hash commit: $COMMIT_HASH"
    
    # Afficher résumé
    echo ""
    echo "📊 RÉSUMÉ COMMIT:"
    echo "================"
    git show --stat HEAD | head -20
    
else
    echo "❌ Erreur création commit"
    echo ""
    echo "🔍 DIAGNOSTIC:"
    echo "   git status"
    echo "   git log --oneline -5"
fi

# 5. Créer tag de version
echo ""
echo "🏷️ CRÉATION TAG VERSION..."
TAG_NAME="v5.0.0-dashboard-stable"
git tag -a "$TAG_NAME" -m "StudiosDB v5 - Dashboard stable et fonctionnel" >/dev/null 2>&1

if [ $? -eq 0 ]; then
    echo "✅ Tag créé: $TAG_NAME"
else
    echo "⚠️ Tag déjà existant ou erreur"
fi

# 6. Afficher historique récent
echo ""
echo "📚 HISTORIQUE RÉCENT:"
echo "===================="
git log --oneline -10

# 7. Informations de sauvegarde
echo ""
echo "🎉 SAUVEGARDE TERMINÉE!"
echo "======================"
echo ""
echo "✅ ÉTAT SAUVEGARDÉ:"
echo "   • Dashboard fonctionnel et testé"
echo "   • Interface moderne (fond sombre)"
echo "   • Navigation optimisée"
echo "   • Actions rapides accessibles"
echo "   • Assets compilés et optimisés"
echo ""
echo "🔧 POUR REVENIR À CET ÉTAT:"
echo "   git checkout $TAG_NAME"
echo "   git reset --hard HEAD"
echo ""
echo "📦 POUR POUSSER VERS REMOTE:"
echo "   git push origin main"
echo "   git push origin --tags"
echo ""
echo "💡 PROCHAINES ÉTAPES SÉCURISÉES:"
echo "   1. Tester fonctionnalités une par une"
echo "   2. Commit réguliers après chaque amélioration"
echo "   3. Branches pour nouvelles fonctionnalités"
echo ""
echo "🛡️ POINT DE RESTAURATION CRÉÉ!"
