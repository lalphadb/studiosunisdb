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
        Schema::table('users', function (Blueprint $table) {
            // Ajouter ecole_id seulement si n'existe pas
            if (!Schema::hasColumn('users', 'ecole_id')) {
                $table->foreignId('ecole_id')->nullable()->constrained('ecoles')->onDelete('cascade');
                $table->index(['ecole_id', 'email_verified_at']); // Performance auth
            }
        });
        
        // Populer avec première école (mono-école)
        $premiereEcole = DB::table('ecoles')->first();
        if ($premiereEcole) {
            DB::table('users')->whereNull('ecole_id')->update(['ecole_id' => $premiereEcole->id]);
            
            // Rendre obligatoire après population
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('ecole_id')->nullable(false)->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'ecole_id')) {
                $table->dropForeign(['ecole_id']);
                $table->dropIndex(['ecole_id', 'email_verified_at']);
                $table->dropColumn('ecole_id');
            }
        });
    }
};
