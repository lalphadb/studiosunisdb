<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RolesAndSuperAdminSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Définir les rôles canoniques StudiosDB
        $roles = [
            'superadmin' => 'Super Administrateur - Accès total',
            'admin_ecole' => 'Administrateur École - Gestion complète école',
            'instructeur' => 'Instructeur - Gestion cours et membres',
            'membre' => 'Membre - Accès limité aux informations personnelles'
        ];

        echo "=== CRÉATION DES RÔLES SPATIE ===\n";
        
        foreach ($roles as $roleName => $description) {
            $role = Role::firstOrCreate(['name' => $roleName], ['guard_name' => 'web']);
            
            if ($role->wasRecentlyCreated) {
                echo "✅ Rôle créé: {$roleName}\n";
            } else {
                echo "ℹ️  Rôle existant: {$roleName}\n";
            }
        }

        // Permissions de base (optionnel - peut être étendu)
        $permissions = [
            'admin-panel' => 'Accès panneau administration',
            'cours-manage' => 'Gérer les cours',
            'membres-manage' => 'Gérer les membres',
            'users-manage' => 'Gérer les utilisateurs',
        ];

        echo "\n=== CRÉATION DES PERMISSIONS ===\n";
        
        foreach ($permissions as $permName => $description) {
            $permission = Permission::firstOrCreate(['name' => $permName], ['guard_name' => 'web']);
            
            if ($permission->wasRecentlyCreated) {
                echo "✅ Permission créée: {$permName}\n";
            } else {
                echo "ℹ️  Permission existante: {$permName}\n";
            }
        }

        // Attribution des permissions aux rôles
        echo "\n=== ATTRIBUTION PERMISSIONS ===\n";
        
        $superadmin = Role::findByName('superadmin');
        $adminEcole = Role::findByName('admin_ecole');
        $instructeur = Role::findByName('instructeur');
        
        // Superadmin : toutes les permissions
        $superadmin->givePermissionTo(Permission::all());
        echo "✅ Superadmin: toutes permissions\n";
        
        // Admin école : gestion cours, membres, panneau admin
        $adminEcole->givePermissionTo(['admin-panel', 'cours-manage', 'membres-manage']);
        echo "✅ Admin École: permissions de gestion\n";
        
        // Instructeur : gestion cours et membres (pas users)
        $instructeur->givePermissionTo(['cours-manage', 'membres-manage']);
        echo "✅ Instructeur: permissions cours/membres\n";

        // Créer le superadmin de manière idempotente
        echo "\n=== CRÉATION SUPERADMIN ===\n";
        $this->createSuperAdmin();

        echo "\n=== RÉSUMÉ FINAL ===\n";
        echo "Rôles disponibles: " . Role::count() . "\n";
        echo "Permissions disponibles: " . Permission::count() . "\n";
        echo "✅ Configuration des rôles terminée\n";
        
        // Clear cache des permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        echo "✅ Cache permissions réinitialisé\n";
    }

    private function createSuperAdmin()
    {
        // Créer ou mettre à jour le super admin LOUIS
        $superAdmin = User::updateOrCreate(
            ['email' => 'louis@4lb.ca'],
            [
                'name' => 'Louis Boudreau',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );

        // Créer le rôle superadmin si n'existe pas
        $role = Role::firstOrCreate(['name' => 'superadmin']);
        
        // Assigner le rôle
        $superAdmin->syncRoles(['superadmin']);

        $this->command->info('✅ Super Admin configuré : louis@4lb.ca');
        $this->command->info('   Mot de passe : password123');
    }
}
