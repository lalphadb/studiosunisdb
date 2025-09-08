#!/bin/bash

echo "🎯 COMMIT SAUVEGARDE - StudiosDB v7"
echo "==================================="
echo ""

cd /home/studiosdb/studiosunisdb

# Vérifier le statut git
echo "📋 Statut des fichiers:"
git status --short
echo ""

# Ajouter tous les fichiers modifiés
echo "➕ Ajout des fichiers modifiés..."
git add -A

# Créer le commit avec message détaillé
echo "💾 Création du commit de sauvegarde..."
git commit -m "feat(responsive): corrections finales UI/UX cohérente v7

✅ Corrections responsive appliquées:
- Dashboard: stats responsive (text-xl sm:text-2xl xl:text-3xl) + truncate
- StatCard: min-w-0 + flex-1 pour éviter débordement chiffres
- Cours: actions hover-only (opacity-0 group-hover:opacity-100)
- Membres: boutons actions visibles + taille uniforme (p-1.5, w-4 h-4)

✅ Thème cohérent:
- Bleu glassmorphism maintenu dans tous les modules
- Comportement hover uniforme (transition-opacity 200ms)
- Colonne Actions standardisée (w-24)

✅ État stable:
- J1 Bootstrap sécurité: FROZEN ✅
- J2 Dashboard: FROZEN ✅  
- J3 Cours: FROZEN ✅
- Prêt pour J4 Utilisateurs

Impact: UI/UX finale cohérente, pas de débordement à 100% zoom
Tests: ✅ Dashboard, ✅ Cours hover, ✅ Responsive breakpoints"

# Afficher le résultat
echo ""
echo "✅ Commit créé:"
git log -1 --oneline
echo ""

echo "🎯 PROCHAINES ÉTAPES:"
echo "- J4 Utilisateurs: CRUD complet + gestion rôles"
echo "- J5 Membres: finalisation intégration"
echo "- J6 Inscription: workflow self-service"
echo ""

echo "Pour pusher vers le dépôt distant (optionnel):"
echo "git push origin \$(git branch --show-current)"
