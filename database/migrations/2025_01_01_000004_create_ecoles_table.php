<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Créer la table ecoles si elle n'existe pas
        if (!Schema::hasTable('ecoles')) {
            Schema::create('ecoles', function (Blueprint $table) {
                $table->id();
                $table->string('nom');
                $table->string('slug')->unique();
                $table->string('adresse')->nullable();
                $table->string('ville')->nullable();
                $table->string('code_postal', 10)->nullable();
                $table->string('province', 2)->default('QC');
                $table->string('telephone', 20)->nullable();
                $table->string('email')->nullable();
                $table->string('site_web')->nullable();
                $table->string('logo')->nullable();
                $table->json('configuration')->nullable();
                $table->boolean('est_active')->default(true);
                $table->timestamps();
                
                $table->index('slug');
                $table->index('est_active');
            });
            
            // Insérer une école par défaut
            DB::table('ecoles')->insert([
                'nom' => 'École de Karaté Studios Unis',
                'slug' => 'studios-unis',
                'ville' => 'Québec',
                'province' => 'QC',
                'est_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ecoles');
    }
};
