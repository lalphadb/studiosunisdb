#!/bin/bash

echo "🧪 TEST CONNEXIONS STUDIOSDB"
echo "============================"

cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro

echo "1️⃣ Test connexion MySQL..."
php artisan tinker --execute="
try {
    DB::connection()->getPdo();
    echo 'MySQL: ✅ Connexion réussie' . PHP_EOL;
    echo 'Database: ' . DB::connection()->getDatabaseName() . PHP_EOL;
} catch(Exception \$e) {
    echo 'MySQL: ❌ ' . \$e->getMessage() . PHP_EOL;
}
"

echo ""
echo "2️⃣ Test cache..."
php artisan cache:clear
echo "Cache: ✅ Nettoyé"

echo ""
echo "3️⃣ Test NPM..."
npm list @heroicons/vue 2>/dev/null && echo "Héroicons: ✅ Installé" || echo "Héroicons: ❌ Manquant"

echo ""
echo "4️⃣ Test compilation..."
npm run build > /tmp/build.log 2>&1
if [ $? -eq 0 ]; then
    echo "Build: ✅ Réussi"
    ls -la public/build/ | head -3
else
    echo "Build: ❌ Échec"
    tail -5 /tmp/build.log
fi

echo ""
echo "5️⃣ Test serveur..."
curl -s -o /dev/null -w "%{http_code}" http://localhost:8001/login | grep -q 200 && echo "Serveur: ✅ Répond" || echo "Serveur: ❌ Ne répond pas"

echo ""
echo "🎯 RÉSUMÉ TESTS"
echo "==============="
php artisan about | grep -E "(Environment|Database|Debug)"
