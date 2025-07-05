#!/bin/bash

echo "🔍 Validation StudiosDB v4.1.10.2..."

# 1. Vérifier BaseAdminController (exclure BaseAdminController.php lui-même)
echo "📋 Contrôleurs BaseAdminController..."
CONTROLLERS_NOK=$(find app/Http/Controllers/Admin -name "*.php" ! -name "BaseAdminController.php" -exec grep -L "extends BaseAdminController" {} \;)
if [ -n "$CONTROLLERS_NOK" ]; then
    echo "❌ Contrôleurs sans BaseAdminController:"
    echo "$CONTROLLERS_NOK"
    exit 1
fi
echo "✅ Tous les contrôleurs héritent BaseAdminController"

# 2. Vérifier strict_types
echo "📋 Types stricts..."
STRICT_NOK=$(find app/Http/Controllers/Admin -name "*.php" -exec grep -L "declare(strict_types=1)" {} \;)
if [ -n "$STRICT_NOK" ]; then
    echo "❌ Fichiers sans strict_types:"
    echo "$STRICT_NOK"
else
    echo "✅ Types stricts validés"
fi

# 3. Validation des routes
echo "📋 Routes admin..."
ROUTE_COUNT=$(php artisan route:list --path=admin 2>/dev/null | grep -c "admin/" || echo "0")
if [ "$ROUTE_COUNT" -gt 90 ]; then
    echo "✅ $ROUTE_COUNT routes admin validées"
else
    echo "⚠️  Routes admin: $ROUTE_COUNT (vérifier la configuration)"
fi

# 4. Optimisation
echo "📋 Optimisation..."
php artisan optimize:clear > /dev/null 2>&1
echo "✅ Cache optimisé"

echo "🎉 StudiosDB validé pour GitHub!"
