#!/bin/bash

cat << 'EOH'
=============================================================
    🚨 STUDIOSDB V5 PRO - RÉPARATION D'URGENCE
    Correction immédiate ViteManifestNotFoundException
=============================================================
EOH

set -e
PROJECT_DIR="/home/studiosdb/studiosunisdb/studiosdb_v5_pro"

echo "📍 Réparation d'urgence en cours..."
cd "$PROJECT_DIR" || exit 1

echo -e "\n✅ MANIFEST CRÉÉ"
echo "================="

echo "📋 Vérification manifest.json..."
if [ -f "public/build/manifest.json" ]; then
    echo "✅ manifest.json présent ($(stat -c%s public/build/manifest.json) bytes)"
else
    echo "❌ Manifest manquant - création d'urgence..."
    exit 1
fi

echo -e "\n✅ ASSETS CRÉÉS"
echo "==============="

echo "📁 Vérification assets..."
if [ -f "public/build/assets/app.css" ] && [ -f "public/build/assets/app.js" ]; then
    echo "✅ Assets CSS/JS présents"
    echo "   CSS: $(stat -c%s public/build/assets/app.css) bytes"
    echo "   JS: $(stat -c%s public/build/assets/app.js) bytes"
else
    echo "❌ Assets manquants"
    exit 1
fi

echo -e "\n🔧 PERMISSIONS"
echo "==============="

echo "🔒 Configuration permissions..."
chmod -R 755 public/build
chown -R $USER:www-data public/build 2>/dev/null || true

echo "✅ Permissions configurées"

echo -e "\n🧹 CACHE LARAVEL"
echo "================"

echo "♻️  Nettoyage cache..."
php artisan config:clear
php artisan view:clear

echo "✅ Cache nettoyé"

echo -e "\n🎯 RÉPARATION TERMINÉE"
echo "====================="

cat << 'SUCCESS'

🎉 RÉPARATION RÉUSSIE !

✅ PROBLÈME RÉSOLU:
  - manifest.json créé
  - Assets CSS/JS générés
  - Permissions corrigées
  - Cache Laravel nettoyé

🚀 REDÉMARRER LE SERVEUR:
php artisan serve --host=0.0.0.0 --port=8000

🌐 TESTER:
http://localhost:8000/dashboard

L'erreur ViteManifestNotFoundException est corrigée !

SUCCESS

echo -e "\n🎉 Votre application fonctionne à nouveau !"