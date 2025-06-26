<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('seminaires', function (Blueprint $table) {
            // Vérifier si ecole_id existe avant de la supprimer
            if (Schema::hasColumn('seminaires', 'ecole_id')) {
                $table->dropForeign(['ecole_id']);
                $table->dropColumn('ecole_id');
            }
            
            // Supprimer capacite_max si elle existe
            if (Schema::hasColumn('seminaires', 'capacite_max')) {
                $table->dropColumn('capacite_max');
            }
            
            // Ajouter les nouveaux champs lieu
            if (!Schema::hasColumn('seminaires', 'lieu')) {
                $table->string('lieu')->after('heure_fin');
            }
            if (!Schema::hasColumn('seminaires', 'adresse_lieu')) {
                $table->string('adresse_lieu')->nullable()->after('lieu');
            }
            if (!Schema::hasColumn('seminaires', 'ville_lieu')) {
                $table->string('ville_lieu')->nullable()->after('adresse_lieu');
            }
        });
    }

    public function down()
    {
        Schema::table('seminaires', function (Blueprint $table) {
            // Remettre les colonnes supprimées
            $table->foreignId('ecole_id')->nullable()->constrained('ecoles')->onDelete('cascade');
            $table->integer('capacite_max')->default(30);
            
            // Supprimer les colonnes lieu
            $table->dropColumn(['lieu', 'adresse_lieu', 'ville_lieu']);
        });
    }
};
