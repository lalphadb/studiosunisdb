<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Merge any accidental 'uperadmin' role into 'superadmin'
        $rolesTable = config('permission.table_names.roles', 'roles');
        $modelHasRoles = config('permission.table_names.model_has_roles', 'model_has_roles');

        $uper = DB::table($rolesTable)->where('name', 'uperadmin')->first();
        if (!$uper) return; // nothing to do

        $super = DB::table($rolesTable)->where('name', 'superadmin')->first();
        if (!$super) {
            // Rename directly if superadmin does not exist
            DB::table($rolesTable)->where('id', $uper->id)->update(['name' => 'superadmin']);
            return;
        }

        // Reassign all model role links from uperadmin to superadmin
        DB::table($modelHasRoles)
            ->where('role_id', $uper->id)
            ->update(['role_id' => $super->id]);

        // Delete the stray role
        DB::table($rolesTable)->where('id', $uper->id)->delete();
    }

    public function down(): void
    {
        // No rollback (intentional consolidation)
    }
};
