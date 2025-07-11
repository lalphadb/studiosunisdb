<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Ecole;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class CreateLoginUsersSeeder extends Seeder
{
    public function run()
    {
        // Créer une école par défaut si elle n'existe pas
        $ecole = Ecole::firstOrCreate([
            'nom' => 'École Administrative'
        ], [
            'adresse' => '123 Rue Admin',
            'ville' => 'Montréal',
            'province' => 'QC',
            'code_postal' => 'H1H 1H1',
            'telephone' => '514-555-0001',
            'email' => 'admin@ecole.ca',
            'actif' => true
        ]);

        // Créer les rôles s'ils n'existent pas
        $roles = ['superadmin', 'admin_ecole', 'instructeur', 'membre', 'parent'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // Utilisateurs à créer/corriger
        $users = [
            [
                'name' => 'Louis Alpha',
                'email' => 'lalpha@4lb.ca',
                'password' => 'password123',
                'role' => 'superadmin'
            ],
            [
                'name' => 'Louis Admin',
                'email' => 'louis@4lb.ca', 
                'password' => 'password123',
                'role' => 'admin_ecole'
            ]
        ];

        foreach ($users as $userData) {
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make($userData['password']),
                    'email_verified_at' => now(),
                    'ecole_id' => $ecole->id
                ]
            );

            // Assigner le rôle
            if (!$user->hasRole($userData['role'])) {
                $user->assignRole($userData['role']);
            }

            echo "✅ Utilisateur créé/mis à jour: {$user->email} (Rôle: {$userData['role']})\n";
            echo "   Mot de passe: {$userData['password']}\n";
        }

        echo "\n=== UTILISATEURS CRÉÉS/CORRIGÉS ===\n";
        echo "lalpha@4lb.ca / password123 (superadmin)\n";
        echo "louis@4lb.ca / password123 (admin_ecole)\n";
    }
}
