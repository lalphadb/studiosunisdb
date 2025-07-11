#!/bin/bash

echo "=== DIAGNOSTIC ET CORRECTION DU PROBLÈME DE CONNEXION ==="
echo

# 1. Vérifier les utilisateurs existants
echo "📋 Utilisateurs dans la base de données :"
php artisan tinker << 'TINKER'
echo "\n=== UTILISATEURS EXISTANTS ===\n";
$users = App\Models\User::with('roles')->get();
foreach($users as $user) {
    echo "Email: " . $user->email;
    echo " | Nom: " . $user->nom_complet;
    echo " | Rôles: " . $user->roles->pluck('name')->implode(', ');
    echo " | Actif: " . ($user->actif ? 'Oui' : 'Non');
    echo " | École: " . ($user->ecole ? $user->ecole->nom : 'Aucune');
    echo "\n";
}
echo "\nTotal: " . $users->count() . " utilisateurs\n";
exit
TINKER

# 2. Créer ou réinitialiser un super-admin
echo -e "\n🔧 Création/Réinitialisation d'un super-admin..."
php artisan tinker << 'TINKER'
use App\Models\User;
use App\Models\Ecole;
use Illuminate\Support\Facades\Hash;

// Trouver ou créer l'école principale
$ecole = Ecole::first();
if (!$ecole) {
    echo "Création de l'école principale...\n";
    $ecole = Ecole::create([
        'nom' => 'StudiosUnis Siège Social',
        'code' => 'SIEGE',
        'adresse' => '123 Rue Principale',
        'ville' => 'Montréal',
        'code_postal' => 'H1A 1A1',
        'telephone' => '514-555-0001',
        'email' => 'info@studiosdb.com',
        'actif' => true
    ]);
}

// Créer ou mettre à jour le super-admin
$email = 'admin@studiosdb.com';
$user = User::where('email', $email)->first();

if ($user) {
    echo "Mise à jour du super-admin existant...\n";
    $user->update([
        'password' => Hash::make('password'),
        'actif' => true
    ]);
} else {
    echo "Création d'un nouveau super-admin...\n";
    $user = User::create([
        'nom' => 'Admin',
        'prenom' => 'Super',
        'email' => $email,
        'password' => Hash::make('password'),
        'ecole_id' => $ecole->id,
        'actif' => true,
        'email_verified_at' => now(),
        'code_utilisateur' => 'U000001'
    ]);
}

// Assigner le rôle super-admin
if (!$user->hasRole('super-admin')) {
    // Créer le rôle s'il n'existe pas
    $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'super-admin']);
    $user->assignRole('super-admin');
    echo "Rôle super-admin assigné.\n";
}

echo "\n✅ Super-admin créé/mis à jour avec succès!\n";
echo "Email: " . $email . "\n";
echo "Mot de passe: password\n";
exit
TINKER

# 3. Créer d'autres utilisateurs de test
echo -e "\n🔧 Création d'utilisateurs de test supplémentaires..."
php artisan tinker << 'TINKER'
use App\Models\User;
use App\Models\Ecole;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

$ecole = Ecole::first();

// Créer les rôles s'ils n'existent pas
$roles = ['super-admin', 'admin', 'gestionnaire', 'instructeur', 'membre'];
foreach ($roles as $roleName) {
    Role::firstOrCreate(['name' => $roleName]);
}

// Créer un admin d'école
$admin = User::firstOrCreate(
    ['email' => 'admin.ecole@studiosdb.com'],
    [
        'nom' => 'Administrateur',
        'prenom' => 'École',
        'password' => Hash::make('password'),
        'ecole_id' => $ecole->id,
        'actif' => true,
        'email_verified_at' => now(),
        'code_utilisateur' => 'U000002'
    ]
);
$admin->assignRole('admin');

// Créer un instructeur
$instructeur = User::firstOrCreate(
    ['email' => 'instructeur@studiosdb.com'],
    [
        'nom' => 'Instructeur',
        'prenom' => 'Test',
        'password' => Hash::make('password'),
        'ecole_id' => $ecole->id,
        'actif' => true,
        'email_verified_at' => now(),
        'code_utilisateur' => 'U000003'
    ]
);
$instructeur->assignRole('instructeur');

// Créer un membre
$membre = User::firstOrCreate(
    ['email' => 'membre@studiosdb.com'],
    [
        'nom' => 'Membre',
        'prenom' => 'Test',
        'password' => Hash::make('password'),
        'ecole_id' => $ecole->id,
        'actif' => true,
        'email_verified_at' => now(),
        'code_utilisateur' => 'U000004'
    ]
);
$membre->assignRole('membre');

echo "\n✅ Utilisateurs de test créés!\n";
exit
TINKER

# 4. Vérifier la configuration de l'authentification
echo -e "\n🔍 Vérification de la configuration..."

# Vérifier que les routes de login existent
echo "Routes d'authentification :"
php artisan route:list | grep -E "(login|logout)" | head -5

# 5. Tester la connexion
echo -e "\n📝 Récapitulatif des comptes disponibles :"
echo "============================================"
echo "Email: admin@studiosdb.com"
echo "Mot de passe: password"
echo "Rôle: Super Admin"
echo ""
echo "Email: admin.ecole@studiosdb.com"
echo "Mot de passe: password"
echo "Rôle: Admin École"
echo ""
echo "Email: instructeur@studiosdb.com"
echo "Mot de passe: password"
echo "Rôle: Instructeur"
echo ""
echo "Email: membre@studiosdb.com"
echo "Mot de passe: password"
echo "Rôle: Membre"
echo "============================================"

# 6. Vérifier Fortify
echo -e "\n🔐 Configuration Fortify..."
if [ -f "config/fortify.php" ]; then
    echo "✓ Fortify est configuré"
    
    # Vérifier que les features sont activées
    echo "Vérification des features Fortify..."
    php -r "
    \$config = require 'config/fortify.php';
    echo 'Username: ' . \$config['username'] . PHP_EOL;
    echo 'Email verification: ' . (in_array('emailVerification', \$config['features']) ? 'Activé' : 'Désactivé') . PHP_EOL;
    "
else
    echo "⚠️  Fortify n'est pas configuré - Configuration en cours..."
    php artisan vendor:publish --provider="Laravel\Fortify\FortifyServiceProvider"
fi

# 7. Clear les caches
echo -e "\n🧹 Nettoyage des caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

echo -e "\n✅ Diagnostic terminé!"
echo
echo "🚀 Pour tester la connexion :"
echo "1. php artisan serve"
echo "2. Aller sur http://localhost:8000/login"
echo "3. Utiliser: admin@studiosdb.com / password"
echo
echo "⚠️  Si le problème persiste, vérifiez :"
echo "- Que le serveur est bien démarré"
echo "- Que vous utilisez le bon email/mot de passe"
echo "- Les logs dans storage/logs/laravel.log"
