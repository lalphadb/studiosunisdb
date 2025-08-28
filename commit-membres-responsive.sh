#!/bin/bash

# Commit sauvegarde - Optimisation responsive Membres
echo "=== COMMIT SAUVEGARDE MEMBRES ==="

# Status avant commit
echo "Status du repository :"
git status --short

echo ""
echo "Différences Membres/Index.vue :"
git diff --stat resources/js/Pages/Membres/Index.vue

# Commit avec message descriptif
git add resources/js/Pages/Membres/Index.vue
git commit -m "fix(membres): optimisation responsive tableau

- Réduction paddings colonnes (px-6→px-3, px-4→px-2) 
- Largeurs fixes colonnes (Contact w-40, Ceinture w-28, etc.)
- Éléments UI compacts (avatars 8x8, boutons p-1.5, icônes 4x4)
- Texte tronqué avec max-w-* + tooltips
- Effet hover boutons préservé (opacity-0→opacity-100)
- Raccourcis visuels ('/ m', '(M)')

Impact: Tableau affiché correctement à zoom 100%
Tests: À valider sur /membres"

# Tag de sauvegarde
git tag -a "membres-responsive-v1" -m "Sauvegarde optimisation responsive tableau membres"

echo ""
echo "✅ Commit effectué - Tag: membres-responsive-v1"
echo "📌 Rollback possible via: git checkout membres-responsive-v1"

# Verification finale
echo ""
echo "Derniers commits :"
git log --oneline -3
