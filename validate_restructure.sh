#!/bin/bash

echo "✅ VALIDATION RESTRUCTURATION STUDIOSDB"
echo "======================================"

echo "1️⃣ Vérification structure finale..."
echo ""
echo "📁 ARBORESCENCE FINALE:"
tree resources/views -I "*.git" 2>/dev/null || find resources/views -type f -name "*.blade.php" | sort

echo ""
echo "2️⃣ Statistiques finales..."
echo "- Pages admin: $(find resources/views/pages/admin -name '*.blade.php' 2>/dev/null | wc -l)"
echo "- Components admin: $(find resources/views/components/admin -name '*.blade.php' 2>/dev/null | wc -l)"
echo "- Partials: $(find resources/views/partials -name '*.blade.php' 2>/dev/null | wc -l)"
echo "- Total vues: $(find resources/views -name '*.blade.php' 2>/dev/null | wc -l)"

echo ""
echo "3️⃣ Vérification références dans contrôleurs..."
grep -r "admin\." app/Http/Controllers/Admin/ | grep -v "pages.admin" | head -5

echo ""
echo "4️⃣ Test compilation..."
if command -v php &> /dev/null; then
    php artisan view:clear 2>/dev/null || echo "Artisan non disponible"
    php artisan optimize:clear 2>/dev/null || echo "Optimize non disponible"
fi

echo ""
echo "✅ Validation terminée!"
