<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('membre_ceintures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('membre_id')->constrained('membres')->onDelete('cascade');
            $table->foreignId('ceinture_id')->constrained('ceintures')->onDelete('cascade');
            $table->date('date_obtention');
            $table->string('examinateur')->nullable();
            $table->text('commentaires')->nullable();
            $table->boolean('valide')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('membre_ceintures');
    }
};
