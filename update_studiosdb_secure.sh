#!/bin/bash

echo "🚨 MISE À JOUR MAJEURE STUDIOSDB V4"
echo "=================================="
echo ""
echo "⚠️  ATTENTION: Laravel 11 → 12 (MAJEURE)"
echo "⚠️  ATTENTION: Plusieurs mises à jour majeures détectées"
echo ""

cd /home/studiosdb/studiosunisdb/studiosdb-v4

read -p "Voulez-vous continuer avec ces mises à jour majeures ? (y/N): " confirm
if [[ $confirm != [yY] ]]; then
    echo "❌ Mise à jour annulée"
    exit 1
fi

echo ""
echo "=== ÉTAPE 1: SAUVEGARDE COMPLÈTE ==="
echo ""

# Sauvegarde complète
echo "💾 Création sauvegarde complète..."
cd /home/studiosdb/studiosunisdb/
tar -czf "studiosdb-v4-before-major-update-$(date +%Y%m%d_%H%M%S).tar.gz" \
    --exclude='studiosdb-v4/vendor' \
    --exclude='studiosdb-v4/node_modules' \
    --exclude='studiosdb-v4/storage/logs' \
    --exclude='studiosdb-v4/bootstrap/cache' \
    studiosdb-v4/

cd studiosdb-v4

# Sauvegarder composer files
cp composer.json composer.json.backup
cp composer.lock composer.lock.backup

echo "✅ Sauvegarde créée"

echo ""
echo "=== ÉTAPE 2: TEST FONCTIONNEMENT ACTUEL ==="
echo ""

# Test avant mise à jour
if php artisan --version >/dev/null 2>&1; then
    echo "✅ Laravel fonctionne actuellement"
    php artisan --version
else
    echo "❌ Laravel ne fonctionne pas - ARRÊT"
    exit 1
fi

echo ""
echo "=== ÉTAPE 3: MISE À JOUR PROGRESSIVE ==="
echo ""

# Étape 3a: Mises à jour mineures d'abord
echo "📦 Mise à jour des dépendances mineures..."
composer update --prefer-stable --no-dev

# Test intermédiaire
if ! php artisan --version >/dev/null 2>&1; then
    echo "❌ Erreur après mises à jour mineures - RESTAURATION"
    cp composer.json.backup composer.json
    cp composer.lock.backup composer.lock
    composer install
    exit 1
fi

echo "✅ Mises à jour mineures OK"

echo ""
echo "=== ÉTAPE 4: MISE À JOUR LARAVEL 12 ==="
echo ""

echo "🚨 Mise à jour vers Laravel 12..."
echo "Cette étape peut créer des incompatibilités !"

read -p "Continuer avec Laravel 12 ? (y/N): " confirm_laravel
if [[ $confirm_laravel == [yY] ]]; then
    # Mise à jour Laravel vers version 12
    composer require "laravel/framework:^12.0" --with-all-dependencies
    
    # Test critique
    if php artisan --version >/dev/null 2>&1; then
        echo "✅ Laravel 12 installé avec succès"
        php artisan --version
    else
        echo "❌ ERREUR CRITIQUE Laravel 12 - RESTAURATION IMMÉDIATE"
        cp composer.json.backup composer.json
        cp composer.lock.backup composer.lock
        composer install --no-dev
        echo "✅ Restauration vers état précédent"
        exit 1
    fi
else
    echo "⚠️ Laravel 12 ignoré - gardons Laravel 11"
fi

echo ""
echo "=== ÉTAPE 5: MISE À JOUR AUTRES PACKAGES MAJEURS ==="
echo ""

# Mise à jour filament-excel
echo "📊 Mise à jour filament-excel..."
if composer require "pxlrbt/filament-excel:^3.0"; then
    echo "✅ Filament Excel 3.0 installé"
else
    echo "⚠️ Problème avec Filament Excel 3.0"
fi

# Mise à jour dompdf
echo "📄 Mise à jour dompdf..."
if composer require "barryvdh/laravel-dompdf:^3.0"; then
    echo "✅ DomPDF 3.0 installé"
else
    echo "⚠️ Problème avec DomPDF 3.0"
fi

echo ""
echo "=== ÉTAPE 6: NETTOYAGE ET OPTIMISATION ==="
echo ""

# Nettoyer tous les caches
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Regénérer l'autoloader
composer dump-autoload --optimize

# Mettre en cache la config
php artisan config:cache

echo ""
echo "=== ÉTAPE 7: TESTS FINAUX ==="
echo ""

# Test artisan
echo "🧪 Test Artisan..."
if php artisan --version; then
    echo "✅ Artisan fonctionne"
else
    echo "❌ Artisan ne fonctionne pas"
    exit 1
fi

# Test serveur
echo ""
echo "🚀 Test serveur (10 secondes)..."
timeout 10s php artisan serve --host=0.0.0.0 --port=8001 &
SERVER_PID=$!
sleep 3

if kill -0 $SERVER_PID 2>/dev/null; then
    echo "✅ Serveur fonctionne !"
    kill $SERVER_PID
    wait $SERVER_PID 2>/dev/null
    
    echo ""
    echo "🎉 MISE À JOUR TERMINÉE AVEC SUCCÈS !"
    echo ""
    echo "📊 NOUVELLES VERSIONS:"
    php artisan about | grep -E "(Laravel Version|PHP Version|Filament)"
    
    echo ""
    echo "🌐 TESTEZ L'INTERFACE:"
    echo "http://localhost:8001/admin"
    echo ""
    echo "💾 SAUVEGARDE DISPONIBLE EN CAS DE PROBLÈME:"
    ls -la /home/studiosdb/studiosunisdb/studiosdb-v4-before-major-update-*.tar.gz | tail -1
    
else
    echo "❌ ERREUR SERVEUR - RESTAURATION RECOMMANDÉE"
    echo ""
    echo "🔄 POUR RESTAURER:"
    echo "cp composer.json.backup composer.json"
    echo "cp composer.lock.backup composer.lock"
    echo "composer install"
fi

echo ""
echo "=== RÉSUMÉ ==="
echo "Testez soigneusement toutes les fonctionnalités de StudiosDB"
echo "Si problème, restaurez avec les fichiers .backup"
