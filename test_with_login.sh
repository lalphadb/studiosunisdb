#!/bin/bash
echo "🔑 TEST AVEC AUTHENTIFICATION"
echo "============================"

# 1. Vérifier que les utilisateurs de test existent
echo "📋 1. Vérification utilisateurs test..."
php artisan tinker --execute="
\$users = App\Models\User::whereIn('email', ['louis@4lb.ca', 'lalpha@4lb.ca'])->get(['email', 'name']);
foreach(\$users as \$user) {
    echo 'Email: ' . \$user->email . ' - Nom: ' . \$user->name . PHP_EOL;
}
if(\$users->count() == 0) {
    echo 'AUCUN utilisateur de test trouvé!' . PHP_EOL;
}
"

# 2. Créer les utilisateurs de test s'ils n'existent pas
echo ""
echo "👤 2. Création utilisateurs test si nécessaire..."
php artisan tinker --execute="
use App\Models\User;
use App\Models\Ecole;
use Illuminate\Support\Facades\Hash;

// Créer l'école de test si elle n'existe pas
\$ecole = Ecole::firstOrCreate(
    ['code' => 'TEST001'],
    [
        'nom' => 'École Test Karaté',
        'adresse' => '123 Rue Test',
        'ville' => 'Montmagny',
        'province' => 'QC',
        'code_postal' => 'G5V 1A1',
        'telephone' => '418-555-0001',
        'email' => 'test@ecole.ca',
        'active' => true
    ]
);

// Créer/mettre à jour l'utilisateur superadmin
\$superuser = User::firstOrCreate(
    ['email' => 'lalpha@4lb.ca'],
    [
        'name' => 'Super Admin',
        'password' => Hash::make('password123'),
        'ecole_id' => null,
        'active' => true,
        'email_verified_at' => now()
    ]
);

// Créer/mettre à jour l'utilisateur admin école
\$adminuser = User::firstOrCreate(
    ['email' => 'louis@4lb.ca'],
    [
        'name' => 'Louis Admin École',
        'password' => Hash::make('password123'),
        'ecole_id' => \$ecole->id,
        'active' => true,
        'email_verified_at' => now()
    ]
);

echo 'Utilisateurs de test créés/mis à jour avec succès!' . PHP_EOL;
echo 'École ID: ' . \$ecole->id . PHP_EOL;
echo 'SuperAdmin: ' . \$superuser->email . PHP_EOL;
echo 'Admin École: ' . \$adminuser->email . ' (école_id: ' . \$adminuser->ecole_id . ')' . PHP_EOL;
"

# 3. Assigner les rôles
echo ""
echo "🎭 3. Attribution des rôles..."
php artisan tinker --execute="
use App\Models\User;
use Spatie\Permission\Models\Role;

// Créer les rôles s'ils n'existent pas
\$superadmin = Role::firstOrCreate(['name' => 'superadmin']);
\$admin_ecole = Role::firstOrCreate(['name' => 'admin_ecole']);

// Assigner les rôles
\$superuser = User::where('email', 'lalpha@4lb.ca')->first();
if(\$superuser) {
    \$superuser->syncRoles(['superadmin']);
    echo 'Rôle superadmin assigné à ' . \$superuser->email . PHP_EOL;
}

\$adminuser = User::where('email', 'louis@4lb.ca')->first();
if(\$adminuser) {
    \$adminuser->syncRoles(['admin_ecole']);
    echo 'Rôle admin_ecole assigné à ' . \$adminuser->email . PHP_EOL;
}
"

echo ""
echo "✅ CONFIGURATION TERMINÉE!"
echo ""
echo "🌐 MAINTENANT TESTEZ DANS LE NAVIGATEUR:"
echo "1. Allez sur: http://127.0.0.1:8001/login"
echo "2. Connectez-vous avec:"
echo "   Email: lalpha@4lb.ca"
echo "   Password: password123"
echo "3. Ou avec:"
echo "   Email: louis@4lb.ca" 
echo "   Password: password123"
echo "4. Puis allez sur: http://127.0.0.1:8001/admin/users"
echo ""
echo "🎯 Vous devriez voir la page des utilisateurs sans erreur!"
