#!/bin/bash

echo "=== VÉRIFICATION STUDIOSDB v5 PRO ==="
echo "Date: $(date)"
echo "Serveur: $(hostname)"
echo ""

echo "🔍 VERSION LARAVEL"
cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro
php artisan --version 2>/dev/null || echo "❌ Laravel non accessible"
echo ""

echo "🔍 COMPOSER & DÉPENDANCES"
composer show laravel/framework 2>/dev/null | head -3 || echo "❌ Composer non trouvé"
echo ""

echo "🔍 ÉTAT DES FICHIERS PROJET"
ls -la /home/studiosdb/studiosunisdb/studiosdb_v5_pro/
echo ""

echo "🔍 FICHIERS CONFIGURATION"
ls -la /home/studiosdb/studiosunisdb/studiosdb_v5_pro/.env* 2>/dev/null || echo "❌ Fichiers .env manquants"
echo ""

echo "🔍 PERMISSIONS DOSSIERS"
ls -ld /home/studiosdb/studiosunisdb/studiosdb_v5_pro/storage/
ls -ld /home/studiosdb/studiosunisdb/studiosdb_v5_pro/bootstrap/cache/
echo ""

echo "🔍 BASE DE DONNÉES"
mysql -e "SHOW DATABASES LIKE 'studiosdb%';" 2>/dev/null || echo "❌ MySQL non accessible"
echo ""

echo "🔍 SERVICES SYSTÈME"
systemctl status nginx --no-pager -l | head -3
systemctl status php8.3-fpm --no-pager -l | head -3  
systemctl status mysql --no-pager -l | head -3
systemctl status redis --no-pager -l | head -3
echo ""

echo "🔍 PROCESSUS LARAVEL"
ps aux | grep -E "(artisan|php)" | grep -v grep
echo ""

echo "🔍 LOGS RÉCENTS"
tail -5 /home/studiosdb/studiosunisdb/studiosdb_v5_pro/storage/logs/laravel.log 2>/dev/null || echo "❌ Logs Laravel non trouvés"

echo ""
echo "=== FIN VÉRIFICATION ==="
