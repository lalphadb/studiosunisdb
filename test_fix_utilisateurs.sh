#!/bin/bash

#====================================
# FIX ERREURS MODULE UTILISATEURS
# StudiosDB v7 - Corrections finales
#====================================

echo "🔧 FIX ERREURS MODULE UTILISATEURS"
echo "==================================="

cd /home/studiosdb/studiosunisdb

echo "1️⃣ Vérification imports route() corrigés..."
echo "Index.vue:"
grep -n "import.*route.*ziggy" resources/js/Pages/Utilisateurs/Index.vue || echo "❌ Import manquant"

echo "Show.vue:"
grep -n "import.*route.*ziggy" resources/js/Pages/Utilisateurs/Show.vue || echo "❌ Import manquant"

echo "Create.vue:"
grep -n "import.*route.*ziggy" resources/js/Pages/Utilisateurs/Create.vue || echo "❌ Import manquant"

echo "Edit.vue:"
grep -n "import.*route.*ziggy" resources/js/Pages/Utilisateurs/Edit.vue || echo "❌ Import manquant"

echo ""
echo "2️⃣ Compilation frontend..."
npm run build 2>&1 | head -20

echo ""
echo "3️⃣ Test accès page utilisateurs..."
echo "URL de test: http://127.0.0.1:8001/utilisateurs"

echo ""
echo "✅ Corrections appliquées:"
echo "   - Import route() ajouté dans tous les fichiers Vue"
echo "   - Menu Utilisateurs ajouté au layout"
echo "   - Permissions admin-panel configurées"
echo ""
echo "🎯 Prochaine étape après compilation:"
echo "   - Naviguer vers /utilisateurs"
echo "   - Vérifier interface complète"
echo "   - Tester CRUD + actions hover"
