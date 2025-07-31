#!/bin/bash
echo "=== DIAGNOSTIC STUDIOSDB v5 ==="
echo "Date: $(date)"
echo "Utilisateur: $(whoami)"
echo ""

# 1. Version Laravel RÉELLE
echo "🔍 VERSIONS SYSTÈME:"
cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro
php artisan --version 2>/dev/null || echo "❌ Artisan inaccessible"
php --version | head -1
composer --version | head -1
node --version 2>/dev/null || echo "❌ Node non installé"
npm --version 2>/dev/null || echo "❌ NPM non installé"

echo ""
echo "📦 COMPOSER DEPENDENCIES:"
composer show laravel/framework 2>/dev/null || echo "❌ Laravel non installé"
composer show inertiajs/inertia-laravel 2>/dev/null || echo "❌ Inertia non installé"

echo ""
echo "🗂️  STRUCTURE FICHIERS:"
ls -la app/Http/Controllers/MembreController.php 2>/dev/null || echo "❌ MembreController manquant"
ls -la app/Models/Membre.php 2>/dev/null || echo "❌ Model Membre manquant"
ls -la resources/js/Pages/Membres/ 2>/dev/null || echo "❌ Pages Vue Membres manquantes"

echo ""
echo "🎨 ASSETS COMPILATION:"
ls -la public/build/ 2>/dev/null || echo "❌ Build assets manquant"
ps aux | grep -E "(npm|vite)" | grep -v grep || echo "❌ Aucun processus build actif"

echo ""
echo "🗄️  BASE DE DONNÉES:"
php artisan migrate:status 2>/dev/null || echo "❌ Migrations non exécutées"
mysql -u studiosdb -p -e "SHOW DATABASES LIKE 'studiosdb%';" 2>/dev/null || echo "❌ Connexion DB impossible"

echo ""
echo "🌐 SERVEUR WEB:"
sudo systemctl status nginx | grep Active || echo "❌ Nginx status inconnu"
sudo systemctl status php8.3-fpm | grep Active || echo "❌ PHP-FPM status inconnu"
curl -s -o /dev/null -w "%{http_code}" http://studiosdb.local/dashboard || echo "❌ Site inaccessible"

echo ""
echo "📋 LOGS RÉCENTS:"
echo "--- Laravel Logs (5 dernières lignes) ---"
tail -5 storage/logs/laravel.log 2>/dev/null || echo "❌ Pas de logs Laravel"
echo ""
echo "--- Nginx Error Logs (3 dernières lignes) ---"
sudo tail -3 /var/log/nginx/error.log 2>/dev/null || echo "❌ Pas de logs Nginx"

echo ""
echo "✅ DIAGNOSTIC TERMINÉ"
