<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Créer les rôles manquants
        $roles = ['super-admin', 'admin', 'gestionnaire', 'instructeur', 'membre'];
        
        foreach ($roles as $roleName) {
            if (!Role::where('name', $roleName)->exists()) {
                Role::create(['name' => $roleName]);
                echo "✅ Rôle {$roleName} créé\n";
            } else {
                echo "ℹ️ Rôle {$roleName} existe déjà\n";
            }
        }
        
        // Assigner le rôle admin à louis@4lb.ca
        $user = User::where('email', 'louis@4lb.ca')->first();
        if ($user && !$user->hasRole('admin')) {
            $user->assignRole('admin');
            echo "✅ Rôle admin assigné à louis@4lb.ca\n";
        }
        
        echo "🎉 Configuration des rôles terminée\n";
    }
}
