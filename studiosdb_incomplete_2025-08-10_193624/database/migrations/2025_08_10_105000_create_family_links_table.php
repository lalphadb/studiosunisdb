<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('family_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            $table->foreignId('related_member_id')->constrained('members')->cascadeOnDelete();
            $table->enum('type', ['parent','enfant','frere_soeur','tuteur','autre'])->index();
            $table->timestamps();

            $table->unique(['member_id','related_member_id','type']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('family_links');
    }
};
