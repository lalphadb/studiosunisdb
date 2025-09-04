<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Add ecole_id to users table for mono-école scoping
     */
    public function up(): void
    {
        if (!Schema::hasTable('users')) return;
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users','ecole_id')) {
                if (Schema::hasTable('ecoles')) {
                    $table->foreignId('ecole_id')->nullable()->constrained('ecoles')->onDelete('cascade');
                } else {
                    $table->unsignedBigInteger('ecole_id')->nullable();
                }
                $table->index(['ecole_id','email_verified_at']);
            }
        });
        if (Schema::hasTable('ecoles') && Schema::hasColumn('users','ecole_id')) {
            $premiereEcole = DB::table('ecoles')->first();
            if ($premiereEcole) { DB::table('users')->whereNull('ecole_id')->update(['ecole_id'=>$premiereEcole->id]); }
            try { Schema::table('users', function (Blueprint $table) { if (Schema::hasTable('ecoles')) $table->unsignedBigInteger('ecole_id')->nullable(false)->change(); }); } catch (Throwable $e) {}
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('users') || !Schema::hasColumn('users','ecole_id')) return;
        Schema::table('users', function (Blueprint $table) {
            try { $table->dropForeign(['ecole_id']); } catch (Throwable $e) {}
            try { $table->dropIndex(['ecole_id','email_verified_at']); } catch (Throwable $e) {}
            $table->dropColumn('ecole_id');
        });
    }
};
