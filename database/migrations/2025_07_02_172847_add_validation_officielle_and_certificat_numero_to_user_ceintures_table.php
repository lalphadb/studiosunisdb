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
            $table->boolean('validation_officielle')->default(false)->after('valide');
            $table->string('certificat_numero')->nullable()->after('validation_officielle');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_ceintures', function (Blueprint $table) {
            $table->dropColumn(['validation_officielle', 'certificat_numero']);
        });
    }
};
