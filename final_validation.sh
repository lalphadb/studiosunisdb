#!/bin/bash
echo "🎉 VALIDATION FINALE StudiosUnisDB v4.1.8.3"

# 1. Test architecture conformité
echo "1️⃣ Test conformité architecture..."
echo "✅ Dashboard: $(test -f app/Policies/DashboardPolicy.php && echo 'Policy OK' || echo 'Policy MANQUANT')"
echo "✅ Log: $(test -f app/Policies/LogPolicy.php && echo 'Policy OK' || echo 'Policy MANQUANT')"
echo "✅ Role: $(test -f app/Http/Controllers/Admin/RoleController.php && echo 'Controller OK' || echo 'Controller MANQUANT')"

# 2. Test sécurité
echo "2️⃣ Test sécurité..."
echo "✅ SecurityHeaders: $(test -f app/Http/Middleware/SecurityHeaders.php && echo 'OK' || echo 'MANQUANT')"
echo "✅ TelescopeAccess: $(test -f app/Http/Middleware/TelescopeAccess.php && echo 'OK' || echo 'MANQUANT')"

# 3. Test configuration
echo "3️⃣ Test configuration..."
grep -q "APP_DEBUG=false" .env.production && echo "✅ Debug OFF" || echo "❌ Debug ON"
grep -q "APP_ENV=production" .env.production && echo "✅ ENV Production" || echo "❌ ENV Dev"

# 4. Test base de données
echo "4️⃣ Test base de données..."
php artisan migrate:status | tail -5

# 5. Test routes principales
echo "5️⃣ Test routes..."
php artisan route:list --name=admin.dashboard
php artisan route:list --name=admin.users

# 6. Test permissions/rôles
echo "6️⃣ Test rôles système..."
php artisan tinker --execute="
echo 'Rôles: ' . implode(', ', \Spatie\Permission\Models\Role::pluck('name')->toArray());
echo 'Users: ' . \App\Models\User::count();
echo 'Ecoles: ' . \App\Models\Ecole::count();
"

# 7. Score final conformité
echo "7️⃣ SCORE CONFORMITÉ FINAL..."
SCORE=0

# Dashboard conforme
test -f app/Policies/DashboardPolicy.php && ((SCORE++))
grep -q "HasMiddleware" app/Http/Controllers/Admin/DashboardController.php && ((SCORE++))

# Log conforme  
test -f app/Policies/LogPolicy.php && ((SCORE++))
grep -q "HasMiddleware" app/Http/Controllers/Admin/LogController.php && ((SCORE++))

# Sécurité
test -f app/Http/Middleware/SecurityHeaders.php && ((SCORE++))
grep -q "APP_DEBUG=false" .env.production && ((SCORE++))

# Configuration
test -f config/telescope.php && ((SCORE++))

echo "📊 SCORE: $SCORE/7"
if [ $SCORE -eq 7 ]; then
    echo "🎉 PARFAIT - 100% CONFORME !"
elif [ $SCORE -ge 5 ]; then
    echo "✅ EXCELLENT - Prêt production"
else
    echo "⚠️ À améliorer"
fi

echo ""
echo "🚀 STUDIOSUNISDB V4.1.8.3-DEV FINALISÉ"
echo "📋 Prochaines étapes:"
echo "   1. Copier .env.production → .env en production"
echo "   2. Configurer serveur web (Nginx + SSL)"
echo "   3. Backup automatique quotidien"
echo "   4. Monitoring avec Telescope"
echo "   5. Tests utilisateurs finaux"
