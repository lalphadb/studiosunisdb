<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class InitialDataSeeder extends Seeder
{
    public function run()
    {
        // Réinitialiser les tables de permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Créer les rôles
        $superAdmin = Role::create(['name' => 'super-admin']);
        $admin = Role::create(['name' => 'admin']);
        $gestionnaire = Role::create(['name' => 'gestionnaire']);
        $instructeur = Role::create(['name' => 'instructeur']);
        $membre = Role::create(['name' => 'membre']);

        // Créer les permissions par module
        $permissions = [
            // Membres
            'membres.view', 'membres.create', 'membres.edit', 'membres.delete',
            'membres.export', 'membres.changer-ceinture',
            
            // Cours
            'cours.view', 'cours.create', 'cours.edit', 'cours.delete',
            'cours.duplicate', 'cours.planning',
            
            // Présences
            'presences.view', 'presences.tablette', 'presences.marquer',
            'presences.rapports', 'presences.export',
            
            // Paiements
            'paiements.view', 'paiements.create', 'paiements.confirmer',
            'paiements.rembourser', 'paiements.generer-factures',
            
            // Ceintures
            'ceintures.view', 'ceintures.examens', 'ceintures.planifier',
            'ceintures.valider', 'ceintures.certificats',
            
            // Administration
            'admin.configuration', 'admin.utilisateurs', 'admin.backup',
            'admin.logs', 'admin.statistiques',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Assigner toutes les permissions au super-admin
        $superAdmin->givePermissionTo(Permission::all());

        // Assigner les permissions à l'admin
        $admin->givePermissionTo([
            'membres.view', 'membres.create', 'membres.edit', 'membres.delete', 'membres.export', 'membres.changer-ceinture',
            'cours.view', 'cours.create', 'cours.edit', 'cours.delete', 'cours.duplicate', 'cours.planning',
            'presences.view', 'presences.tablette', 'presences.marquer', 'presences.rapports', 'presences.export',
            'paiements.view', 'paiements.create', 'paiements.confirmer', 'paiements.generer-factures',
            'ceintures.view', 'ceintures.examens', 'ceintures.planifier', 'ceintures.valider',
            'admin.configuration', 'admin.statistiques',
        ]);

        // Assigner les permissions au gestionnaire
        $gestionnaire->givePermissionTo([
            'membres.view', 'membres.create', 'membres.edit', 'membres.export',
            'cours.view', 'cours.planning',
            'presences.view', 'presences.tablette', 'presences.marquer', 'presences.rapports',
            'paiements.view', 'paiements.create', 'paiements.confirmer',
            'ceintures.view',
        ]);

        // Assigner les permissions à l'instructeur
        $instructeur->givePermissionTo([
            'membres.view', 'membres.changer-ceinture',
            'cours.view', 'cours.planning',
            'presences.view', 'presences.tablette', 'presences.marquer',
            'ceintures.view', 'ceintures.examens',
        ]);

        // Assigner les permissions au membre
        $membre->givePermissionTo([
            'membres.view', // Seulement son propre profil
            'cours.view',
            'presences.view', // Ses propres présences
            'paiements.view', // Ses propres paiements
        ]);

        // Créer l'utilisateur Louis et lui assigner le rôle admin
        $louis = User::updateOrCreate(
            ['email' => 'louis@4lb.ca'],
            [
                'name' => 'Louis Admin',
                'password' => Hash::make('password123'), // À changer en production!
                'email_verified_at' => now(),
            ]
        );
        $louis->assignRole('admin');

        // Créer un utilisateur super-admin
        $superAdminUser = User::updateOrCreate(
            ['email' => 'super@studiosdb.local'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('SuperAdmin2025!'),
                'email_verified_at' => now(),
            ]
        );
        $superAdminUser->assignRole('super-admin');

        // Créer les ceintures de base
        $ceintures = [
            ['name' => 'Blanche', 'color_hex' => '#FFFFFF', 'order' => 1],
            ['name' => 'Jaune', 'color_hex' => '#FFD700', 'order' => 2],
            ['name' => 'Orange', 'color_hex' => '#FFA500', 'order' => 3],
            ['name' => 'Verte', 'color_hex' => '#00FF00', 'order' => 4],
            ['name' => 'Bleue', 'color_hex' => '#0000FF', 'order' => 5],
            ['name' => 'Marron', 'color_hex' => '#8B4513', 'order' => 6],
            ['name' => 'Noire 1er Dan', 'color_hex' => '#000000', 'order' => 7],
            ['name' => 'Noire 2e Dan', 'color_hex' => '#000000', 'order' => 8],
            ['name' => 'Noire 3e Dan', 'color_hex' => '#000000', 'order' => 9],
        ];

        foreach ($ceintures as $ceinture) {
            DB::table('belts')->updateOrInsert(
                ['order' => $ceinture['order']],
                array_merge($ceinture, [
                    'minimum_duration_months' => 3,
                    'minimum_attendances' => 24,
                    'active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }

        $this->command->info('Données initiales créées avec succès!');
        $this->command->info('Utilisateur admin: louis@4lb.ca / password123');
        $this->command->info('Super admin: super@studiosdb.local / SuperAdmin2025!');
    }
}
