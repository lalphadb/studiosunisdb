#!/bin/bash

# StudiosDB - Script de Commit et Sauvegarde
# Amélioration: Corrections responsive + README.md complet

echo "=== STUDIOSDB COMMIT & SAVE ==="
echo "Corrections responsive + README.md pour GitHub"
echo

# Vérifier le statut git
echo "📋 Statut des fichiers modifiés :"
git status --porcelain

echo
echo "📦 Ajout des fichiers au staging..."

# Ajouter les fichiers de corrections responsive
git add resources/js/Pages/Dashboard.vue
git add resources/js/Components/UI/StatCard.vue  
git add resources/js/Pages/Cours/Index.vue

# Ajouter le nouveau README.md
git add README.md

# Ajouter ce script lui-même
git add commit-responsive-readme.sh

echo "✅ Fichiers ajoutés au staging"

echo
echo "💾 Création du commit..."

# Message de commit détaillé
COMMIT_MSG="feat: corrections responsive design + README.md GitHub complet

🎨 Corrections UI/UX Responsive:
- Dashboard: police responsive (text-xl sm:text-2xl xl:text-3xl) + truncate
- StatCard: flex-1 + min-w-0 pour éviter débordement chiffres  
- Cours: boutons actions hover-only alignés sur module Membres
- Actions: taille uniforme (p-1.5, w-4 h-4, colonne w-24)
- UX: comportement hover cohérent dans toute l'application

📚 Documentation GitHub:
- README.md complet avec instructions installation détaillées
- Sections: prérequis, configuration, déploiement, architecture
- Standards développement et contribution
- Support et maintenance

✨ Améliorations:
- Résout débordement stats à 100% zoom
- Interface cohérente hover-only dans toutes les tables  
- Documentation professionnelle pour nouveau développeurs
- Setup simplifié avec commandes étape par étape

Impact: UX uniforme + onboarding développeurs facilité"

# Committer avec le message détaillé
git commit -m "$COMMIT_MSG"

if [ $? -eq 0 ]; then
    echo "✅ Commit créé avec succès"
    echo
    echo "🔄 Push vers le repository..."
    
    # Push vers origin (si configuré)
    git push origin main 2>/dev/null || git push origin master 2>/dev/null || {
        echo "⚠️  Push automatique échoué. Exécutez manuellement :"
        echo "   git push origin main"  
        echo "   (ou git push origin master selon votre branche)"
    }
    
    echo
    echo "📊 Résumé du commit :"
    git log -1 --oneline
    
    echo
    echo "🎯 Prochaines étapes :"
    echo "   1. Vérifier le push sur GitHub"
    echo "   2. Continuer développement module Utilisateurs (J4)"
    echo "   3. Finaliser module Membres (J5)"
    
else
    echo "❌ Erreur lors du commit"
    echo "Vérifiez les conflits ou permissions"
fi

echo
echo "=== FIN SCRIPT COMMIT ==="
