#!/bin/bash

echo "🔍 SUITE VÉRIFICATION STUDIOSDB v5 PRO"
echo "====================================="

cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro

echo "🔍 STRUCTURE COMPLÈTE PROJET"
ls -la

echo ""
echo "🔍 FICHIERS .ENV"
ls -la .env* 2>/dev/null || echo "❌ Fichiers .env non trouvés"

echo ""
echo "🔍 COMPOSER.JSON - DÉPENDANCES LARAVEL 12"
cat composer.json | grep -A 10 -B 5 "require"

echo ""
echo "🔍 PACKAGE.JSON - FRONTEND"
cat package.json | grep -A 10 -B 5 "dependencies"

echo ""
echo "🔍 ARTISAN COMMANDS DISPONIBLES"
php artisan list | head -20

echo ""
echo "🔍 CONFIGURATION LARAVEL 12"
php artisan about

echo ""
echo "🔍 ÉTAT BASE DE DONNÉES"
mysql -e "SHOW DATABASES LIKE 'studiosdb%';" 2>/dev/null || echo "❌ MySQL non accessible"

echo ""
echo "🔍 MIGRATIONS STATUS"
php artisan migrate:status 2>/dev/null || echo "❌ DB non configurée"

echo ""
echo "🔍 ROUTES ACTUELLES"
php artisan route:list | head -10

echo ""
echo "🔍 PERMISSIONS CRITIQUES"
ls -ld storage/
ls -ld bootstrap/cache/
ls -ld public/

echo ""
echo "🔍 SERVICES SYSTÈME"
systemctl is-active nginx php8.3-fpm mysql redis-server

echo ""
echo "🔍 PROCESSUS ACTIFS"
ps aux | grep -E "(nginx|php|mysql|redis)" | grep -v grep

echo ""
echo "🔍 LOGS LARAVEL RÉCENTS"
tail -10 storage/logs/laravel.log 2>/dev/null || echo "❌ Pas de logs Laravel"

echo ""
echo "🔍 NPM/NODE STATUS"
node --version 2>/dev/null || echo "❌ Node.js non installé"
npm --version 2>/dev/null || echo "❌ NPM non installé"

echo ""
echo "🔍 FRONTEND BUILD STATUS"
ls -la public/build/ 2>/dev/null || echo "❌ Assets non compilés"
