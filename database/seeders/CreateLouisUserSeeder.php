<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Ecole;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class CreateLouisUserSeeder extends Seeder
{
    public function run()
    {
        echo "=== CRÉATION DE L'UTILISATEUR LOUIS@4LB.CA ===\n";

        // 1. Récupérer ou créer l'école
        $ecole = Ecole::first();
        if (!$ecole) {
            // Créer une école simple sans la colonne actif si elle pose problème
            $ecole = new Ecole();
            $ecole->nom = 'École Administrative';
            $ecole->adresse = '123 Rue Admin';
            $ecole->ville = 'Montréal';
            $ecole->province = 'QC';
            $ecole->code_postal = 'H1H 1H1';
            $ecole->telephone = '514-555-0001';
            $ecole->email = 'admin@ecole.ca';
            
            // Ajouter actif seulement si la colonne existe
            try {
                $ecole->actif = true;
            } catch (\Exception $e) {
                // Ignorer si la colonne n'existe pas
            }
            
            $ecole->save();
            echo "✅ École créée: {$ecole->nom} (ID: {$ecole->id})\n";
        } else {
            echo "✅ École existante utilisée: {$ecole->nom} (ID: {$ecole->id})\n";
        }

        // 2. Vérifier que le rôle admin_ecole existe
        $role = Role::where('name', 'admin_ecole')->first();
        if (!$role) {
            $role = Role::create(['name' => 'admin_ecole']);
            echo "✅ Rôle 'admin_ecole' créé\n";
        } else {
            echo "✅ Rôle 'admin_ecole' existe\n";
        }

        // 3. Supprimer l'utilisateur existant s'il existe
        $existingUser = User::where('email', 'louis@4lb.ca')->first();
        if ($existingUser) {
            $existingUser->delete();
            echo "🗑️  Ancien utilisateur louis@4lb.ca supprimé\n";
        }

        // 4. Créer le nouvel utilisateur
        $user = User::create([
            'name' => 'Louis Admin École',
            'email' => 'louis@4lb.ca',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'ecole_id' => $ecole->id
        ]);

        // 5. Assigner le rôle
        $user->assignRole('admin_ecole');

        echo "✅ Utilisateur créé avec succès:\n";
        echo "   Nom: {$user->name}\n";
        echo "   Email: {$user->email}\n";
        echo "   Mot de passe: password123\n";
        echo "   École ID: {$user->ecole_id}\n";
        echo "   Rôle: admin_ecole\n";

        // 6. Vérification finale
        $user->refresh();
        echo "\n=== VÉRIFICATION ===\n";
        echo "ID: {$user->id}\n";
        echo "Rôles: " . $user->getRoleNames()->implode(', ') . "\n";
        echo "Permissions: " . $user->getAllPermissions()->pluck('name')->count() . " permissions\n";
    }
}
