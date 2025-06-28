<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Ecole;
use App\Models\Ceinture;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RestoreBaseDataSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('🚀 Restauration des données de base...');

        // 1. Créer les permissions et rôles si pas déjà fait
        $this->createPermissionsAndRoles();

        // 2. Créer l'école St-Émile
        $this->createEcoles();

        // 3. Créer les ceintures de base
        $this->createCeintures();

        // 4. Créer les utilisateurs administrateurs
        $this->createAdminUsers();

        $this->command->info('✅ Données de base restaurées avec succès !');
    }

    private function createPermissionsAndRoles()
    {
        $this->command->info('📋 Création des permissions et rôles...');

        // Réinitialiser le cache des permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Supprimer les anciennes permissions/rôles si elles existent
        Permission::query()->delete();
        Role::query()->delete();

        // Créer toutes les permissions
        $permissions = [
            // Dashboard
            'view-dashboard',
            
            // Users
            'view-users', 'create-user', 'edit-user', 'delete-user', 'export-users',
            
            // Écoles
            'view-ecoles', 'create-ecole', 'edit-ecole', 'delete-ecole',
            
            // Ceintures
            'view-ceintures', 'create-ceinture', 'edit-ceinture', 'delete-ceinture', 'assign-ceintures',
            
            // Cours
            'view-cours', 'create-cours', 'edit-cours', 'delete-cours',
            
            // Modules futurs
            'view-seminaires', 'create-seminaire', 'edit-seminaire', 'delete-seminaire',
            'view-presences', 'create-presence', 'edit-presence', 'delete-presence',
            'view-paiements', 'create-paiement', 'edit-paiement', 'delete-paiement',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'web']);
        }

        // Créer les rôles
        $superAdminRole = Role::create(['name' => 'super-admin', 'guard_name' => 'web']);
        $adminEcoleRole = Role::create(['name' => 'admin-ecole', 'guard_name' => 'web']);
        $instructeurRole = Role::create(['name' => 'instructeur', 'guard_name' => 'web']);
        $membreRole = Role::create(['name' => 'membre', 'guard_name' => 'web']);

        // Assigner toutes les permissions au super-admin
        $superAdminRole->givePermissionTo(Permission::all());

        // Permissions pour admin d'école
        $adminEcoleRole->givePermissionTo([
            'view-dashboard',
            'view-users', 'create-user', 'edit-user', 'export-users',
            'view-ecoles', 'edit-ecole',
            'view-ceintures', 'assign-ceintures',
            'view-cours', 'create-cours', 'edit-cours',
            'view-seminaires', 'create-seminaire', 'edit-seminaire',
            'view-presences', 'create-presence', 'edit-presence',
            'view-paiements', 'create-paiement', 'edit-paiement'
        ]);

        // Permissions pour instructeur
        $instructeurRole->givePermissionTo([
            'view-dashboard',
            'view-users',
            'view-cours',
            'view-ceintures', 'assign-ceintures',
            'view-presences', 'create-presence', 'edit-presence'
        ]);

        // Permissions pour membre
        $membreRole->givePermissionTo(['view-dashboard']);

        $this->command->info('✅ Permissions et rôles créés');
    }

    private function createEcoles()
    {
        $this->command->info('🏫 Création des écoles...');

        // École St-Émile
        $stEmile = Ecole::updateOrCreate(
            ['code' => 'STE'],
            [
                'nom' => 'École de Karaté St-Émile',
                'adresse' => '1234 Rue Principale',
                'ville' => 'St-Émile',
                'province' => 'QC',
                'code_postal' => 'G0A 4E0',
                'telephone' => '418-555-0123',
                'email' => 'info@karatestemile.ca',
                'site_web' => 'https://karatestemile.ca',
                'description' => 'École de karaté traditionnelle située à St-Émile, offrant des cours pour tous les âges et niveaux.',
                'active' => true
            ]
        );

        // École Lalpha (pour les tests)
        $lalpha = Ecole::updateOrCreate(
            ['code' => 'LAL'],
            [
                'nom' => 'École Lalpha Dojo',
                'adresse' => '5678 Boulevard Test',
                'ville' => 'Québec',
                'province' => 'QC',
                'code_postal' => 'G1V 4B6',
                'telephone' => '418-555-0456',
                'email' => 'contact@lalpha.ca',
                'site_web' => 'https://lalpha.ca',
                'description' => 'École moderne de karaté avec installations de pointe.',
                'active' => true
            ]
        );

        $this->command->info("✅ Écoles créées: {$stEmile->nom}, {$lalpha->nom}");
    }

    private function createCeintures()
    {
        $this->command->info('🥋 Création des ceintures...');

        $ceintures = [
            ['nom' => 'Blanche', 'couleur' => '#FFFFFF', 'ordre' => 1, 'description' => 'Ceinture de débutant'],
            ['nom' => 'Jaune', 'couleur' => '#FFFF00', 'ordre' => 2, 'description' => 'Premier niveau de progression'],
            ['nom' => 'Orange', 'couleur' => '#FFA500', 'ordre' => 3, 'description' => 'Deuxième niveau de progression'],
            ['nom' => 'Verte', 'couleur' => '#008000', 'ordre' => 4, 'description' => 'Niveau intermédiaire'],
            ['nom' => 'Bleue', 'couleur' => '#0000FF', 'ordre' => 5, 'description' => 'Niveau intermédiaire avancé'],
            ['nom' => 'Brune', 'couleur' => '#8B4513', 'ordre' => 6, 'description' => 'Niveau avancé'],
            ['nom' => 'Noire 1er Dan', 'couleur' => '#000000', 'ordre' => 7, 'description' => 'Premier niveau de maîtrise'],
            ['nom' => 'Noire 2e Dan', 'couleur' => '#000000', 'ordre' => 8, 'description' => 'Deuxième niveau de maîtrise'],
            ['nom' => 'Noire 3e Dan', 'couleur' => '#000000', 'ordre' => 9, 'description' => 'Troisième niveau de maîtrise'],
            ['nom' => 'Noire 4e Dan', 'couleur' => '#000000', 'ordre' => 10, 'description' => 'Quatrième niveau de maîtrise'],
        ];

        foreach ($ceintures as $ceintureData) {
            Ceinture::updateOrCreate(
                ['nom' => $ceintureData['nom']],
                $ceintureData
            );
        }

        $this->command->info('✅ Ceintures créées: ' . count($ceintures) . ' ceintures');
    }

    private function createAdminUsers()
    {
        $this->command->info('👥 Création des utilisateurs administrateurs...');

        $stEmile = Ecole::where('code', 'STE')->first();
        $lalpha = Ecole::where('code', 'LAL')->first();

        // Super Admin - lalpha@4lb.ca
        $superAdmin = User::updateOrCreate(
            ['email' => 'lalpha@4lb.ca'],
            [
                'name' => 'Lalpha Super Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'ecole_id' => $lalpha->id,
                'telephone' => '418-555-9999',
                'date_naissance' => '1980-01-01',
                'sexe' => 'M',
                'adresse' => '123 Rue Admin',
                'ville' => 'Québec',
                'code_postal' => 'G1V 0A1',
                'active' => true,
                'date_inscription' => now()->format('Y-m-d'),
                'notes' => 'Super administrateur du système'
            ]
        );

        // Admin École St-Émile - louis@4lb.ca
        $adminStEmile = User::updateOrCreate(
            ['email' => 'louis@4lb.ca'],
            [
                'name' => 'Louis Admin St-Émile',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'ecole_id' => $stEmile->id,
                'telephone' => '418-555-1234',
                'date_naissance' => '1975-05-15',
                'sexe' => 'M',
                'adresse' => '456 Rue École',
                'ville' => 'St-Émile',
                'code_postal' => 'G0A 4E0',
                'active' => true,
                'date_inscription' => now()->format('Y-m-d'),
                'notes' => 'Administrateur de l\'école St-Émile'
            ]
        );

        // Assigner les rôles
        $superAdminRole = Role::where('name', 'super-admin')->first();
        $adminEcoleRole = Role::where('name', 'admin-ecole')->first();

        if ($superAdminRole) {
            $superAdmin->assignRole($superAdminRole);
        }

        if ($adminEcoleRole) {
            $adminStEmile->assignRole($adminEcoleRole);
        }

        $this->command->info("✅ Utilisateurs créés:");
        $this->command->info("   - Super Admin: {$superAdmin->email} (École: {$lalpha->nom})");
        $this->command->info("   - Admin St-Émile: {$adminStEmile->email} (École: {$stEmile->nom})");
        $this->command->info("   - Mot de passe par défaut: 'password'");
    }
}
