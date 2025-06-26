<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Vérifier si la table user_ceintures existe
        if (!Schema::hasTable('user_ceintures')) {
            Schema::create('user_ceintures', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('ceinture_id')->constrained('ceintures')->onDelete('cascade');
                $table->date('date_obtention');
                $table->boolean('valide')->default(true);
                $table->text('notes')->nullable();
                $table->foreignId('ecole_id')->nullable()->constrained('ecoles')->onDelete('cascade');
                $table->timestamps();
                
                $table->index(['user_id', 'date_obtention']);
                $table->index(['ecole_id', 'date_obtention']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('user_ceintures');
    }
};
