<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ceintures', function (Blueprint $table) {
            if (!Schema::hasColumn('ceintures', 'name_en')) {
                $table->string('name_en')->nullable()->after('name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ceintures', function (Blueprint $table) {
            $table->dropColumn('name_en');
        });
    }
};
