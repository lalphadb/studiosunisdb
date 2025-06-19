#!/bin/bash
echo "🧪 === TESTS COMPLETS StudiosUnisDB v3.9.1 ==="

cd /home/studiosdb/studiosunisdb/

# Test 1: Serveur démarre
echo "🌐 Test démarrage serveur..."
timeout 10s php artisan serve --host=127.0.0.1 --port=8001 &
SERVER_PID=$!
sleep 5

# Test 2: Page d'accueil
echo "🏠 Test page d'accueil..."
curl -s -o /dev/null -w "%{http_code}" http://127.0.0.1:8001/ | grep -q "200" && echo "✅ Page d'accueil OK" || echo "❌ Page d'accueil KO"

# Test 3: Page de login
echo "🔐 Test page login..."
curl -s -o /dev/null -w "%{http_code}" http://127.0.0.1:8001/login | grep -q "200" && echo "✅ Page login OK" || echo "❌ Page login KO"

# Test 4: Dashboard (redirect si non connecté)
echo "📊 Test dashboard..."
curl -s -o /dev/null -w "%{http_code}" http://127.0.0.1:8001/admin | grep -q "302\|200" && echo "✅ Dashboard accessible" || echo "❌ Dashboard inaccessible"

# Arrêter le serveur de test
kill $SERVER_PID 2>/dev/null

# Test 5: Base de données
echo "🗄️ Test base de données..."
php artisan migrate:status > /dev/null 2>&1 && echo "✅ Base de données OK" || echo "❌ Problème base de données"

# Test 6: Permissions
echo "🔒 Test permissions..."
php artisan tinker --execute="
\$user = App\Models\User::first();
if (\$user && \$user->hasRole('superadmin')) {
    echo '✅ Permissions OK';
} else {
    echo '❌ Problème permissions';
}
"

echo "🧪 Tests terminés !"
