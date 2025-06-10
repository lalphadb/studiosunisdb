#!/bin/bash

echo "🧹 NETTOYAGE AGRESSIF POUR GITHUB"
echo "================================="

# Supprimer fichiers parasites Laravel
echo "🗑️ Suppression fichiers parasites..."
rm -f 'nullable'* 'required'* *.tmp *.bak *~ .*.swp

# Supprimer anciens rapports et logs
rm -f RAPPORT_*.txt diagnostic_*.txt analyze_*.sh check_status.sh

# Nettoyer storage complètement
echo "🧹 Nettoyage storage..."
find storage/logs -name "*.log" -delete 2>/dev/null || true
find storage/framework/cache -name "*" -not -name ".gitkeep" -delete 2>/dev/null || true
find storage/framework/sessions -name "*" -not -name ".gitkeep" -delete 2>/dev/null || true
find storage/framework/views -name "*" -not -name ".gitkeep" -delete 2>/dev/null || true

# Créer .gitkeep manquants
touch storage/app/.gitkeep
touch storage/app/public/.gitkeep
touch storage/framework/cache/.gitkeep
touch storage/framework/sessions/.gitkeep
touch storage/framework/testing/.gitkeep
touch storage/framework/views/.gitkeep
touch storage/logs/.gitkeep

# Nettoyer node_modules et vendor (seront réinstallés)
echo "🗑️ Suppression node_modules et vendor..."
rm -rf node_modules
rm -rf vendor

# Clear tous les caches Laravel
echo "🧹 Clear cache Laravel..."
php artisan config:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true
php artisan cache:clear 2>/dev/null || true

echo "✅ Nettoyage local terminé"

