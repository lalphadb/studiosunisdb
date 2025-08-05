#!/bin/bash

echo "🕵️ DIAGNOSTIC DASHBOARD ACTUEL"
echo "=============================="

cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro

# 1. Voir quelle vue est rendue dans le contrôleur
echo "1. Controller Dashboard - Vue utilisée:"
grep -A 5 -B 5 "Inertia::render" app/Http/Controllers/DashboardController.php

echo ""
echo "2. Dashboards disponibles:"
find resources/js/Pages -name "*ashboard*" -type f

echo ""
echo "3. Contenu début Dashboard.vue (si existe):"
head -5 resources/js/Pages/Dashboard.vue 2>/dev/null || echo "❌ Dashboard.vue n'existe pas"

echo ""
echo "4. Contenu début Dashboard/Admin.vue (si existe):"
head -5 resources/js/Pages/Dashboard/Admin.vue 2>/dev/null || echo "❌ Dashboard/Admin.vue n'existe pas"

echo ""
echo "5. Route dashboard dans web.php:"
grep -A 2 -B 2 "/dashboard" routes/web.php

echo ""
echo "DIAGNOSTIC TERMINÉ"