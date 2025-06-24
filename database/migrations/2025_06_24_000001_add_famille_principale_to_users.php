<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'famille_principale_id')) {
                $table->foreignId('famille_principale_id')->nullable()
                      ->after('ecole_id')
                      ->constrained('users')
                      ->onDelete('set null');
                
                $table->index('famille_principale_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'famille_principale_id')) {
                $table->dropForeign(['famille_principale_id']);
                $table->dropIndex(['famille_principale_id']);
                $table->dropColumn('famille_principale_id');
            }
        });
    }
};
