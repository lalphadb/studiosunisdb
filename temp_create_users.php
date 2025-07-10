<?php
// Script temporaire pour créer les utilisateurs admin

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\{User, Ecole};
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

echo "🔧 Création des utilisateurs admin...\n";

try {
    // Créer les rôles si nécessaire
    $roles = ['superadmin', 'admin_ecole', 'professeur', 'membre'];
    foreach ($roles as $roleName) {
        Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
    }
    echo "✅ Rôles créés/vérifiés\n";

    // SuperAdmin (lalpha@4lb.ca)
    $superadmin = User::updateOrCreate(
        ['email' => 'lalpha@4lb.ca'],
        [
            'name' => 'Louis-Philippe Alpha',
            'email' => 'lalpha@4lb.ca',
            'password' => Hash::make('StudiosDB2025!'),
            'ecole_id' => null,
            'phone' => '418-123-4567',
            'is_active' => true,
            'email_verified_at' => now(),
        ]
    );
    
    // Assigner le rôle SuperAdmin
    $superadmin->syncRoles(['superadmin']);
    echo "✅ SuperAdmin créé/mis à jour: lalpha@4lb.ca\n";
    echo "   Password: StudiosDB2025!\n";
    echo "   Rôles: " . implode(', ', $superadmin->roles->pluck('name')->toArray()) . "\n";

    // Admin École (louis@4lb.ca)
    $ecole = Ecole::first();
    if ($ecole) {
        $admin = User::updateOrCreate(
            ['email' => 'louis@4lb.ca'],
            [
                'name' => 'Louis Dubois',
                'email' => 'louis@4lb.ca',
                'password' => Hash::make('StudiosDB2025!'),
                'ecole_id' => $ecole->id,
                'phone' => '418-234-5678',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        
        // Assigner le rôle Admin École
        $admin->syncRoles(['admin_ecole']);
        echo "✅ Admin École créé/mis à jour: louis@4lb.ca\n";
        echo "   Password: StudiosDB2025!\n";
        echo "   École: " . $ecole->nom . " (ID: " . $ecole->id . ")\n";
        echo "   Rôles: " . implode(', ', $admin->roles->pluck('name')->toArray()) . "\n";
    } else {
        echo "❌ Aucune école disponible pour l'admin\n";
    }

    // Créer un utilisateur membre de test
    if ($ecole) {
        $membre = User::updateOrCreate(
            ['email' => 'membre@test.ca'],
            [
                'name' => 'Membre Test',
                'email' => 'membre@test.ca',
                'password' => Hash::make('password123'),
                'ecole_id' => $ecole->id,
                'phone' => '418-345-6789',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        
        $membre->syncRoles(['membre']);
        echo "✅ Membre test créé: membre@test.ca / password123\n";
    }

    echo "\n🎉 Tous les utilisateurs ont été créés avec succès!\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    exit(1);
}
