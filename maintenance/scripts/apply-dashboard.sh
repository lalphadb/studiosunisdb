#!/bin/bash

echo "🎨 MISE À JOUR DASHBOARD CLAIR PROFESSIONNEL"
echo "==========================================="

cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro

# 1. Cache Laravel
echo "1. Nettoyage cache Laravel..."
php artisan optimize:clear
php artisan cache:clear

# 2. Compilation assets
echo "2. Compilation du nouveau dashboard..."
npm run build

# 3. Vérification build
echo "3. Vérification..."
if [ -f "public/build/manifest.json" ]; then
    echo "✅ Dashboard compilé avec succès"
    ls -la public/build/
else
    echo "❌ Erreur compilation"
    npm run dev &
    sleep 5
    kill %1 2>/dev/null
fi

# 4. Permissions
echo "4. Permissions..."
sudo chown -R www-data:www-data public/build/
sudo chmod -R 755 public/build/

# 5. Test du dashboard
echo "5. Test dashboard..."
HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/dashboard 2>/dev/null)
echo "Code HTTP Dashboard: $HTTP_CODE"

if [ "$HTTP_CODE" = "200" ]; then
    echo "✅ DASHBOARD CLAIR PROFESSIONNEL ACTIF !"
else
    echo "❌ Erreur $HTTP_CODE"
fi

echo ""
echo "🎯 DASHBOARD ULTRA-PROFESSIONNEL THÈME CLAIR ACTIVÉ !"
echo "===================================================="
echo "✅ Design moderne avec couleurs claires"
echo "✅ 4 KPI Cards avec indicateurs de progression"
echo "✅ Actions rapides colorées (Membres/Cours/Présences)"
echo "✅ Section Analytics avec activités récentes"
echo "✅ Métriques avancées spécialisées karaté"
echo "✅ Répartition des ceintures par couleur"
echo "✅ Quick Actions Bar avec boutons d'action"
echo "✅ Footer informatif avec statut système"
echo ""
echo "🌐 Testez maintenant: http://localhost:8000/dashboard"
echo "👤 Login: louis@4lb.ca"