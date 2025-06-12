<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Ecole;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class FinalUsersSeeder extends Seeder
{
    public function run()
    {
        // Créer toutes les permissions
        $permissions = [
            'manage-all', 'view-dashboard', 'access-admin',
            'manage-ecoles', 'create-ecole', 'edit-ecole', 'delete-ecole', 'view-ecoles',
            'manage-membres', 'create-membre', 'edit-membre', 'delete-membre', 'view-membres',
            'manage-cours', 'create-cours', 'edit-cours', 'delete-cours', 'view-cours',
            'manage-presences', 'take-presences', 'edit-presences', 'view-presences',
            'manage-ceintures', 'evaluate-ceintures', 'view-progressions',
            'manage-finances', 'view-paiements', 'create-paiement', 'edit-paiement',
            'view-reports', 'generate-reports', 'view-analytics', 'export-data',
            'manage-users', 'manage-roles', 'view-logs', 'manage-settings'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Créer les rôles
        $superAdminRole = Role::firstOrCreate(['name' => 'superadmin']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $instructeurRole = Role::firstOrCreate(['name' => 'instructeur']);
        $membreRole = Role::firstOrCreate(['name' => 'membre']);

        // Permissions SuperAdmin (toutes)
        $superAdminRole->syncPermissions(Permission::all());

        // Permissions Admin École
        $adminPermissions = [
            'view-dashboard', 'access-admin',
            'manage-membres', 'create-membre', 'edit-membre', 'delete-membre', 'view-membres',
            'manage-cours', 'create-cours', 'edit-cours', 'delete-cours', 'view-cours',
            'manage-presences', 'take-presences', 'edit-presences', 'view-presences',
            'manage-ceintures', 'evaluate-ceintures', 'view-progressions',
            'manage-finances', 'view-paiements', 'create-paiement', 'edit-paiement',
            'view-reports', 'generate-reports', 'view-analytics', 'export-data'
        ];
        $adminRole->syncPermissions($adminPermissions);

        // 1. LOUIS = SUPERADMIN (toutes les écoles)
        $louis = User::updateOrCreate(
            ['email' => 'louis@4lb.ca'],
            [
                'name' => 'Louis SuperAdmin',
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
                'ecole_id' => null // NULL = SuperAdmin
            ]
        );
        $louis->syncRoles(['superadmin']);

        // 2. TROUVER L'ÉCOLE ST-ÉMILE
        $ecoleStEmile = Ecole::where('nom', 'like', '%mile%')->first();
        
        if (!$ecoleStEmile) {
            // Créer l'école St-Émile si elle n'existe pas
            $ecoleStEmile = Ecole::create([
                'nom' => 'Studios Unis St-Émile',
                'adresse' => '1234 Rue Principale',
                'ville' => 'St-Émile',
                'province' => 'Quebec',
                'code_postal' => 'G0A 4E0',
                'telephone' => '418-123-4567',
                'email' => 'stemile@studiosunisdb.com',
                'directeur' => 'Lalpha Directeur',
                'capacite_max' => 150,
                'statut' => 'actif'
            ]);
        }

        // 3. LALPHA = ADMIN ÉCOLE ST-ÉMILE
        $lalpha = User::updateOrCreate(
            ['email' => 'lalpha@4lb.ca'],
            [
                'name' => 'Lalpha Admin St-Émile',
                'password' => bcrypt('B0bby2111'),
                'email_verified_at' => now(),
                'ecole_id' => $ecoleStEmile->id
            ]
        );
        $lalpha->syncRoles(['admin']);

        echo "✅ UTILISATEURS CONFIGURÉS :" . PHP_EOL;
        echo "👑 SuperAdmin: louis@4lb.ca / password123 (Toutes écoles)" . PHP_EOL;
        echo "🏫 Admin École: lalpha@4lb.ca / B0bby2111 (École: " . $ecoleStEmile->nom . ")" . PHP_EOL;
        echo "📧 École ID: " . $ecoleStEmile->id . PHP_EOL;
    }
}
