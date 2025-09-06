<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('membres', 'ecole_id')) {
            Schema::table('membres', function (Blueprint $table) {
                $table->unsignedBigInteger('ecole_id')->default(1)->after('id');
                $table->index('ecole_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('membres', 'ecole_id')) {
            Schema::table('membres', function (Blueprint $table) {
                $table->dropIndex(['ecole_id']);
                $table->dropColumn('ecole_id');
            });
        }
    }
};
