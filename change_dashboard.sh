#!/bin/bash

echo "🔄 Changement de Dashboard StudiosDB v5"
echo "======================================"
echo ""

case "$1" in
    "simple"|"admin")
        sed -i "s/Dashboard'/Dashboard\/Admin'/g" app/Http/Controllers/DashboardController.php
        sed -i "s/Dashboard/Dashboard\/Admin/g" app/Http/Controllers/DashboardController.php
        echo "✅ Dashboard Simple activé"
        ;;
    "pro"|"professionnel")
        sed -i "s/Dashboard\/Admin/Dashboard/g" app/Http/Controllers/DashboardController.php
        echo "✅ Dashboard Professionnel activé (thème sombre)"
        ;;
    "ultra")
        if [ -f "resources/js/Pages/DashboardUltraPro.vue" ]; then
            cp resources/js/Pages/DashboardUltraPro.vue resources/js/Pages/Dashboard.vue
        elif [ -f "resources/js/Pages/Dashboard/Variants/DashboardUltraPro.vue" ]; then
            cp resources/js/Pages/Dashboard/Variants/DashboardUltraPro.vue resources/js/Pages/Dashboard.vue
        fi
        sed -i "s/Dashboard\/Admin/Dashboard/g" app/Http/Controllers/DashboardController.php
        echo "✅ Dashboard Ultra Pro activé (animations + gradients)"
        ;;
    *)
        echo "Usage: ./change_dashboard.sh [simple|pro|ultra]"
        echo ""
        echo "Options disponibles:"
        echo "  simple  - Dashboard basique et rapide"
        echo "  pro     - Dashboard professionnel avec thème sombre" 
        echo "  ultra   - Dashboard ultra moderne avec animations"
        echo ""
        echo "Dashboard actuel:"
        grep -o "Dashboard[^']*" app/Http/Controllers/DashboardController.php | head -1
        exit 1
        ;;
esac

echo ""
echo "🚀 Redémarrez les serveurs pour voir les changements:"
echo "   npm run dev"
echo "   php artisan serve"
