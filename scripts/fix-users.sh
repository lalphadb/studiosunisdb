#!/bin/bash

echo "👥 RÉPARATION UTILISATEURS"
echo "=========================="

# Réparation via artisan route plus sûre
php artisan tinker --execute="
\$superadmin = \App\Models\User::where('email', 'lalpha@4lb.ca')->first();
if (\$superadmin) {
    \$superadmin->syncRoles(['superadmin']);
    echo 'SuperAdmin réparé';
} else {
    echo 'SuperAdmin non trouvé';
}
"

php artisan permission:cache-reset

echo "✅ Utilisateurs vérifiés"
