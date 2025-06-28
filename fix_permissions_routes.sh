#!/bin/bash
echo "🔧 CORRECTION PERMISSIONS ET ROUTES"
echo "==================================="

# 1. Vérifier et ajouter la route présences manquante
echo "📝 1. Ajout route présences dans admin.php..."
if ! grep -q "Route::resource('presences'" routes/admin.php; then
    echo "Route::resource('presences', App\\Http\\Controllers\\Admin\\PresenceController::class);" >> routes/admin.php
    echo "✅ Route présences ajoutée"
else
    echo "✅ Route présences déjà présente"
fi

# 2. Réinitialiser les permissions Spatie
echo ""
echo "🔐 2. Reset permissions Spatie..."
php artisan permission:cache-reset
php artisan permission:create-permission-tables 2>/dev/null || echo "Tables déjà créées"

# 3. Créer les permissions manquantes
echo ""
echo "📋 3. Création permissions de base..."
php artisan tinker --execute="
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

// Supprimer les permissions existantes pour éviter les doublons
Permission::query()->delete();
Role::query()->delete();

// Créer les rôles
\$superadmin = Role::create(['name' => 'superadmin']);
\$admin_ecole = Role::create(['name' => 'admin_ecole']); 
\$instructeur = Role::create(['name' => 'instructeur']);
\$membre = Role::create(['name' => 'membre']);

// Permissions pour chaque module
\$modules = ['users', 'ecoles', 'cours', 'seminaires', 'ceintures', 'paiements', 'presences'];
\$actions = ['viewAny', 'view', 'create', 'update', 'delete'];

foreach(\$modules as \$module) {
    foreach(\$actions as \$action) {
        Permission::create(['name' => \$action . ',App\\\\Models\\\\' . ucfirst(rtrim(\$module, 's'))]);
    }
}

// Assigner toutes les permissions au superadmin
\$superadmin->givePermissionTo(Permission::all());

// Assigner permissions limitées aux autres rôles
\$adminPermissions = Permission::whereIn('name', [
    'viewAny,App\\\\Models\\\\User',
    'view,App\\\\Models\\\\User', 
    'create,App\\\\Models\\\\User',
    'update,App\\\\Models\\\\User',
    'viewAny,App\\\\Models\\\\Cour',
    'create,App\\\\Models\\\\Cour',
    'update,App\\\\Models\\\\Cour',
    'viewAny,App\\\\Models\\\\Seminaire',
    'create,App\\\\Models\\\\Seminaire',
    'update,App\\\\Models\\\\Seminaire',
    'viewAny,App\\\\Models\\\\Paiement',
    'create,App\\\\Models\\\\Paiement',
    'viewAny,App\\\\Models\\\\Presence',
    'create,App\\\\Models\\\\Presence'
])->get();

\$admin_ecole->givePermissionTo(\$adminPermissions);

echo 'Permissions créées avec succès!';
"

# 4. Assigner les rôles aux utilisateurs de test
echo ""
echo "👤 4. Attribution rôles utilisateurs test..."
php artisan tinker --execute="
use App\Models\User;
use Spatie\Permission\Models\Role;

// Assigner superadmin à lalpha@4lb.ca
\$superuser = User::where('email', 'lalpha@4lb.ca')->first();
if(\$superuser) {
    \$superuser->assignRole('superadmin');
    echo 'Rôle superadmin assigné à lalpha@4lb.ca';
}

// Assigner admin_ecole à louis@4lb.ca  
\$adminuser = User::where('email', 'louis@4lb.ca')->first();
if(\$adminuser) {
    \$adminuser->assignRole('admin_ecole');
    echo 'Rôle admin_ecole assigné à louis@4lb.ca';
}
"

# 5. Nettoyer tous les caches
echo ""
echo "🧹 5. Nettoyage complet cache..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
php artisan permission:cache-reset

# 6. Vérifier les routes
echo ""
echo "📋 6. Vérification routes admin..."
php artisan route:list --name=admin --columns=method,uri,name | grep -E "(presences|paiements|seminaires)"

echo ""
echo "✅ CORRECTION PERMISSIONS TERMINÉE!"
echo "Testez maintenant: http://127.0.0.1:8001/admin/presences"
