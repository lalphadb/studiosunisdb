<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class SetupPermissions extends Command
{
    protected $signature = 'studiosdb:setup-permissions';
    protected $description = 'Configure les permissions pour StudiosDB';

    public function handle()
    {
        $this->info('=== Configuration des permissions StudiosDB ===');

        // Permissions
        $permissions = [
            'admin.dashboard',
            'users.view', 'users.create', 'users.edit', 'users.delete',
            'cours.view', 'cours.create', 'cours.edit', 'cours.delete',
            'ecoles.view', 'ecoles.create', 'ecoles.edit', 'ecoles.delete',
            'sessions.view', 'sessions.create', 'sessions.edit', 'sessions.delete',
            'ceintures.view', 'ceintures.create', 'ceintures.edit', 'ceintures.delete',
            'seminaires.view', 'seminaires.create', 'seminaires.edit', 'seminaires.delete',
            'paiements.view', 'paiements.create', 'paiements.edit', 'paiements.delete',
            'presences.view', 'presences.create', 'presences.edit', 'presences.delete',
            'roles.view', 'roles.edit',
            'logs.view',
            'exports.view'
        ];

        $this->info('Création des permissions...');
        foreach ($permissions as $permission) {
            if (!Permission::where('name', $permission)->exists()) {
                Permission::create(['name' => $permission]);
                $this->line("✓ Permission créée: $permission");
            }
        }

        // Rôles
        $roles = ['superadmin', 'admin_ecole', 'membre'];
        $this->info('Création des rôles...');
        foreach ($roles as $role_name) {
            if (!Role::where('name', $role_name)->exists()) {
                Role::create(['name' => $role_name]);
                $this->line("✓ Rôle créé: $role_name");
            }
        }

        // Superadmin permissions
        $superadmin = Role::where('name', 'superadmin')->first();
        if ($superadmin) {
            $superadmin->syncPermissions($permissions);
            $this->info('✓ Permissions superadmin configurées');
        }

        // Admin école permissions
        $adminEcolePermissions = [
            'admin.dashboard',
            'users.view', 'users.create', 'users.edit', 'users.delete',
            'cours.view', 'cours.create', 'cours.edit', 'cours.delete',
            'sessions.view', 'sessions.create', 'sessions.edit', 'sessions.delete',
            'ceintures.view', 'ceintures.create', 'ceintures.edit', 'ceintures.delete',
            'seminaires.view', 'seminaires.create', 'seminaires.edit', 'seminaires.delete',
            'paiements.view', 'paiements.create', 'paiements.edit', 'paiements.delete',
            'presences.view', 'presences.create', 'presences.edit', 'presences.delete'
        ];

        $adminEcole = Role::where('name', 'admin_ecole')->first();
        if ($adminEcole) {
            $adminEcole->syncPermissions($adminEcolePermissions);
            $this->info('✓ Permissions admin_ecole configurées');
        }

        // Configurer user 5
        $user = User::find(5);
        if ($user) {
            $user->assignRole('superadmin');
            $this->info("✓ User 5 ({$user->name}) configuré comme superadmin");
            $this->line("   Peut accéder admin: " . ($user->can('admin.dashboard') ? 'OUI' : 'NON'));
        } else {
            $this->error('❌ User 5 non trouvé');
        }

        $this->info('=== Configuration terminée ===');
        return 0;
    }
}
