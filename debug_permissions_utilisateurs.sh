#!/bin/bash

#====================================
# DEBUG PERMISSIONS UTILISATEURS
# StudiosDB v7 - Test accès admin
#====================================

echo "🔍 DEBUG PERMISSIONS UTILISATEURS"
echo "=================================="

cd /home/studiosdb/studiosunisdb

echo "1️⃣ Test gate admin-panel..."
php artisan tinker --execute="
\$user = \App\Models\User::first();
echo 'User: ' . \$user->name . PHP_EOL;
echo 'Roles: ' . \$user->getRoleNames()->implode(', ') . PHP_EOL;
echo 'Can admin-panel: ' . (\$user->can('admin-panel') ? 'YES' : 'NO') . PHP_EOL;
echo 'Has superadmin: ' . (\$user->hasRole('superadmin') ? 'YES' : 'NO') . PHP_EOL;
echo 'Has admin_ecole: ' . (\$user->hasRole('admin_ecole') ? 'YES' : 'NO') . PHP_EOL;
"

echo ""
echo "2️⃣ Vérification routes admin..."
php artisan route:list --name=utilisateurs | head -5

echo ""
echo "3️⃣ Test direct route..."
curl -s "http://127.0.0.1:8001/utilisateurs" -I | head -3

echo ""
echo "4️⃣ Navigation dans Layout (recherche 'utilisateurs')..."
grep -n "utilisateurs" resources/js/Layouts/AuthenticatedLayout.vue | head -3

echo ""
echo "✅ Si 'Can admin-panel: YES' → problème compilation frontend"
echo "✅ Si 'Can admin-panel: NO' → problème permissions/rôles"
echo ""
echo "🔧 Solutions:"
echo "   - npm run build (recompiler frontend)"
echo "   - Vider cache navigateur"
echo "   - Vérifier rôles utilisateur en DB"
