#!/bin/bash

echo "🧪 TEST CONNEXION MYSQL STUDIOSDB"
echo "================================="

cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro

# Test connexion directe MySQL
echo "Test 1: Connexion directe MySQL"
mysql -u studiosdb -p'StudioSDB_2025!Secure' -e "SELECT 'Connexion MySQL réussie!' as status; SHOW DATABASES LIKE 'studiosdb%';"

echo ""
echo "Test 2: Via Laravel Artisan"
php artisan tinker --execute="
try {
    \$pdo = DB::connection()->getPdo();
    echo '✅ Laravel -> MySQL: CONNEXION RÉUSSIE!' . PHP_EOL;
    echo 'Database: ' . DB::connection()->getDatabaseName() . PHP_EOL;
    echo 'Driver: ' . \$pdo->getAttribute(PDO::ATTR_DRIVER_NAME) . PHP_EOL;
} catch(Exception \$e) {
    echo '❌ Erreur Laravel: ' . \$e->getMessage() . PHP_EOL;
}
"

echo ""
echo "Test 3: Clear cache et retest"
php artisan config:clear
php artisan config:cache

# Test final via navigateur
echo ""
echo "Test 4: Page /debug"
curl -s http://localhost:8001/debug | grep -E "(Database|studiosdb)" || echo "Page debug non accessible"

echo ""
echo "🎯 Si tous les tests passent = BASE DE DONNÉES OPÉRATIONNELLE!"
