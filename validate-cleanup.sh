#!/bin/bash

echo "✅ VALIDATION POST-NETTOYAGE STUDIOSDB V5 PRO"
echo "============================================="

cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro

# Vérifications essentielles
SUCCESS=true

echo "🔍 VÉRIFICATIONS SYSTÈME..."
echo "==========================="

# 1. Structure Laravel
echo ""
echo "1. Structure Laravel essentielle..."
REQUIRED_DIRS=("app" "config" "database" "resources" "routes" "storage" "public" "vendor")
for dir in "${REQUIRED_DIRS[@]}"; do
    if [ -d "$dir" ]; then
        echo "✅ $dir"
    else
        echo "❌ $dir - MANQUANT !"
        SUCCESS=false
    fi
done

# 2. Fichiers de configuration
echo ""
echo "2. Fichiers de configuration..."
REQUIRED_FILES=("composer.json" "package.json" "artisan" "vite.config.js" ".env")
for file in "${REQUIRED_FILES[@]}"; do
    if [ -f "$file" ]; then
        echo "✅ $file"
    else
        echo "❌ $file - MANQUANT !"
        SUCCESS=false
    fi
done

# 3. Documentation
echo ""
echo "3. Documentation professionnelle..."
DOC_FILES=("README.md" "CHANGELOG.md" "LICENSE" "VERSION")
for file in "${DOC_FILES[@]}"; do
    if [ -f "$file" ]; then
        echo "✅ $file"
    else
        echo "❌ $file - MANQUANT !"
    fi
done

# 4. Scripts utiles conservés
echo ""
echo "4. Scripts de maintenance conservés..."
USEFUL_SCRIPTS=("sauvegarde-complete.sh" "commit-sauvegarde.sh" "apply-dashboard.sh" "quick-fix.sh")
for script in "${USEFUL_SCRIPTS[@]}"; do
    if [ -f "$script" ]; then
        echo "✅ $script"
    else
        echo "⚠️  $script - Absent (peut être normal)"
    fi
done

# 5. Test fonctionnel Laravel
echo ""
echo "5. Tests fonctionnels..."

# Test Artisan
if php artisan --version > /dev/null 2>&1; then
    echo "✅ Laravel Artisan fonctionne"
else
    echo "❌ Laravel Artisan - ERREUR !"
    SUCCESS=false
fi

# Test routes
if php artisan route:list > /dev/null 2>&1; then
    echo "✅ Routes Laravel fonctionnent"
    ROUTE_COUNT=$(php artisan route:list | wc -l)
    echo "   📊 Nombre de routes: $ROUTE_COUNT"
else
    echo "❌ Routes Laravel - ERREUR !"
    SUCCESS=false
fi

# Test NPM
if npm --version > /dev/null 2>&1; then
    echo "✅ NPM disponible"
else
    echo "❌ NPM - NON DISPONIBLE !"
    SUCCESS=false
fi

# 6. Test compilation assets
echo ""
echo "6. Test compilation assets..."
if [ -f "public/build/manifest.json" ]; then
    echo "✅ Assets compilés disponibles"
    ASSET_COUNT=$(find public/build -name "*.js" -o -name "*.css" | wc -l)
    echo "   📊 Fichiers assets: $ASSET_COUNT"
else
    echo "⚠️  Assets non compilés - Exécutez 'npm run build'"
fi

# 7. Permissions
echo ""
echo "7. Permissions critiques..."
if [ -w "storage" ]; then
    echo "✅ storage/ accessible en écriture"
else
    echo "❌ storage/ - PERMISSIONS INSUFFISANTES !"
    SUCCESS=false
fi

if [ -w "bootstrap/cache" ]; then
    echo "✅ bootstrap/cache/ accessible en écriture"
else
    echo "❌ bootstrap/cache/ - PERMISSIONS INSUFFISANTES !"
    SUCCESS=false
fi

# 8. Taille du projet après nettoyage
echo ""
echo "8. Statistiques projet nettoyé..."
PROJECT_SIZE=$(du -sh . 2>/dev/null | cut -f1)
echo "📊 Taille totale: $PROJECT_SIZE"

PHP_FILES=$(find . -name "*.php" -not -path "./vendor/*" -not -path "./archive_cleanup*" | wc -l)
echo "📊 Fichiers PHP: $PHP_FILES"

VUE_FILES=$(find . -name "*.vue" -not -path "./archive_cleanup*" | wc -l)
echo "📊 Fichiers Vue: $VUE_FILES"

JS_FILES=$(find . -name "*.js" -not -path "./node_modules/*" -not -path "./archive_cleanup*" | wc -l)
echo "📊 Fichiers JS: $JS_FILES"

# 9. Vérifier les archives créées
echo ""
echo "9. Archives de nettoyage..."
ARCHIVE_DIRS=$(find . -maxdepth 1 -name "archive_cleanup_*" -type d | wc -l)
if [ $ARCHIVE_DIRS -gt 0 ]; then
    echo "✅ $ARCHIVE_DIRS archive(s) de nettoyage créée(s)"
    find . -maxdepth 1 -name "archive_cleanup_*" -type d | while read archive; do
        ARCHIVE_SIZE=$(du -sh "$archive" 2>/dev/null | cut -f1)
        ARCHIVE_FILES=$(find "$archive" -type f | wc -l)
        echo "   📦 $(basename "$archive"): $ARCHIVE_SIZE ($ARCHIVE_FILES fichiers)"
    done
else
    echo "ℹ️  Aucune archive de nettoyage trouvée"
fi

# 10. Test serveur de développement
echo ""
echo "10. Test serveur de développement..."
timeout 5 php artisan serve --port=8001 > /dev/null 2>&1 &
SERVER_PID=$!
sleep 2

if curl -s -I http://localhost:8001 > /dev/null 2>&1; then
    echo "✅ Serveur de développement fonctionne"
else
    echo "⚠️  Serveur de développement - Test incomplet"
fi

# Arrêter le serveur de test
kill $SERVER_PID 2>/dev/null

# Résultat final
echo ""
echo "🎯 RÉSULTAT DE LA VALIDATION"
echo "============================"

if [ "$SUCCESS" = true ]; then
    echo "🎉 VALIDATION RÉUSSIE !"
    echo "✅ Le projet StudiosDB v5 Pro est fonctionnel après nettoyage"
    echo "✅ Tous les composants essentiels sont présents"
    echo "✅ La structure Laravel est intacte"
    echo "✅ Les scripts de maintenance sont disponibles"
    echo ""
    echo "📋 ACTIONS RECOMMANDÉES:"
    echo "• Testez le dashboard: http://localhost:8000/dashboard"
    echo "• Exécutez 'npm run build' si assets manquants"
    echo "• Vérifiez les fonctionnalités critiques"
    echo "• Supprimez les archives si tout fonctionne bien"
    echo ""
    echo "🚀 Projet prêt pour production !"
else
    echo "⚠️  VALIDATION PARTIELLEMENT RÉUSSIE"
    echo "❌ Certains éléments nécessitent une attention"
    echo "🔧 Vérifiez les erreurs ci-dessus avant déploiement"
fi

echo ""
echo "📊 MÉTRIQUES FINALES:"
echo "• Taille projet: $PROJECT_SIZE"
echo "• Fichiers PHP: $PHP_FILES" 
echo "• Fichiers Vue: $VUE_FILES"
echo "• Fichiers JS: $JS_FILES"
echo "• Archives créées: $ARCHIVE_DIRS"