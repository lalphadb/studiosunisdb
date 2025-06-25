<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Vérifier si la table membre_ceintures existe avant de la renommer
        if (Schema::hasTable('membre_ceintures') && !Schema::hasTable('user_ceintures')) {
            // Copier les données vers la nouvelle table
            Schema::create('user_ceintures', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('ceinture_id')->constrained()->onDelete('cascade');
                $table->date('date_obtention');
                $table->string('examinateur')->nullable();
                $table->text('commentaires')->nullable();
                $table->boolean('valide')->default(false);
                $table->timestamps();
                
                $table->index(['user_id', 'date_obtention']);
            });
            
            // Copier les données
            DB::statement('INSERT INTO user_ceintures SELECT * FROM membre_ceintures');
            
            // Supprimer l'ancienne table seulement après vérification
            Schema::dropIfExists('membre_ceintures');
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('user_ceintures') && !Schema::hasTable('membre_ceintures')) {
            Schema::rename('user_ceintures', 'membre_ceintures');
        }
    }
};
