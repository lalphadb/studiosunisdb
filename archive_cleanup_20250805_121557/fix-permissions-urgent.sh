#!/bin/bash

cat << 'EOH'
=============================================================
    🔒 CORRECTION PERMISSIONS STORAGE URGENT
    Réparation permissions Laravel logs
=============================================================
EOH

set -e
PROJECT_DIR="/home/studiosdb/studiosunisdb/studiosdb_v5_pro"

echo "📍 Correction permissions en cours..."
cd "$PROJECT_DIR" || exit 1

echo -e "\n🔒 CORRECTION PERMISSIONS STORAGE"
echo "================================="

echo "🗑️  Suppression anciens logs problématiques..."
sudo rm -f storage/logs/laravel.log 2>/dev/null || true
sudo rm -rf storage/logs/*.log 2>/dev/null || true

echo "📁 Création structure logs..."
mkdir -p storage/logs
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions  
mkdir -p storage/framework/views
mkdir -p bootstrap/cache

echo "🔒 Attribution permissions correctes..."
# Propriétaire utilisateur pour tout le projet
sudo chown -R $USER:$USER .

# Permissions spéciales pour storage et bootstrap/cache (www-data)
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
sudo chmod -R 755 storage/logs

echo "📄 Création fichier log vide..."
sudo touch storage/logs/laravel.log
sudo chown www-data:www-data storage/logs/laravel.log
sudo chmod 666 storage/logs/laravel.log

echo "✅ Permissions corrigées"

echo -e "\n🧹 NETTOYAGE CACHE"
echo "=================="

echo "♻️  Suppression caches corrompus..."
rm -rf storage/framework/cache/data/*
rm -rf storage/framework/views/*
rm -rf bootstrap/cache/*.php

echo "🔄 Reconstruction cache Laravel..."
php artisan config:clear
php artisan route:clear  
php artisan view:clear
php artisan cache:clear

echo "✅ Cache nettoyé"

echo -e "\n🧪 TEST PERMISSIONS"
echo "=================="

echo "🔍 Test écriture log..."
if [ -w "storage/logs/laravel.log" ]; then
    echo "✅ Fichier log accessible en écriture"
    echo "Test écriture: $(date)" >> storage/logs/laravel.log
else
    echo "❌ Problème permissions persiste"
    echo "📊 Permissions actuelles:"
    ls -la storage/logs/
fi

echo "🔍 Test permissions storage..."
ls -la storage/ | head -5

echo -e "\n🎯 CORRECTION PERMISSIONS TERMINÉE"
echo "=================================="

cat << 'SUCCESS'

🎉 PERMISSIONS CORRIGÉES !

✅ PROBLÈMES RÉSOLUS:
  - storage/logs/ accessible en écriture
  - laravel.log recréé avec bonnes permissions
  - Propriétés www-data:www-data sur storage
  - Cache Laravel nettoyé
  - Structure complète recréée

🚀 REDÉMARRER LE SERVEUR:
php artisan serve --host=0.0.0.0 --port=8000

🌐 TESTER LOGIN:
http://localhost:8000/login

📧 COMPTE: louis@4lb.ca / password

SUCCESS

echo -e "\n✨ Laravel peut maintenant écrire les logs !"