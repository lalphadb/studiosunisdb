<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

try {
    // Vérifier si l'utilisateur existe déjà
    $existingUser = User::where('email', 'louis@4lb.ca')->first();
    
    if ($existingUser) {
        echo "Utilisateur louis@4lb.ca existe déjà avec le rôle: " . $existingUser->role . "\n";
        echo "Email: " . $existingUser->email . "\n";
        echo "Nom: " . $existingUser->name . "\n";
        
        // Mettre à jour le rôle vers superadmin si nécessaire
        if ($existingUser->role !== 'superadmin') {
            $existingUser->role = 'superadmin';
            $existingUser->save();
            echo "Rôle mis à jour vers: superadmin\n";
        }
    } else {
        // Créer le nouvel utilisateur
        $user = User::create([
            'name' => 'Louis SuperAdmin',
            'email' => 'louis@4lb.ca',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
            'role' => 'superadmin'
        ]);
        
        echo "✅ Utilisateur créé avec succès!\n";
        echo "Email: " . $user->email . "\n";
        echo "Nom: " . $user->name . "\n";
        echo "Rôle: " . $user->role . "\n";
        echo "Mot de passe: password123\n";
    }
    
    // Vérifier les permissions
    echo "\n=== VÉRIFICATION DES PERMISSIONS ===\n";
    $user = User::where('email', 'louis@4lb.ca')->first();
    echo "ID: " . $user->id . "\n";
    echo "Email: " . $user->email . "\n";
    echo "Rôle: " . $user->role . "\n";
    echo "Email vérifié: " . ($user->email_verified_at ? 'Oui' : 'Non') . "\n";
    echo "Créé le: " . $user->created_at . "\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
