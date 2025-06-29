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
        Schema::table('paiements', function (Blueprint $table) {
            // Ajouter les colonnes manquantes avec des valeurs par défaut
            if (!Schema::hasColumn('paiements', 'montant_net')) {
                $table->decimal('montant_net', 10, 2)->default(0)->after('montant');
            }
            
            if (!Schema::hasColumn('paiements', 'frais')) {
                $table->decimal('frais', 10, 2)->default(0)->after('montant_net');
            }
            
            if (!Schema::hasColumn('paiements', 'date_echeance')) {
                $table->date('date_echeance')->nullable()->after('date_paiement');
            }
            
            if (!Schema::hasColumn('paiements', 'motif')) {
                $table->string('motif')->nullable()->after('statut');
            }
            
            if (!Schema::hasColumn('paiements', 'description')) {
                $table->text('description')->nullable()->after('motif');
            }
            
            // Modifier les colonnes existantes pour avoir des valeurs par défaut si nécessaire
            $table->decimal('montant', 10, 2)->default(0)->change();
            $table->unsignedBigInteger('ecole_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paiements', function (Blueprint $table) {
            $table->dropColumn(['montant_net', 'frais', 'date_echeance', 'motif', 'description']);
        });
    }
};
