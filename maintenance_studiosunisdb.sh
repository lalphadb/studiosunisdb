#!/bin/bash

echo "🔧 MAINTENANCE STUDIOSUNISDB"
echo "==========================="

# Clear cache Laravel
php artisan optimize:clear

# Nettoyer logs anciens (>7 jours)
find storage/logs -name "*.log" -mtime +7 -delete 2>/dev/null

# Nettoyer fichiers temporaires
find . -name "*.tmp" -delete 2>/dev/null
find . -name "*.bak" -delete 2>/dev/null
find . -name "*~" -delete 2>/dev/null

# Vérifier permissions storage
chmod -R 775 storage/ 2>/dev/null
chmod -R 775 bootstrap/cache/ 2>/dev/null

echo "✅ Maintenance terminée"

# Statistiques
echo ""
echo "📊 STATISTIQUES PROJET:"
echo "Fichiers PHP: $(find . -name "*.php" | wc -l)"
echo "Fichiers Blade: $(find . -name "*.blade.php" | wc -l)"
echo "Taille totale: $(du -sh . | cut -f1)"
echo "Logs: $(find storage/logs -name "*.log" | wc -l) fichiers"

