<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ceintures', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('ecole_id')->unsigned()->nullable();
            $table->string('nom');
            $table->string('couleur');
            $table->integer('ordre');
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->index('ecole_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ceintures');
    }
};
