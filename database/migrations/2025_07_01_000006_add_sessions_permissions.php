<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    public function up(): void
    {
        // Permissions pour les sessions
        $permissions = [
            'view-sessions',
            'create-sessions', 
            'update-sessions',
            'delete-sessions',
            'viewAny-sessions',
            'manage-sessions',
            
            'view-horaires',
            'create-horaires',
            'update-horaires', 
            'delete-horaires',
            'viewAny-horaires',
            'manage-horaires'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'web']);
        }

        // Attribuer aux rôles existants
        $superAdmin = Role::where('name', 'superadmin')->first();
        $adminEcole = Role::where('name', 'admin_ecole')->first();
        
        if ($superAdmin) {
            $superAdmin->givePermissionTo($permissions);
        }
        
        if ($adminEcole) {
            $adminEcole->givePermissionTo([
                'view-sessions', 'create-sessions', 'update-sessions', 'delete-sessions', 'viewAny-sessions',
                'view-horaires', 'create-horaires', 'update-horaires', 'delete-horaires', 'viewAny-horaires'
            ]);
        }
    }

    public function down(): void
    {
        $permissions = [
            'view-sessions', 'create-sessions', 'update-sessions', 'delete-sessions', 'viewAny-sessions', 'manage-sessions',
            'view-horaires', 'create-horaires', 'update-horaires', 'delete-horaires', 'viewAny-horaires', 'manage-horaires'
        ];

        foreach ($permissions as $permission) {
            Permission::where('name', $permission)->delete();
        }
    }
};
