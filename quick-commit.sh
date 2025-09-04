#!/bin/bash

echo "📦 Commit de sauvegarde StudiosDB"
echo "================================="
echo ""

# Afficher le statut
echo "📋 Fichiers modifiés:"
git status --short
echo ""

# Ajouter tous les fichiers
echo "➕ Ajout des fichiers..."
git add -A

# Faire le commit
echo "💾 Création du commit..."
git commit -m "feat(dashboard): refonte complète UI/UX professionnelle 2025

- Dashboard professionnel avec design glassmorphism
- Suppression totale des scrollbars (design moderne)
- Statistiques animées et graphiques SVG
- Corrections double sidebar et erreurs PHP
- Composants StatCard, ModernStatsCard, ModernActionCard
- Build optimisé avec Terser
- Configuration PostCSS et Tailwind améliorée

[LEDGER] J2 Dashboard (réf. UI) → DONE"

# Afficher le résultat
echo ""
echo "✅ Commit effectué:"
git log -1 --oneline
echo ""

# Proposer de pusher
echo "Pour pusher vers le dépôt distant:"
echo "git push origin $(git branch --show-current)"
