<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('ecole_id')->nullable()->constrained('schools')->nullOnDelete();
            $table->enum('statut', ['actif','suspendu','inactif'])->default('actif')->index();
            $table->timestamp('last_login_at')->nullable();
        });
    }
    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('ecole_id');
            $table->dropColumn(['statut','last_login_at']);
        });
    }
};
