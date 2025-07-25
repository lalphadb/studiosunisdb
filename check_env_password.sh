#!/bin/bash

echo "🔍 DIAGNOSTIC CONFIGURATION LARAVEL"
echo "=================================="

cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro

echo "1️⃣ Contenu .env actuel:"
echo "======================"
grep -E "DB_|APP_KEY" .env

echo ""
echo "2️⃣ Configuration Laravel cachée:"
echo "==============================="
php artisan config:show database.connections.mysql 2>/dev/null || echo "Impossible d'afficher config DB"

echo ""
echo "3️⃣ Test mot de passe spécifique:"
echo "=============================="
DB_PASSWORD=$(grep DB_PASSWORD .env | cut -d'=' -f2)
echo "Mot de passe .env: '$DB_PASSWORD'"

# Test avec ce mot de passe
mysql -u studiosdb -p"$DB_PASSWORD" -e "SELECT 'Password .env fonctionne!' as test;" 2>/dev/null || echo "❌ Password .env ne fonctionne pas"

echo ""
echo "4️⃣ Comparaison mots de passe:"
echo "==========================="
echo "Working password: StudioSDB_2025!Secure"  
echo ".env password: $DB_PASSWORD"
if [ "$DB_PASSWORD" = "StudioSDB_2025!Secure" ]; then
    echo "✅ Passwords identiques"
else
    echo "❌ PASSWORDS DIFFÉRENTS - PROBLÈME TROUVÉ!"
fi
