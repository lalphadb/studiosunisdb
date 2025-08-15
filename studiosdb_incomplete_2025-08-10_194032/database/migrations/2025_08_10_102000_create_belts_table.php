<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('belts', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('couleur')->nullable();
            $table->unsignedTinyInteger('rang')->index(); // 1=blanche, 2=jaune...
            $table->timestamps();
            $table->unique('rang');
        });
    }
    public function down(): void {
        Schema::dropIfExists('belts');
    }
};
