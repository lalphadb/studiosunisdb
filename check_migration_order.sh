#!/bin/bash

echo "🔍 VÉRIFICATION ORDRE MIGRATIONS STUDIOSUNISDB"
echo "=============================================="

echo "📋 Ordre actuel :"
ls database/migrations/*.php | nl

echo ""
echo "🧪 Test rapide migration ordre :"
mysql -u root -pLkmP0km1 -e "DROP DATABASE IF EXISTS temp_order_test; CREATE DATABASE temp_order_test;"

# Configuration temporaire pour test
cp .env .env.backup.check
sed 's/DB_DATABASE=studiosdb/DB_DATABASE=temp_order_test/' .env > .env.temp.check
cp .env.temp.check .env

php artisan migrate --force

if [ $? -eq 0 ]; then
    echo "✅ ORDRE CORRECT"
else 
    echo "❌ PROBLÈMES D'ORDRE DÉTECTÉS"
fi

# Cleanup
cp .env.backup.check .env
rm .env.temp.check .env.backup.check
mysql -u root -pLkmP0km1 -e "DROP DATABASE temp_order_test;"
