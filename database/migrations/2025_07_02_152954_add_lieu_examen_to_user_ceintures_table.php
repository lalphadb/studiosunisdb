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
        Schema::table('user_ceintures', function (Blueprint $table) {
            $table->string('lieu_examen')->nullable()->after('examinateur');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_ceintures', function (Blueprint $table) {
            $table->dropColumn('lieu_examen');
        });
    }
};
