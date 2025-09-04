<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('telescope_entries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uuid')->index();
            $table->string('batch_id')->index();
            $table->string('family_hash')->nullable()->index();
            $table->string('should_display_on_index')->default('1');
            $table->string('type')->index();
            $table->text('content');
            $table->text('user_id')->nullable();
            $table->string('user_type')->nullable();
            $table->string('tags')->nullable();
            $table->integer('sequence')->default(0);
            $table->timestamp('created_at')->nullable()->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('telescope_entries');
    }
};
