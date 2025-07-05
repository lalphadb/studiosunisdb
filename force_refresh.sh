#!/bin/bash

echo "🔄 FORCER RAFRAÎCHISSEMENT COMPLET"
echo "=================================="

# Nettoyer tous les caches
echo "1. Nettoyage caches complet:"
php artisan optimize:clear
php artisan view:clear
php artisan config:clear
php artisan route:clear
php artisan cache:clear

# Recompiler les vues
echo ""
echo "2. Recompilation vues:"
php artisan view:cache

# Afficher le layout final
echo ""
echo "3. Layout admin final:"
echo "====================="
cat resources/views/layouts/admin.blade.php | grep -A 5 -B 5 "navigation"

echo ""
echo "4. Structure navigation actuelle:"
echo "================================"
if [ -f "resources/views/components/admin/navigation.blade.php" ]; then
    echo "✅ Component navigation: $(wc -l < resources/views/components/admin/navigation.blade.php) lignes"
else
    echo "❌ Component navigation manquant"
fi

if [ -f "resources/views/partials/admin-navigation.blade.php" ]; then
    echo "⚠️ Partials navigation encore présent"
else
    echo "✅ Partials navigation supprimé"
fi

echo ""
echo "✅ RAFRAÎCHISSEMENT TERMINÉ"
echo "👆 Appuyez sur F5 ou Ctrl+R dans votre navigateur"
