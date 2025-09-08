#!/bin/bash

#====================================
# TEST MODULE UTILISATEURS J4
# StudiosDB v7 - Validation complète
#====================================

echo "🎯 TEST MODULE UTILISATEURS (J4) - Validation complète"
echo "====================================================="

cd /home/studiosdb/studiosunisdb

echo "1️⃣ Vérification structure routes..."
php artisan route:list --name=utilisateurs | head -10

echo ""
echo "2️⃣ Vérification fichiers Vue..."
ls -la resources/js/Pages/Utilisateurs/

echo ""
echo "3️⃣ Vérification hover actions (doit contenir opacity-0)..."
grep -n "opacity-0 group-hover:opacity-100" resources/js/Pages/Utilisateurs/Index.vue

echo ""
echo "4️⃣ Vérification UserPolicy (sécurité)..."
grep -n "ecole_id" app/Policies/UserPolicy.php

echo ""
echo "5️⃣ Vérification Model User (fillable)..."
grep -A10 "fillable" app/Models/User.php

echo ""
echo "6️⃣ Vérification UserController (ecole_id scoping)..."
grep -n "ecole_id" app/Http/Controllers/UserController.php

echo ""
echo "7️⃣ Test compilation frontend..."
npm run build

echo ""
echo "✅ MODULE UTILISATEURS - TESTS TERMINÉS"
echo "📋 Actions à valider manuellement:"
echo "   - Navigation vers /utilisateurs"
echo "   - Actions hover cachées/visibles"
echo "   - CRUD complet fonctionnel"
echo "   - Scoping par ecole_id"
echo "   - Reset password + gestion rôles"
