#!/bin/bash

echo "🔧 TEST FONCTIONNEMENT STUDIOSDB V5 PRO"
echo "======================================"

echo ""
echo "✅ 1. Vérification serveur Laravel..."
if curl -s "http://localhost:8000" > /dev/null; then
    echo "   ✅ Serveur accessible sur port 8000"
else
    echo "   ❌ Serveur inaccessible"
fi

echo ""
echo "✅ 2. Vérification assets Vite..."
if [ -f "public/build/.vite/manifest.json" ]; then
    echo "   ✅ Manifest Vite présent"
    echo "   📊 Taille: $(du -h public/build/.vite/manifest.json | cut -f1)"
else
    echo "   ❌ Manifest Vite manquant"
fi

echo ""
echo "✅ 3. Vérification assets CSS/JS..."
CSS_COUNT=$(find public/build/assets -name "*.css" | wc -l)
JS_COUNT=$(find public/build/assets -name "*.js" | wc -l)
echo "   📁 Fichiers CSS: $CSS_COUNT"
echo "   📁 Fichiers JS: $JS_COUNT"

echo ""
echo "✅ 4. Test routes principales..."
echo "   🌐 Root: $(curl -s -o /dev/null -w "%{http_code}" http://localhost:8000)"
echo "   🔑 Login: $(curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/login)"
echo "   📊 Dashboard: $(curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/dashboard)"

echo ""
echo "✅ 5. Vérification base de données..."
if php artisan migrate:status > /dev/null 2>&1; then
    echo "   ✅ Base de données connectée"
    MIGRATION_COUNT=$(php artisan migrate:status | grep -c "Ran")
    echo "   📊 Migrations appliquées: $MIGRATION_COUNT"
else
    echo "   ❌ Problème base de données"
fi

echo ""
echo "🎯 RÉSUMÉ:"
echo "========="
echo "Le projet StudiosDB v5 Pro devrait maintenant fonctionner."
echo "Testez dans votre navigateur: http://studiosdb.local:8000/login"
echo ""
echo "📧 Identifiants admin:"
echo "Email: admin@studiosdb.com"
echo "Mot de passe: password123"
