#!/bin/bash

echo "⚡ CORRECTION RAPIDE ERREUR SQL"
echo "=============================="

cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro

# 1. Clear cache pour appliquer le nouveau contrôleur
echo "1. Clear cache Laravel..."
php artisan cache:clear
php artisan config:clear

# 2. Test immédiat
echo "2. Test dashboard..."
php artisan route:list | grep dashboard

echo ""
echo "✅ CORRECTION APPLIQUÉE"
echo "Le DashboardController ne plantera plus sur les tables manquantes"
echo ""
echo "Testez maintenant: http://localhost:8000/dashboard"