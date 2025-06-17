#!/bin/bash

echo "🔍 DIAGNOSTIC STUDIOSUNISDB"
echo "==========================="

# Base de données
echo "📊 STATISTIQUES:"
mysql -u root -pLkmP0km1 -D studiosdb --silent -e "
SELECT 'Users' as Type, COUNT(*) as Count FROM users
UNION ALL
SELECT 'Ecoles', COUNT(*) FROM ecoles  
UNION ALL
SELECT 'Roles', COUNT(*) FROM roles
UNION ALL
SELECT 'Permissions', COUNT(*) FROM permissions;
"

# Test connexion SuperAdmin
SUPERADMIN_EXISTS=$(mysql -u root -pLkmP0km1 -D studiosdb --silent -e "SELECT COUNT(*) FROM users WHERE email = 'lalpha@4lb.ca';" 2>/dev/null)

if [ "$SUPERADMIN_EXISTS" -eq 1 ]; then
    echo "✅ SuperAdmin: OK"
else
    echo "❌ SuperAdmin: MANQUANT"
fi

echo "🎯 Pour réparer: ./scripts/fix-permissions.sh"
