#!/bin/bash

# StudiosDB Auto-Fix Script
# Purpose: Diagnostique et corrige automatiquement les problèmes courants

echo "=== StudiosDB Auto-Fix Script ==="
echo "Diagnostic et correction automatique en cours..."
echo ""

# Variables
FIXED_COUNT=0
ISSUES_COUNT=0

# Function to report issues
report_issue() {
    echo "❌ Problème détecté: $1"
    ((ISSUES_COUNT++))
}

# Function to report fixes
report_fix() {
    echo "✅ Corrigé: $1"
    ((FIXED_COUNT++))
}

# 1. Check and fix hot file
if [ -f "public/hot" ]; then
    report_issue "Fichier HMR hot détecté en production"
    rm -f public/hot
    report_fix "Fichier hot supprimé"
fi

# 2. Check and build assets
if [ ! -f "public/build/.vite/manifest.json" ]; then
    report_issue "Assets non compilés"
    echo "   Compilation des assets..."
    npm run build > /dev/null 2>&1
    if [ -f "public/build/.vite/manifest.json" ]; then
        report_fix "Assets compilés avec succès"
    else
        echo "   ⚠️  Échec de la compilation - vérifiez manuellement avec: npm run build"
    fi
fi

# 3. Check and fix permissions
if [ ! -w "storage" ] || [ ! -w "bootstrap/cache" ]; then
    report_issue "Permissions incorrectes sur storage/cache"
    chmod -R 775 storage bootstrap/cache 2>/dev/null
    report_fix "Permissions corrigées"
fi

# 4. Clear all caches
echo ""
echo "Nettoyage des caches..."
php artisan config:clear > /dev/null 2>&1
php artisan view:clear > /dev/null 2>&1
php artisan route:clear > /dev/null 2>&1
php artisan cache:clear > /dev/null 2>&1
php artisan optimize:clear > /dev/null 2>&1
report_fix "Tous les caches nettoyés"

# 5. Check database connection
echo ""
echo "Test de la connexion base de données..."
DB_TEST=$(php artisan tinker --execute="echo DB::connection()->getPdo() ? 'OK' : 'FAILED';" 2>&1 | grep -E "OK|FAILED" | head -1)
if [ "$DB_TEST" = "OK" ]; then
    echo "✅ Connexion base de données OK"
else
    report_issue "Connexion base de données échouée"
    echo "   Vérifiez votre fichier .env"
fi

# 6. Generate app key if missing
APP_KEY=$(grep "^APP_KEY=" .env | cut -d '=' -f2)
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "" ]; then
    report_issue "APP_KEY manquante"
    php artisan key:generate
    report_fix "APP_KEY générée"
fi

# 7. Check required routes
ROUTE_COUNT=$(php artisan route:list 2>/dev/null | grep -c "dashboard\|login" || echo "0")
if [ "$ROUTE_COUNT" -eq "0" ]; then
    report_issue "Routes manquantes"
    echo "   Vérifiez routes/web.php"
else
    echo "✅ Routes configurées"
fi

# 8. Make scripts executable
chmod +x start-server.sh start-dev.sh test-app.sh 2>/dev/null

# Summary
echo ""
echo "=== Résumé ==="
echo "Problèmes détectés: $ISSUES_COUNT"
echo "Corrections appliquées: $FIXED_COUNT"
echo ""

if [ "$ISSUES_COUNT" -eq "0" ]; then
    echo "🎉 Aucun problème détecté! L'application devrait fonctionner correctement."
elif [ "$FIXED_COUNT" -eq "$ISSUES_COUNT" ]; then
    echo "🎉 Tous les problèmes ont été corrigés!"
else
    echo "⚠️  Certains problèmes nécessitent une intervention manuelle."
fi

echo ""
echo "=== Prochaines étapes ==="
echo "1. Pour lancer en production: bash start-server.sh"
echo "2. Pour lancer en développement: bash start-dev.sh"
echo "3. Pour tester l'application: bash test-app.sh"
echo ""
echo "L'application sera accessible à: http://127.0.0.1:8001/dashboard"
