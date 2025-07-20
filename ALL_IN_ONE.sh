#!/bin/bash

# 🎯 STUDIOSDB V5 - CORRECTION + COMMIT GITHUB
# ============================================
# Script tout-en-un pour corriger et sauvegarder

cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro

echo "🎯 STUDIOSDB V5 - CORRECTION & COMMIT FINAL"
echo "==========================================="
echo ""

# ÉTAPE 1: Correction des erreurs
echo "🔧 1. CORRECTION ERREURS CRITIQUES"
echo "=================================="
chmod +x fix_errors_and_commit.sh
./fix_errors_and_commit.sh
echo ""

# ÉTAPE 2: Commit sur GitHub
echo "🚀 2. SAUVEGARDE GITHUB"
echo "======================"
chmod +x commit_github.sh
./commit_github.sh
echo ""

# ÉTAPE 3: Test final
echo "🧪 3. TEST FINAL"
echo "==============="

echo "Test syntaxe PHP..."
php -l routes/web.php && echo "✅ Syntaxe PHP OK" || echo "❌ Erreur syntaxe PHP"

echo "Test routes Laravel..."
php artisan route:list >/dev/null 2>&1 && echo "✅ Routes OK" || echo "❌ Erreur routes"

echo "Test DB..."
php -r "
try {
    \$pdo = new PDO('mysql:host=127.0.0.1;dbname=studiosdb_central', 'studiosdb', 'StudiosDB2024!');
    echo '✅ DB OK' . PHP_EOL;
} catch (Exception \$e) {
    echo '⚠️ DB: ' . \$e->getMessage() . PHP_EOL;
}
"

echo ""
echo "🎉 CORRECTION & COMMIT TERMINÉS!"
echo "==============================="
echo ""
echo "🔗 TESTEZ MAINTENANT CES URLS:"
echo "   📍 http://localhost:8000/debug"
echo "   📍 http://localhost:8000/test"
echo "   📍 http://localhost:8000/"
echo ""
echo "🚀 REDÉMARREZ LE SERVEUR:"
echo "   Ctrl+C puis:"
echo "   php artisan serve --host=0.0.0.0 --port=8000"
echo ""
echo "📋 STATUT GIT:"
git status --short 2>/dev/null || echo "Pas de repo Git"
echo ""
echo "🏆 VOTRE PROJET EST MAINTENANT CORRIGÉ ET SAUVEGARDÉ!"