#!/bin/bash

echo "🔄 REMPLACEMENT DASHBOARD SIMPLE → ULTRA-PROFESSIONNEL"
echo "===================================================="

cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro

# 1. Sauvegarder l'ancien
echo "1. Sauvegarde ancien dashboard..."
if [ -f "resources/js/Pages/Dashboard.vue" ]; then
    cp resources/js/Pages/Dashboard.vue resources/js/Pages/Dashboard.vue.backup
    echo "✅ Dashboard simple sauvegardé"
fi

# 2. Vérifier quel dashboard est utilisé
echo "2. Vérification route dashboard..."
grep -n "Dashboard" app/Http/Controllers/DashboardController.php | grep "render"

# 3. Lister les dashboards disponibles
echo "3. Dashboards disponibles..."
find resources/js/Pages -name "*ashboard*" -type f

# 4. Remplacer par l'ultra-pro
echo "4. Remplacement par dashboard ultra-professionnel..."
if [ -f "resources/js/Pages/Dashboard/Admin.vue" ]; then
    # Copier l'ultra-pro vers Dashboard.vue principal
    cp resources/js/Pages/Dashboard/Admin.vue resources/js/Pages/Dashboard.vue
    echo "✅ Dashboard ultra-professionnel activé"
else
    echo "❌ Dashboard ultra-pro non trouvé - Création..."
fi

echo ""
echo "REMPLACEMENT TERMINÉ"
echo "Testez: http://localhost:8000/dashboard"