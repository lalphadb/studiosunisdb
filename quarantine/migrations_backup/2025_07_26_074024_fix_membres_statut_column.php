<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('membres', function (Blueprint $table) {
            // Vérifier si colonne 'statut' existe
            if (! Schema::hasColumn('membres', 'statut')) {
                $table->enum('statut', ['actif', 'inactif', 'suspendu'])->default('actif')->after('contact_urgence_telephone');
            }

            // Si colonne 'active' existe, migrer vers 'statut'
            if (Schema::hasColumn('membres', 'active')) {
                // Sera fait dans le seeder de données
            }
        });
    }

    public function down()
    {
        Schema::table('membres', function (Blueprint $table) {
            $table->dropColumn('statut');
        });
    }
};
