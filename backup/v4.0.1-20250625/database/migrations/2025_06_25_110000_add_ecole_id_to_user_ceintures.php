<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_ceintures', function (Blueprint $table) {
            if (!Schema::hasColumn('user_ceintures', 'ecole_id')) {
                $table->foreignId('ecole_id')->nullable()->constrained('ecoles')->onDelete('cascade');
            }
        });
        
        // Remplir ecole_id avec l'école de l'utilisateur
        DB::statement("
            UPDATE user_ceintures uc
            JOIN users u ON uc.user_id = u.id
            SET uc.ecole_id = u.ecole_id
            WHERE uc.ecole_id IS NULL AND u.ecole_id IS NOT NULL
        ");
    }

    public function down(): void
    {
        Schema::table('user_ceintures', function (Blueprint $table) {
            $table->dropForeign(['ecole_id']);
            $table->dropColumn('ecole_id');
        });
    }
};
