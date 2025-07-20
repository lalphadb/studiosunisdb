#!/bin/bash

# 🚀 STUDIOSDB V5 - MODE PRODUCTION (LA BONNE MÉTHODE!)
# =====================================================

cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro

echo "🚀 PASSAGE EN MODE PRODUCTION"
echo "=============================="
echo ""
echo "❌ PROBLÈME IDENTIFIÉ:"
echo "   - Mode dev = Vite dev server permanent (mauvais pour production)"
echo "   - Mode prod = Assets compilés une fois (correct pour production)"
echo ""

# 1. Arrêter le dev server inutile
echo "🛑 ARRÊT VITE DEV SERVER..."
pkill -f "vite" 2>/dev/null || true
pkill -f "npm run dev" 2>/dev/null || true
pkill -f "php artisan serve" 2>/dev/null || true
sleep 2

# 2. Nettoyer
echo "🧹 NETTOYAGE..."
rm -f public/hot 2>/dev/null || true
rm -rf public/build/* 2>/dev/null || true
php artisan route:clear >/dev/null 2>&1
php artisan config:clear >/dev/null 2>&1

# 3. Vérifier environnement de production
echo ""
echo "⚙️ CONFIGURATION PRODUCTION..."

# Vérifier .env
if grep -q "APP_ENV=local" .env 2>/dev/null; then
    echo "🔧 Passage en mode production..."
    sed -i 's/APP_ENV=local/APP_ENV=production/' .env
    sed -i 's/APP_DEBUG=true/APP_DEBUG=false/' .env
    echo "✅ .env configuré pour production"
else
    echo "✅ Environnement production OK"
fi

# 4. COMPILATION ASSETS PRODUCTION
echo ""
echo "🔨 COMPILATION ASSETS PRODUCTION..."
echo "==================================="

# Installer dépendances production
echo "📦 Installation dépendances..."
npm ci --only=production >/dev/null 2>&1

# Compiler pour production
echo "🔨 Compilation TypeScript + Vue + CSS..."
npm run build

if [ $? -eq 0 ]; then
    echo "✅ Compilation réussie!"
else
    echo "❌ Erreur compilation - vérification logs..."
    npm run build
    exit 1
fi

# 5. Vérifier fichiers compilés
echo ""
echo "🔍 VÉRIFICATION ASSETS COMPILÉS..."
if [ -d "public/build" ] && [ "$(ls -A public/build 2>/dev/null)" ]; then
    echo "✅ Assets compilés trouvés dans public/build/"
    echo "📋 Fichiers:"
    ls -la public/build/ | head -10
else
    echo "❌ Aucun asset compilé trouvé!"
    exit 1
fi

# 6. Optimisation Laravel production
echo ""
echo "⚡ OPTIMISATION LARAVEL PRODUCTION..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 7. Changer template pour production
echo ""
echo "🔧 CONFIGURATION TEMPLATE PRODUCTION..."

# Modifier app.blade.php pour mode production
cat > resources/views/app.blade.php << 'EOF'
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title inertia>{{ config('app.name', 'StudiosDB') }}</title>
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    
    <!-- Routes Ziggy -->
    @routes
    
    <!-- Assets compilés Vite (PRODUCTION) -->
    @vite(['resources/css/app.css', 'resources/js/app.ts'])
    
    <!-- Inertia Head -->
    @inertiaHead
</head>
<body class="font-sans antialiased">
    @inertia
</body>
</html>
EOF

echo "✅ Template configuré pour production"

# 8. DÉMARRAGE SERVEUR PRODUCTION
echo ""
echo "🚀 DÉMARRAGE SERVEUR PRODUCTION..."
echo "=================================="

# Démarrer Laravel en mode production
nohup php artisan serve --host=0.0.0.0 --port=8000 --env=production > laravel-prod.log 2>&1 &
LARAVEL_PID=$!

echo "✅ Laravel démarré en mode PRODUCTION (PID: $LARAVEL_PID)"
echo "📋 Logs: tail -f laravel-prod.log"
sleep 3

# 9. TESTS PRODUCTION
echo ""
echo "🧪 TESTS MODE PRODUCTION..."
echo "==========================="

# Test API
echo -n "🔍 Test API... "
if curl -s http://0.0.0.0:8000/test | grep -q "SUCCESS"; then
    echo "✅ OK"
else
    echo "❌ ERREUR"
fi

# Test Debug
echo -n "🔍 Test Debug... "
if curl -s http://0.0.0.0:8000/debug | grep -q "StudiosDB"; then
    echo "✅ OK"
else
    echo "❌ ERREUR"
fi

# Test Login (avec assets compilés)
echo -n "🔍 Test Login... "
LOGIN_RESPONSE=$(curl -s http://0.0.0.0:8000/login)
if echo "$LOGIN_RESPONSE" | grep -q "<!DOCTYPE html>"; then
    echo "✅ OK"
else
    echo "❌ ERREUR"
fi

# Vérifier qu'il n'y a plus de référence au dev server
echo -n "🔍 Vérification mode production... "
if ! curl -s http://0.0.0.0:8000/login | grep -q "127.0.0.1:5175"; then
    echo "✅ OK (pas de dev server)"
else
    echo "⚠️ Référence dev server détectée"
fi

# 10. RÉSULTATS FINAUX
echo ""
echo "🎉 MODE PRODUCTION ACTIVÉ!"
echo "=========================="
echo ""
echo "✅ AVANTAGES PRODUCTION:"
echo "   • Assets compilés/minifiés"
echo "   • Pas de processus Vite permanent"
echo "   • Optimisé pour performance"
echo "   • Cache Laravel activé"
echo "   • Prêt pour serveur de production"
echo ""
echo "📊 STATUT:"
echo "   ✅ Mode: PRODUCTION"
echo "   ✅ Assets: COMPILÉS"
echo "   ✅ Cache: ACTIVÉ"
echo "   ❌ Dev server: ARRÊTÉ (normal!)"
echo ""
echo "🎯 URLS:"
echo "   🔍 Debug: http://0.0.0.0:8000/debug"
echo "   🔐 Login: http://0.0.0.0:8000/login"
echo "   🏠 Dashboard: http://0.0.0.0:8000/dashboard"
echo ""
echo "📋 MONITORING:"
echo "   - Laravel: tail -f laravel-prod.log"
echo ""
echo "🚀 STUDIOSDB V5 EN MODE PRODUCTION!"

# 11. Instructions finales
echo ""
echo "💡 POUR MODIFICATIONS FUTURES:"
echo "==============================="
echo "• Modifier code: npm run build (recompiler)"
echo "• Debug: npm run dev (temporaire)"
echo "• Production: npm run build (toujours)"
echo ""
echo "🎯 TON APPROCHE ÉTAIT CORRECTE!"
