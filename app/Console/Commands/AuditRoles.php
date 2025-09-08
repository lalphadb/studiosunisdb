<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AuditRoles extends Command
{
    protected $signature = 'audit:roles {--fix-missing-superadmin : Ensure at least one superadmin exists}';

    protected $description = 'Audit roles & permissions consistency across policies and database';

    public function handle(): int
    {
        $this->info('--- Roles & Permissions Audit ---');

        $dbRoles = Role::all()->pluck('name')->sort()->values();
        $this->line('Existing roles (DB): '.$dbRoles->join(', '));

        $policyMatrix = [
            'cours' => [
                'viewAny' => ['superadmin', 'admin_ecole', 'instructeur', 'membre'],
                'manage' => ['superadmin', 'admin_ecole'],
            ],
            'membre' => [
                'view' => ['superadmin', 'admin_ecole', 'instructeur'],
                'manage' => ['superadmin', 'admin_ecole'],
            ],
            'paiement' => [
                'view' => ['superadmin', 'admin_ecole', 'instructeur'],
                'manage' => ['superadmin', 'admin_ecole'],
            ],
            'presence' => [
                'view' => ['superadmin', 'admin_ecole', 'instructeur'],
                'manage' => ['superadmin', 'admin_ecole'],
            ],
            'ceinture' => [
                'view' => ['superadmin', 'admin_ecole'],
                'manage' => ['superadmin'],
            ],
            'users' => [
                'view' => ['superadmin', 'admin_ecole'],
                'manage' => ['superadmin', 'admin_ecole'],
            ],
        ];

        $referencedRoles = collect($policyMatrix)->flatMap(fn ($ops) => collect($ops)->flatten())->unique()->sort()->values();
        $this->line('Roles referenced in policies: '.$referencedRoles->join(', '));

        $missingInDb = $referencedRoles->diff($dbRoles);
        if ($missingInDb->isNotEmpty()) {
            $this->warn('Missing roles in DB: '.$missingInDb->join(', '));
        } else {
            $this->info('All policy roles exist in DB.');
        }

        // Detect orphan roles (present in DB but not referenced anywhere)
        $orphan = $dbRoles->diff($referencedRoles);
        if ($orphan->isNotEmpty()) {
            $this->warn('Orphan (unused) roles: '.$orphan->join(', '));
        } else {
            $this->info('No orphan roles.');
        }

        // Count users per role
        $this->line("\nUsers per role:");
        $dbRoles->each(function ($role) {
            $count = User::role($role)->count();
            $this->line(str_pad(" - $role", 20).': '.$count);
        });

        // Ensure at least one superadmin
        $superCount = User::role('superadmin')->count();
        if ($superCount === 0) {
            $this->error('No user has role superadmin!');
            if ($this->option('fix-missing-superadmin')) {
                $user = User::first();
                if ($user) {
                    $user->assignRole('superadmin');
                    $this->info("Assigned superadmin to user #{$user->id} ({$user->email})");
                } else {
                    $this->error('No users found to assign superadmin.');
                }
            }
        } else {
            $this->info("Superadmin user count: $superCount");
        }

        // Suggest permission mapping (future hardening)
        if (Permission::count() === 0) {
            $this->warn('No granular permissions defined yet (Spatie\Permission permissions table empty). Consider defining permissions for finer control.');
        }

        $this->info('\nAudit complete.');

        return Command::SUCCESS;
    }
}
