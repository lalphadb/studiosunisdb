<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Supprimer l'ancien champ famille_principale_id si il existe
            if (Schema::hasColumn('users', 'famille_principale_id')) {
                $table->dropForeign('users_famille_principale_id_foreign');
                $table->dropColumn('famille_principale_id');
            }
            
            // Ajouter les nouveaux champs famille simples
            $table->string('nom_famille')->nullable()->after('name')->comment('Nom de famille groupé');
            $table->string('contact_principal_famille')->nullable()->after('telephone')->comment('Contact principal de la famille');
            $table->string('telephone_principal_famille')->nullable()->after('contact_principal_famille');
            $table->text('notes_famille')->nullable()->after('notes')->comment('Notes famille partagées');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nom_famille', 'contact_principal_famille', 'telephone_principal_famille', 'notes_famille']);
        });
    }
};
