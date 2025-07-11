<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        // Créer les permissions
        $permissions = [
            // SuperAdmin
            'manage-all-schools',
            'view-telescope',
            'view-all-logs',
            'create-schools',
            'delete-schools',
            
            // Admin École
            'manage-school',
            'manage-users',
            'manage-courses',
            'manage-belts',
            'manage-payments',
            'manage-seminars',
            'manage-presences',
            'manage-schedules',
            'export-data',
            'view-reports',
            
            // Instructeur
            'teach-courses',
            'take-presences',
            'view-students',
            'manage-own-courses',
            'propose-seminars',
            
            // Membre
            'view-own-profile',
            'view-own-presences',
            'view-own-payments',
            'view-own-progress',
            'register-seminars',
            'view-schedules',
        ];
        
        $this->command->info('Création des permissions...');
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
        
        // Créer les rôles avec leurs permissions
        $roles = [
            'superadmin' => Permission::all(), // Toutes les permissions
            
            'admin_ecole' => [
                'manage-school',
                'manage-users',
                'manage-courses',
                'manage-belts',
                'manage-payments',
                'manage-seminars',
                'manage-presences',
                'manage-schedules',
                'export-data',
                'view-reports',
            ],
            
            'instructeur' => [
                'teach-courses',
                'take-presences',
                'view-students',
                'manage-own-courses',
                'propose-seminars',
                'view-schedules',
            ],
            
            'membre' => [
                'view-own-profile',
                'view-own-presences',
                'view-own-payments',
                'view-own-progress',
                'register-seminars',
                'view-schedules',
            ],
        ];
        
        $this->command->info('Création des rôles...');
        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            
            if (is_array($rolePermissions)) {
                $role->syncPermissions($rolePermissions);
            } else {
                // Pour superadmin qui a toutes les permissions
                $role->syncPermissions($rolePermissions);
            }
            
            $this->command->info("  ✓ Rôle '$roleName' créé avec " . count($role->permissions) . " permissions");
        }
        
        $this->command->info('✅ Permissions et rôles créés avec succès');
    }
}
