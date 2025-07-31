#!/bin/bash

# Exécution simple et rapide
cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro

echo "🚀 RÉPARATION DASHBOARD ULTRA PRO..."

# Cache clear
php artisan optimize:clear

# Rôles
php artisan tinker --execute="
\$roles = ['super-admin', 'admin', 'gestionnaire', 'instructeur', 'membre'];
foreach (\$roles as \$r) {
    if (!\Spatie\Permission\Models\Role::where('name', \$r)->exists()) {
        \Spatie\Permission\Models\Role::create(['name' => \$r]);
    }
}
\$u = \App\Models\User::where('email', 'louis@4lb.ca')->first();
if (\$u && !\$u->hasRole('admin')) \$u->assignRole('admin');
echo 'Rôles OK\n';
"

# Migration
php artisan migrate --force

# Assets
npm run build 2>/dev/null || echo "Build frontend..."

# Cache
php artisan optimize

echo "✅ RÉPARÉ! Test: http://studiosdb.local/dashboard"
