<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('course_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->unsignedTinyInteger('weekday'); // 0=Dimanche .. 6=Samedi
            $table->time('start_time');
            $table->time('end_time');
            $table->json('instructeur_ids')->nullable(); // [user_id,...]
            $table->timestamps();
            $table->index(['course_id','weekday']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('course_schedules');
    }
};
