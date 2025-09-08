<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('examens', function (Blueprint $table) {
            $table->foreignId('membre_id')->constrained()->onDelete('cascade');
            $table->foreignId('ceinture_id')->constrained('ceintures');
            $table->foreignId('instructeur_id')->constrained('users');
            $table->date('date_examen');
            $table->time('heure_examen');
            $table->enum('statut', ['planifie', 'en_cours', 'reussi', 'echec', 'reporte'])->default('planifie');
            $table->integer('note_technique')->nullable(); // /100
            $table->integer('note_physique')->nullable(); // /100
            $table->integer('note_kata')->nullable(); // /100
            $table->integer('note_finale')->nullable(); // /100
            $table->text('commentaires')->nullable();
            $table->text('points_forts')->nullable();
            $table->text('points_amelioration')->nullable();
            $table->boolean('certificat_emis')->default(false);
            $table->date('date_certificat')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('examens', function (Blueprint $table) {
            $table->dropColumn([
                'membre_id', 'ceinture_id', 'instructeur_id',
                'date_examen', 'heure_examen', 'statut',
                'note_technique', 'note_physique', 'note_kata', 'note_finale',
                'commentaires', 'points_forts', 'points_amelioration',
                'certificat_emis', 'date_certificat',
            ]);
        });
    }
};
