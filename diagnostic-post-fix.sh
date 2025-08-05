#!/bin/bash

echo "🔍 DIAGNOSTIC COMPLET POST-FIX LOGS"
echo "==================================="

cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro

# 1. Test des différents endpoints
echo "1. Tests HTTP endpoints..."

echo "Test localhost:8000:"
curl -s -w "HTTP %{http_code}\n" http://localhost:8000/dashboard | head -1

echo "Test studiosdb.local:"
curl -s -w "HTTP %{http_code}\n" http://studiosdb.local/dashboard | head -1

echo "Test 127.0.0.1:"
curl -s -w "HTTP %{http_code}\n" http://127.0.0.1/dashboard | head -1

# 2. Vérifier quel serveur est actif
echo ""
echo "2. Serveurs actifs..."
echo "Nginx status:"
sudo systemctl is-active nginx

echo "PHP-FPM status:"
sudo systemctl is-active php8.3-fpm

echo "Serveur dev Laravel (port 8000):"
ps aux | grep "php artisan serve" | grep -v grep || echo "Aucun serveur dev"

# 3. Test direct PHP
echo ""
echo "3. Test direct PHP..."
php artisan route:list | grep dashboard
echo "Routes dashboard trouvées: $(php artisan route:list | grep -c dashboard)"

# 4. Test avec serveur dev temporaire
echo ""
echo "4. Lancement serveur dev temporaire..."
timeout 10 php artisan serve --host=0.0.0.0 --port=8001 &
SERVER_PID=$!
sleep 3

echo "Test serveur dev port 8001:"
curl -s -w "HTTP %{http_code}\n" http://localhost:8001/dashboard | head -1

# Arrêter le serveur temporaire
kill $SERVER_PID 2>/dev/null

# 5. Vérifier les assets
echo ""
echo "5. Vérification assets..."
if [ -f "public/build/manifest.json" ]; then
    echo "✅ Manifest exists"
    echo "Assets dans manifest: $(grep -c '"' public/build/manifest.json)"
else
    echo "❌ Pas de manifest - Assets non compilés"
fi

# 6. Dernières erreurs dans les logs (maintenant qu'ils fonctionnent!)
echo ""
echo "6. Dernières erreurs Laravel..."
tail -10 storage/logs/laravel.log | grep -i error || echo "Pas d'erreurs récentes"

echo ""
echo "DIAGNOSTIC TERMINÉ"
echo "=================="