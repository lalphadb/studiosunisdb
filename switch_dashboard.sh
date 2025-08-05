#!/bin/bash
# Switch rapide entre dashboards

echo "🔄 Switch Dashboard StudiosDB v5"
echo "================================"

CURRENT=$(grep -o "Dashboard[^']*" app/Http/Controllers/DashboardController.php | head -1)
echo "Dashboard actuel: $CURRENT"
echo ""

case "$1" in
    "simple"|"admin")
        sed -i 's/Dashboard[^'"'"']*/Dashboard\/Admin/g' app/Http/Controllers/DashboardController.php
        echo "✅ Changé vers Dashboard Simple"
        ;;
    "pro"|"professionnel")
        sed -i 's/Dashboard[^'"'"']*/Dashboard/g' app/Http/Controllers/DashboardController.php
        echo "✅ Changé vers Dashboard Professionnel"
        ;;
    "ultra")
        cp resources/js/Pages/DashboardUltraPro.vue resources/js/Pages/Dashboard.vue 2>/dev/null
        sed -i 's/Dashboard[^'"'"']*/Dashboard/g' app/Http/Controllers/DashboardController.php
        echo "✅ Changé vers Dashboard Ultra Pro"
        ;;
    *)
        echo "Usage: ./switch_dashboard.sh [simple|pro|ultra]"
        echo ""
        echo "Exemples:"
        echo "  ./switch_dashboard.sh simple    # Dashboard basique"
        echo "  ./switch_dashboard.sh pro       # Dashboard professionnel"
        echo "  ./switch_dashboard.sh ultra     # Dashboard ultra moderne"
        ;;
esac

echo ""
echo "🚀 Redémarrez les serveurs pour voir les changements"
