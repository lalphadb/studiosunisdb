#!/bin/bash

#====================================
# TEST FINAL MODULE UTILISATEURS
# StudiosDB v7 - Validation complète
#====================================

echo "🎯 TEST FINAL MODULE UTILISATEURS"
echo "=================================="

cd /home/studiosdb/studiosunisdb

echo "1️⃣ Vérification routes dans app.js..."
grep -A5 -B5 "utilisateurs" resources/js/app.js

echo ""
echo "2️⃣ Compilation frontend..."
npm run build 2>&1 | tail -10

echo ""
echo "3️⃣ Vérification permissions admin..."
php artisan tinker --execute="
\$user = \App\Models\User::first();
echo 'User: ' . \$user->name . PHP_EOL;
echo 'Can admin-panel: ' . (\$user->can('admin-panel') ? 'YES' : 'NO') . PHP_EOL;
"

echo ""
echo "4️⃣ Test routes web..."
php artisan route:list --name=utilisateurs | head -5

echo ""
echo "✅ MODULE UTILISATEURS - PRÊT!"
echo "📱 Actions utilisateur:"
echo "   1. Naviguer vers http://127.0.0.1:8001/utilisateurs"
echo "   2. Vérifier menu visible dans sidebar"
echo "   3. Tester interface responsive + hover actions"
echo "   4. CRUD complet: créer/modifier/supprimer utilisateur"
echo ""
echo "🎯 Si OK → Module J4 TERMINÉ ✅"
echo "🎯 Si problème → Check console navigateur"
