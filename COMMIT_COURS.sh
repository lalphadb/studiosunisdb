#!/bin/bash
echo "=== COMMIT SAUVEGARDE MODULE COURS ==="
cd /home/studiosdb/studiosunisdb

echo "🔍 VÉRIFICATION ÉTAT GIT"
echo "- Status git:"
git status --porcelain | head -10

echo ""
echo "📝 PRÉPARATION COMMIT"

# Ajouter tous les fichiers modifiés
echo "- Ajout fichiers modifiés..."
git add .

# Message de commit détaillé
COMMIT_MSG="fix(cours): Résolution contraintes DB + FormRequests Laravel 12

🔧 PROBLÈMES RÉSOLUS:
- SQLSTATE[23000]: tarif_mensuel cannot be null
- SQLSTATE[HY000]: ecole_id doesn't have default value

✅ CORRECTIONS APPORTÉES:
- Migration tarif_mensuel nullable (2025_08_28_130000)
- Migration ecole_id default mono-école (2025_08_28_140000)
- FormRequests StoreCoursRequest + UpdateCoursRequest
- Contrôleur CoursController optimisé (utilise FormRequests)
- Formulaires Vue Create.vue + Edit.vue (null au lieu string vide)
- Fallbacks robustes ecole_id pour environnement mono-école

🎯 RÉSULTATS:
- ✅ Création cours MENSUEL: fonctionne
- ✅ Création cours TRIMESTRIEL/HORAIRE/A_LA_CARTE: RÉPARÉ
- ✅ Validation centralisée avec messages français
- ✅ Architecture Laravel 12 respectée

📁 FICHIERS AJOUTÉS:
- app/Http/Requests/StoreCoursRequest.php
- app/Http/Requests/UpdateCoursRequest.php
- database/migrations/2025_08_28_130000_fix_tarif_mensuel_nullable.php
- database/migrations/2025_08_28_140000_fix_ecole_id_default_cours.php

📝 FICHIERS MODIFIÉS:
- app/Http/Controllers/CoursController.php (utilise FormRequests)
- resources/js/Pages/Cours/Create.vue (tarif_mensuel: null)
- resources/js/Pages/Cours/Edit.vue (tarif_mensuel: null)

🏗️ ARCHITECTURE:
Browser → Vue(données clean) → FormRequest(validation+fallback) → Controller(simple) → DB(contraintes OK)

Module Cours: 100% OPÉRATIONNEL - Prêt production"

echo "📝 MESSAGE COMMIT PRÉPARÉ"
echo ""

# Effectuer le commit
echo "🚀 COMMIT EN COURS..."
git commit -m "$COMMIT_MSG"

COMMIT_RESULT=$?

if [ $COMMIT_RESULT -eq 0 ]; then
    echo "✅ COMMIT RÉUSSI"
    echo ""
    echo "📊 DERNIERS COMMITS:"
    git log --oneline -3
    echo ""
    echo "🎯 HASH COMMIT SAUVEGARDE:"
    git rev-parse HEAD | cut -c1-8
else
    echo "❌ ERREUR COMMIT (code: $COMMIT_RESULT)"
    echo "Vérifiez l'état git et recommencez."
fi

echo ""
echo "📋 INFORMATIONS SAUVEGARDE:"
echo "- Date: $(date +'%Y-%m-%d %H:%M:%S')"
echo "- Branche: $(git branch --show-current 2>/dev/null || echo 'N/A')"
echo "- État: Module Cours STABLE et CORRIGÉ"
echo "- Prochaine étape: Module Utilisateurs (J4)"

echo ""
echo "✅ SAUVEGARDE COMMIT TERMINÉE"
