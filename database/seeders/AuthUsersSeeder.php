<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{User, Ecole};
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AuthUsersSeeder extends Seeder
{
    public function run(): void
    {
        // Créer les rôles s'ils n'existent pas
        $roles = ['superadmin', 'admin_ecole', 'professeur', 'membre'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        // SuperAdmin (lalpha@4lb.ca)
        $superadmin = User::updateOrCreate(
            ['email' => 'lalpha@4lb.ca'],
            [
                'name' => 'Louis-Philippe Alpha',
                'email' => 'lalpha@4lb.ca',
                'password' => Hash::make('StudiosDB2025!'),
                'ecole_id' => null, // SuperAdmin n'est lié à aucune école
                'phone' => '418-123-4567',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $superadmin->assignRole('superadmin');

        // Admin École (louis@4lb.ca) - École St-Émile
        $ecoleStEmile = Ecole::where('nom', 'École St-Émile')->first();
        if ($ecoleStEmile) {
            $adminEcole = User::updateOrCreate(
                ['email' => 'louis@4lb.ca'],
                [
                    'name' => 'Louis Dubois',
                    'email' => 'louis@4lb.ca',
                    'password' => Hash::make('StudiosDB2025!'),
                    'ecole_id' => $ecoleStEmile->id,
                    'phone' => '418-234-5678',
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );
            $adminEcole->assignRole('admin_ecole');
        }

        // Créer quelques utilisateurs test supplémentaires
        $ecoles = Ecole::limit(5)->get();
        foreach ($ecoles as $index => $ecole) {
            // Professeur
            $professeur = User::updateOrCreate(
                ['email' => "professeur{$index}@studiosdb.test"],
                [
                    'name' => "Professeur École {$ecole->nom}",
                    'email' => "professeur{$index}@studiosdb.test",
                    'password' => Hash::make('password123'),
                    'ecole_id' => $ecole->id,
                    'phone' => '418-' . str_pad($index, 3, '0', STR_PAD_LEFT) . '-0000',
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );
            $professeur->assignRole('professeur');

            // Quelques membres
            for ($i = 1; $i <= 3; $i++) {
                $membre = User::updateOrCreate(
                    ['email' => "membre{$index}-{$i}@studiosdb.test"],
                    [
                        'name' => "Membre {$i} - {$ecole->nom}",
                        'email' => "membre{$index}-{$i}@studiosdb.test",
                        'password' => Hash::make('password123'),
                        'ecole_id' => $ecole->id,
                        'phone' => '418-' . str_pad($index, 3, '0', STR_PAD_LEFT) . '-000' . $i,
                        'is_active' => true,
                        'email_verified_at' => now(),
                    ]
                );
                $membre->assignRole('membre');
            }
        }
    }
}
