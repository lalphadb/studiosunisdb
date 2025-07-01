#!/bin/bash
REPORT="users_roles_permissions_$(date +%Y%m%d_%H%M%S).txt"
php artisan tinker --execute="
use App\Models\User;
foreach (User::with('roles', 'permissions')->get() as \$user) {
    echo 'User: ' . \$user->email . PHP_EOL;
    echo '  Roles: ' . implode(', ', \$user->getRoleNames()->toArray()) . PHP_EOL;
    echo '  Permissions: ' . implode(', ', \$user->getPermissionNames()->toArray()) . PHP_EOL;
    echo '---' . PHP_EOL;
}
" | tee "\$REPORT"
echo "=> Rapport généré : \$REPORT"
