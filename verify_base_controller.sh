#!/bin/bash

echo "🔍 VÉRIFICATION BASEADMINCONTROLLER"
echo "==================================="

echo "Recherche des extends BaseAdminController dans les contrôleurs:"
find app/Http/Controllers/Admin -name "*.php" -exec grep -l "extends BaseAdminController" {} \;

echo ""
echo "Contrôleurs qui héritent de BaseAdminController:"
grep -r "extends BaseAdminController" app/Http/Controllers/Admin/ || echo "❌ AUCUN TROUVÉ!"

echo ""
echo "Vérification namespace BaseAdminController:"
grep -n "class BaseAdminController" app/Http/Controllers/Admin/BaseAdminController.php

echo ""
echo "Vérification imports dans autres contrôleurs:"
grep -r "use.*BaseAdminController" app/Http/Controllers/Admin/
