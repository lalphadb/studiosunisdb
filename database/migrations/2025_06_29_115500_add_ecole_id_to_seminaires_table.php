<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('seminaires', function (Blueprint $table) {
            $table->foreignId('ecole_id')->nullable()->after('id')->constrained()->onDelete('set null');
            $table->foreignId('processed_by_user_id')->nullable()->after('ecole_id')->constrained('users')->onDelete('set null');
            $table->enum('statut', ['planifie', 'ouvert', 'complet', 'termine', 'annule'])->default('planifie')->after('inscription_ouverte');
            $table->boolean('certificat')->default(false)->after('statut');
            $table->text('objectifs')->nullable()->after('materiel_requis');
            $table->text('prerequis')->nullable()->after('objectifs');
            $table->text('notes_admin')->nullable()->after('prerequis');
            
            // Index pour optimiser les requêtes multi-tenant
            $table->index(['ecole_id', 'statut']);
        });
    }

    public function down(): void
    {
        Schema::table('seminaires', function (Blueprint $table) {
            $table->dropForeign(['ecole_id']);
            $table->dropForeign(['processed_by_user_id']);
            $table->dropIndex(['ecole_id', 'statut']);
            $table->dropColumn([
                'ecole_id', 
                'processed_by_user_id',
                'statut',
                'certificat',
                'objectifs',
                'prerequis',
                'notes_admin'
            ]);
        });
    }
};
