#!/bin/bash

echo "🔧 CORRECTION ERREUR SQL + ACTIVATION DASHBOARD ULTRA-PRO"
echo "========================================================="

cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro

# 1. Vérifier les tables existantes
echo "1. Tables existantes dans la base de données..."
mysql -u studiosdb -p studiosdb_central -e "SHOW TABLES;" 2>/dev/null || echo "Erreur connexion DB"

# 2. Vérifier quel dashboard est rendu
echo ""
echo "2. Dashboard rendu par le contrôleur..."
grep -A 3 "Inertia::render" app/Http/Controllers/DashboardController.php

# 3. S'assurer que Dashboard/Admin.vue existe
echo ""
echo "3. Vérification Dashboard/Admin.vue..."
if [ -f "resources/js/Pages/Dashboard/Admin.vue" ]; then
    echo "✅ Dashboard/Admin.vue existe"
    head -5 resources/js/Pages/Dashboard/Admin.vue
else
    echo "❌ Dashboard/Admin.vue manquant"
fi

# 4. Clear cache Laravel
echo ""
echo "4. Clear cache Laravel..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# 5. Test du dashboard corrigé
echo ""
echo "5. Test dashboard corrigé..."
curl -s -I http://localhost:8000/dashboard | head -1

echo ""
echo "CORRECTION TERMINÉE"
echo "Testez maintenant: http://localhost:8000/dashboard"