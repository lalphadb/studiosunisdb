<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Modifier l'enum des motifs pour refléter les sessions trimestrielles
        DB::statement("ALTER TABLE paiements MODIFY COLUMN motif ENUM(
            'session_hiver',
            'session_printemps', 
            'session_ete',
            'session_automne',
            'seminaire',
            'examen_ceinture',
            'equipement',
            'autre'
        ) NOT NULL");
    }

    public function down()
    {
        DB::statement("ALTER TABLE paiements MODIFY COLUMN motif ENUM(
            'cotisation_mensuelle',
            'cotisation_annuelle',
            'seminaire',
            'examen_ceinture',
            'equipement',
            'autre'
        ) NOT NULL");
    }
};
