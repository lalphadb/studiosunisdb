#!/bin/bash

echo "🔍 DIAGNOSTIC RAPIDE PAGE BLANCHE - STUDIOSDB V5"
echo "=============================================="

cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro

# 1. STATUT PROCESSUS
echo "📊 1. STATUT PROCESSUS:"
LARAVEL_PID=$(pgrep -f "php artisan serve")
VITE_PID=$(pgrep -f "npm run dev")

if [ ! -z "$LARAVEL_PID" ]; then
    echo "   ✅ Laravel actif (PID: $LARAVEL_PID)"
else
    echo "   ❌ Laravel inactif"
fi

if [ ! -z "$VITE_PID" ]; then
    echo "   ✅ Vite HMR actif (PID: $VITE_PID)"
else
    echo "   ❌ Vite HMR inactif"
fi

# 2. VÉRIFICATION SYNTAXE PHP
echo ""
echo "🔍 2. SYNTAXE PHP:"
if php -l "app/Http/Controllers/DashboardController.php" > /dev/null 2>&1; then
    echo "   ✅ DashboardController syntaxe OK"
else
    echo "   ❌ DashboardController erreur syntaxe:"
    php -l "app/Http/Controllers/DashboardController.php" | sed 's/^/      /'
fi

# 3. TEST CONNEXIONS
echo ""
echo "🌐 3. TEST CONNEXIONS:"

# Test Laravel
if curl -f -s -o /dev/null -w "%{http_code}" "http://localhost:8000" | grep -q "200\|302"; then
    echo "   ✅ Laravel HTTP: OK"
else
    echo "   ❌ Laravel HTTP: ERREUR"
fi

# Test Dashboard
if curl -f -s -o /dev/null -w "%{http_code}" "http://localhost:8000/dashboard" | grep -q "200\|302"; then
    echo "   ✅ Dashboard HTTP: OK"
else
    echo "   ❌ Dashboard HTTP: ERREUR"
fi

# 4. ERREURS RÉCENTES
echo ""
echo "📝 4. ERREURS RÉCENTES (5 dernières):"
if [ -f "storage/logs/laravel.log" ]; then
    tail -50 "storage/logs/laravel.log" | grep -E "(ERROR|Division|Parse)" | tail -5 | sed 's/^/   /'
    
    ERROR_COUNT=$(tail -100 "storage/logs/laravel.log" | grep -c "ERROR\|Division\|Parse" || echo "0")
    echo "   📊 Total erreurs récentes: $ERROR_COUNT"
else
    echo "   ℹ️  Pas de logs Laravel"
fi

# 5. VÉRIFICATION ASSETS
echo ""
echo "📦 5. ASSETS:"
if [ -d "public/build" ]; then
    BUILD_FILES=$(find public/build -name "*.js" -o -name "*.css" | wc -l)
    echo "   ✅ Répertoire build existe ($BUILD_FILES fichiers)"
else
    echo "   ❌ Répertoire build manquant"
fi

# 6. BASE DE DONNÉES
echo ""
echo "🗄️  6. BASE DE DONNÉES:"
if php artisan migrate:status > /dev/null 2>&1; then
    echo "   ✅ Base de données connectée"
else
    echo "   ❌ Problème base de données"
fi

echo ""
echo "🏁 DIAGNOSTIC TERMINÉ"
echo "==================="

