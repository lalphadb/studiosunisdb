#!/bin/bash
echo "🧪 Tests finaux..."

# Test base de données
php artisan db:show | head -5

# Test routes principales
php artisan route:list --path=dashboard | head -3

# Test migrations uniques
echo "Migrations ceintures/cours:"
ls database/migrations/ | grep -E "(ceintures|cours)" | wc -l

# Test Chart.js
npm list chart.js 2>/dev/null | grep chart.js || echo "Chart.js manquant"

# Test assets compilés
[ -f "public/build/manifest.json" ] && echo "✅ Assets OK" || echo "❌ Assets manquants"

echo "✅ Tests terminés"
