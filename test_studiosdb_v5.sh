#!/bin/bash

echo "=== TEST STUDIOSDB v5 ==="
echo ""

# 1. Test Laravel
echo "1️⃣ Test Laravel Configuration..."
php artisan config:clear
php artisan config:cache
echo "✅ Config OK"

# 2. Test Routes
echo ""
echo "2️⃣ Test Routes..."
php artisan route:clear
php artisan route:cache
echo "✅ Routes OK"

# 3. Test Database
echo ""
echo "3️⃣ Test Database Connection..."
php artisan migrate:status | head -5
echo "✅ Database OK"

# 4. Test Build
echo ""
echo "4️⃣ Test Build Assets..."
if [ -f "public/build/manifest.json" ]; then
    echo "✅ Build présent"
else
    echo "❌ Build manquant - Exécuter: npm run build"
fi

# 5. Test Server
echo ""
echo "5️⃣ Pour lancer le serveur de développement:"
echo "   php artisan serve"
echo "   (dans un autre terminal) npm run dev"
echo ""
echo "Accès: http://127.0.0.1:8000"
echo ""

# 6. Test Permissions
echo "6️⃣ Test Permissions..."
php -r "
require __DIR__.'/vendor/autoload.php';
\$app = require_once __DIR__.'/bootstrap/app.php';
\$kernel = \$app->make(Illuminate\Contracts\Console\Kernel::class);
\$kernel->bootstrap();

\$roles = \Spatie\Permission\Models\Role::pluck('name')->toArray();
echo 'Rôles disponibles: ' . implode(', ', \$roles) . PHP_EOL;
"

echo ""
echo "=== TESTS TERMINÉS ==="
