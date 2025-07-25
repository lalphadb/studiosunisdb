#!/bin/bash

echo "✅ VÉRIFICATION POST-CORRECTION"
echo "==============================="

cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro

echo "1. Status Laravel:"
php artisan --version
php artisan about | head -5

echo ""
echo "2. Assets compilés:"
ls -la public/build/

echo ""
echo "3. Logs récents:"
tail -5 storage/logs/laravel.log 2>/dev/null || echo "Pas d'erreurs récentes"

echo ""
echo "4. Test HTTP:"
curl -s -o /dev/null -w "%{http_code}" http://localhost:8001/login

echo ""
echo "5. Processus actifs:"
ps aux | grep -E "(nginx|php)" | grep -v grep | wc -l

echo ""
if [ -f "public/build/manifest.json" ]; then
    echo "🎉 SUCCÈS: Assets compilés!"
else
    echo "❌ ÉCHEC: Assets manquants"
fi
