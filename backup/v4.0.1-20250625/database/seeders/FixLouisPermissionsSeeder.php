<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Ecole;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class FixLouisPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Trouver ou créer l'école Saint-Émile
        $ecole = Ecole::firstOrCreate(
            ['nom' => 'Club de Karaté Saint-Émile'],
            [
                'code' => 'STE',
                'adresse' => '1234 rue Principale',
                'ville' => 'Saint-Émile',
                'province' => 'QC',
                'code_postal' => 'G0A 4E0',
                'telephone' => '418-XXX-XXXX',
                'email' => 'stemile@studiosunis.com',
                'active' => true,
            ]
        );

        // 2. Créer/mettre à jour Louis
        $user = User::updateOrCreate(
            ['email' => 'louis@4lb.ca'],
            [
                'name' => 'Louis Admin Saint-Émile',
                'password' => Hash::make('StEmile2025!'),
                'ecole_id' => $ecole->id,
                'active' => true,
                'email_verified_at' => now(),
            ]
        );

        // 3. Créer le rôle admin_ecole s'il n'existe pas
        $adminEcoleRole = Role::firstOrCreate(['name' => 'admin_ecole']);

        // 4. PERMISSIONS COMPLÈTES pour admin_ecole
        $permissions = [
            // Users (membres)
            'viewAny-users', 'view-users', 'create-users', 'update-users', 'delete-users',
            
            // Écoles  
            'viewAny-ecoles', 'view-ecoles', 'update-ecoles',
            
            // Cours
            'viewAny-cours', 'view-cours', 'create-cours', 'update-cours', 'delete-cours',
            
            // Ceintures
            'viewAny-ceintures', 'view-ceintures', 'create-ceintures', 'update-ceintures', 'delete-ceintures',
            
            // Présences
            'viewAny-presences', 'view-presences', 'create-presences', 'update-presences', 'delete-presences',
            
            // Séminaires
            'viewAny-seminaires', 'view-seminaires', 'create-seminaires', 'update-seminaires', 'delete-seminaires',
            
            // Paiements
            'viewAny-paiements', 'view-paiements', 'create-paiements', 'update-paiements', 'delete-paiements',
        ];

        // 5. Créer les permissions et les assigner au rôle
        foreach ($permissions as $permission) {
            $perm = Permission::firstOrCreate(['name' => $permission]);
            $adminEcoleRole->givePermissionTo($perm);
        }

        // 6. Assigner le rôle à Louis
        $user->syncRoles(['admin_ecole']);

        $this->command->info("✅ Louis@4lb.ca configuré avec TOUTES les permissions admin_ecole");
        $this->command->info("🏫 École: {$ecole->nom} (ID: {$ecole->id})");
        $this->command->info("🔑 Permissions assignées: " . count($permissions));
    }
}
