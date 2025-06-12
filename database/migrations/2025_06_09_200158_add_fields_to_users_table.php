<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'ecole_id')) {
                $table->unsignedBigInteger('ecole_id')->nullable()->after('email_verified_at');
                $table->foreign('ecole_id')->references('id')->on('ecoles')->onDelete('set null');
            }
            if (!Schema::hasColumn('users', 'telephone')) {
                $table->string('telephone', 20)->nullable()->after('ecole_id');
            }
            if (!Schema::hasColumn('users', 'statut')) {
                $table->enum('statut', ['actif', 'inactif'])->default('actif')->after('telephone');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['ecole_id']);
            $table->dropColumn(['ecole_id', 'telephone', 'statut']);
        });
    }
};
