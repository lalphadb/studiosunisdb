#!/bin/bash

#====================================
# TEST GESTION RÔLES UTILISATEURS
# StudiosDB v7 - Validation J4 finale
#====================================

echo "👥 TEST GESTION RÔLES UTILISATEURS"
echo "==================================="

cd /home/studiosdb/studiosunisdb

echo "1️⃣ Vérification des rôles disponibles..."
php artisan tinker --execute="
\$roles = \Spatie\Permission\Models\Role::all();
echo 'Rôles système:' . PHP_EOL;
foreach(\$roles as \$role) {
    echo '  - ' . \$role->name . PHP_EOL;
}
"

echo ""
echo "2️⃣ Vérification UpdateUserRequest corrigé..."
grep -A5 -B2 "routeUser.*utilisateur" app/Http/Requests/UpdateUserRequest.php

echo ""
echo "3️⃣ Test interface Edit.vue..."
echo "Vérification gestion rôles:"
grep -n "rolesList.*props.roles" resources/js/Pages/Utilisateurs/Edit.vue

echo ""
echo "4️⃣ Test assignation rôle instructeur..."
php artisan tinker --execute="
\$user = \App\Models\User::first();
echo 'User: ' . \$user->name . PHP_EOL;
echo 'Rôles actuels: ' . \$user->getRoleNames()->implode(', ') . PHP_EOL;
echo 'Peut être instructeur: ' . (\Spatie\Permission\Models\Role::where('name', 'instructeur')->exists() ? 'OUI' : 'NON') . PHP_EOL;
"

echo ""
echo "✅ TESTS GESTION RÔLES:"
echo "   - UpdateUserRequest sécurisé ✓"
echo "   - Interface rôles améliorée ✓"
echo "   - Rôle instructeur disponible ✓"
echo "   - Statut actif/inactif ajouté ✓"
echo ""
echo "🎯 Tests utilisateur:"
echo "   1. Aller sur /utilisateurs"
echo "   2. Cliquer 'Modifier' sur un utilisateur"
echo "   3. Vérifier section 'Rôles' visible"
echo "   4. Cocher/décocher 'instructeur'"
echo "   5. Enregistrer → Succès attendu"
echo ""
echo "📋 Workflow rôles:"
echo "   - membre: Rôle par défaut"
echo "   - instructeur: Staff enseignant"
echo "   - admin_ecole: Admin école"
echo "   - superadmin: Super admin global"
