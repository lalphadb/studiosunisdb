<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('privacy_consents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('member_id')->nullable()->constrained('members')->nullOnDelete();
            $table->string('version')->default('v1');
            $table->timestamp('given_at');
            $table->ipAddress('ip')->nullable();
            $table->text('texte_snapshot'); // copie du texte accepté
            $table->timestamps();
            $table->index(['user_id','member_id']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('privacy_consents');
    }
};
