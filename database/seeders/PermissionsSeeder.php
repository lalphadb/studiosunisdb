<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class PermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Créer toutes les permissions
        $permissions = [
            // Écoles
            'view-ecoles',
            'create-ecole',
            'edit-ecole',
            'delete-ecole',
            
            // Users
            'view-users',
            'create-user',
            'edit-user',
            'delete-user',
            
            // Cours
            'view-cours',
            'create-cours',
            'edit-cours',
            'delete-cours',
            
            // Ceintures
            'view-ceintures',
            'create-ceinture',
            'edit-ceinture',
            'delete-ceinture',
            
            // Séminaires
            'view-seminaires',
            'create-seminaire',
            'edit-seminaire',
            'delete-seminaire',
            
            // Paiements
            'view-paiements',
            'create-paiement',
            'edit-paiement',
            'delete-paiement',
            
            // Présences
            'view-presences',
            'create-presence',
            'edit-presence',
            'delete-presence',
            
            // Inscriptions
            'view-inscriptions',
            'create-inscription',
            'edit-inscription',
            'delete-inscription',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Créer ou récupérer le rôle super-admin
        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin']);
        
        // Assigner TOUTES les permissions au super-admin
        $superAdminRole->syncPermissions($permissions);

        // Assigner toutes les permissions directement à l'utilisateur lalpha@4lb.ca
        $superAdminUser = User::where('email', 'lalpha@4lb.ca')->first();
        
        if ($superAdminUser) {
            // Assigner le rôle
            $superAdminUser->assignRole('super-admin');
            
            // Assigner TOUTES les permissions directement
            $superAdminUser->givePermissionTo($permissions);
            
            echo "✅ Toutes les permissions assignées à {$superAdminUser->email}\n";
        } else {
            echo "❌ Utilisateur lalpha@4lb.ca non trouvé\n";
        }
    }
}
