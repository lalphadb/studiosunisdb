<?php
// Fix complet authentification et navigation StudiosDB
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

echo "\n=== FIX AUTHENTIFICATION & NAVIGATION STUDIOSDB ===\n\n";

// 1. Créer les tables de permissions si nécessaire
echo "1. VÉRIFICATION DES TABLES SPATIE:\n";
try {
    // Créer les rôles
    $roles = ['superadmin', 'admin_ecole', 'instructeur', 'membre'];
    foreach ($roles as $roleName) {
        $role = Role::firstOrCreate(
            ['name' => $roleName],
            ['guard_name' => 'web']
        );
        echo "   ✓ Rôle '{$roleName}' créé/vérifié\n";
    }
} catch (Exception $e) {
    echo "   ⚠️ Erreur Spatie: " . $e->getMessage() . "\n";
    echo "   Exécution de: php artisan migrate\n";
    Artisan::call('migrate');
}

// 2. Créer l'utilisateur admin
echo "\n2. CRÉATION UTILISATEUR ADMIN:\n";
$adminEmail = 'admin@studiosdb.ca';
$adminPassword = 'AdminStudios2025!';

// Supprimer l'ancien si existe
User::where('email', $adminEmail)->delete();

// Créer le nouveau
$user = User::create([
    'name' => 'Louis Admin',
    'email' => $adminEmail,
    'password' => Hash::make($adminPassword),
    'email_verified_at' => now(),
]);

// Assigner le rôle
try {
    $user->assignRole('admin_ecole');
    echo "   ✓ Utilisateur admin créé avec rôle admin_ecole\n";
} catch (Exception $e) {
    echo "   ⚠️ Erreur assignation rôle: " . $e->getMessage() . "\n";
}

echo "   Email: {$adminEmail}\n";
echo "   Mot de passe: {$adminPassword}\n";

// 3. Vérifier la configuration
echo "\n3. VÉRIFICATION CONFIGURATION:\n";
echo "   APP_URL: " . config('app.url') . "\n";
echo "   Session driver: " . config('session.driver') . "\n";
echo "   Session domain: " . config('session.domain') . "\n";
echo "   Session same_site: " . config('session.same_site') . "\n";

// 4. Nettoyer les caches
echo "\n4. NETTOYAGE DES CACHES:\n";
Artisan::call('optimize:clear');
echo "   ✓ Cache vidé\n";

// 5. Créer des données de test
echo "\n5. CRÉATION DONNÉES DE TEST:\n";

// Créer quelques membres si la table existe
try {
    if (Schema::hasTable('membres')) {
        DB::table('membres')->truncate();
        
        $membres = [
            ['prenom' => 'Jean', 'nom' => 'Dupont', 'statut' => 'actif'],
            ['prenom' => 'Marie', 'nom' => 'Tremblay', 'statut' => 'actif'],
            ['prenom' => 'Pierre', 'nom' => 'Gagnon', 'statut' => 'actif'],
        ];
        
        foreach ($membres as $membre) {
            DB::table('membres')->insert([
                'prenom' => $membre['prenom'],
                'nom' => $membre['nom'],
                'statut' => $membre['statut'],
                'date_naissance' => '2010-01-01',
                'date_inscription' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        echo "   ✓ 3 membres de test créés\n";
    }
} catch (Exception $e) {
    echo "   ⚠️ " . $e->getMessage() . "\n";
}

// 6. Régénérer les caches
echo "\n6. RÉGÉNÉRATION DES CACHES:\n";
Artisan::call('config:cache');
Artisan::call('route:cache');
Artisan::call('view:cache');
echo "   ✓ Caches régénérés\n";

// 7. Instructions finales
echo "\n=== INSTRUCTIONS POUR TESTER ===\n\n";
echo "1. ARRÊTER tous les serveurs (Ctrl+C)\n";
echo "2. DÉMARRER le serveur Laravel:\n";
echo "   php artisan serve --host=localhost --port=8000\n\n";
echo "3. DANS UN AUTRE TERMINAL, démarrer Vite:\n";
echo "   npm run dev\n\n";
echo "4. OUVRIR le navigateur:\n";
echo "   http://localhost:8000/login\n\n";
echo "5. SE CONNECTER avec:\n";
echo "   Email: {$adminEmail}\n";
echo "   Mot de passe: {$adminPassword}\n\n";
echo "6. APRÈS CONNEXION, les liens fonctionneront:\n";
echo "   - Dashboard\n";
echo "   - Membres\n";
echo "   - Cours\n";
echo "   - Présences\n";
echo "   - Paiements\n\n";

echo "=== IMPORTANT ===\n";
echo "Si l'erreur persiste:\n";
echo "1. Vider les cookies du navigateur\n";
echo "2. Utiliser une fenêtre de navigation privée\n";
echo "3. Vérifier que l'URL est bien http://localhost:8000 (pas 127.0.0.1)\n\n";

echo "=== FIN DU FIX ===\n";
