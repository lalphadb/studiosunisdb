#!/bin/bash

echo "🚀 RÉPARATION DASHBOARD ULTRA PRO v5..."

cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro

# 1. Nettoyage cache complet
echo "🧹 Nettoyage cache..."
php artisan optimize:clear

# 2. Créer les rôles si nécessaire
echo "👑 Vérification rôles..."
php artisan tinker --execute="
\$roles = ['super-admin', 'admin', 'gestionnaire', 'instructeur', 'membre'];
foreach (\$roles as \$r) {
    if (!\Spatie\Permission\Models\Role::where('name', \$r)->exists()) {
        \Spatie\Permission\Models\Role::create(['name' => \$r]);
        echo \"✅ Rôle \$r créé\n\";
    }
}
\$u = \App\Models\User::where('email', 'louis@4lb.ca')->first();
if (\$u && !\$u->hasRole('admin')) {
    \$u->assignRole('admin');
    echo \"✅ Admin assigné\n\";
}
echo \"Rôles OK\n\";
"

# 3. Migration
echo "🗄️ Migrations..."
php artisan migrate --force

# 4. Build frontend
echo "🎨 Build frontend..."
npm run build

# 5. Cache optimisé
echo "⚡ Optimisation..."
php artisan optimize

echo ""
echo "✅ DASHBOARD ULTRA PRO RÉPARÉ!"
echo "🔗 Test maintenant: http://studiosdb.local/dashboard"
echo "🎯 Interface ultra-moderne avec glassmorphism"
echo "📊 Barres de progression animées"
echo "🚀 Navigation fluide vers modules"
