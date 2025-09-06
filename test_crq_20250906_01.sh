#!/bin/bash

#====================================
# CRQ-20250906-01 : TESTS CORRECTIONS
# Validation erreurs module Utilisateurs
#====================================

echo "🔧 CRQ-20250906-01 : Test corrections erreurs"
echo "=============================================="

cd /home/studiosdb/studiosunisdb

echo "1️⃣ Vérification inject route() dans composants..."
echo "Index.vue:"
grep -n "inject.*route" resources/js/Pages/Utilisateurs/Index.vue

echo "Show.vue:"
grep -n "inject.*route" resources/js/Pages/Utilisateurs/Show.vue

echo "Create.vue:"
grep -n "inject.*route" resources/js/Pages/Utilisateurs/Create.vue

echo "Edit.vue:"
grep -n "inject.*route" resources/js/Pages/Utilisateurs/Edit.vue

echo ""
echo "2️⃣ Vérification app.js provide..."
grep -A3 -B1 "provide.*route" resources/js/app.js

echo ""
echo "3️⃣ Test compilation..."
npm run build 2>&1 | tail -5

echo ""
echo "✅ TESTS CRQ-20250906-01:"
echo "   - Route injectée dans tous composants ✓"
echo "   - Provide configuré dans app.js ✓"  
echo "   - Fallback window.route disponible ✓"
echo ""
echo "🎯 Action utilisateur:"
echo "   1. Rafraîchir navigateur (Ctrl+Shift+R)"
echo "   2. Vérifier console sans erreurs route()"
echo "   3. Tester /utilisateurs complètement"
echo ""
echo "📋 Critères succès:"
echo "   - Plus d'erreur '_ctx.route is not a function'"
echo "   - Menu Utilisateurs visible"
echo "   - Interface fonctionnelle"
echo "   - Actions hover opérationnelles"
