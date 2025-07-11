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
        Schema::table('users', function (Blueprint $table) {
            // Renommer name en nom et prenom
            $table->string('nom', 100)->after('id');
            $table->string('prenom', 100)->after('nom');
            
            // Ajouter les champs StudiosDB
            $table->foreignId('ecole_id')->nullable()->after('email')->constrained('ecoles')->onDelete('restrict');
            $table->string('code_utilisateur', 20)->unique()->nullable()->after('ecole_id');
            $table->string('telephone', 20)->nullable();
            $table->date('date_naissance')->nullable();
            $table->enum('sexe', ['M', 'F', 'A'])->nullable();
            $table->string('adresse', 255)->nullable();
            $table->string('ville', 100)->nullable();
            $table->string('province', 50)->nullable();
            $table->string('code_postal', 10)->nullable();
            $table->string('pays', 50)->default('Canada');
            $table->string('photo', 255)->nullable();
            $table->string('contact_urgence_nom', 100)->nullable();
            $table->string('contact_urgence_telephone', 20)->nullable();
            $table->text('notes')->nullable();
            $table->boolean('actif')->default(true);
            $table->datetime('derniere_connexion')->nullable();
            $table->softDeletes();
            
            // Index
            $table->index('ecole_id');
            $table->index('code_utilisateur');
            $table->index('actif');
            $table->index(['nom', 'prenom']);
        });
        
        // Supprimer la colonne name après migration des données
        if (Schema::hasColumn('users', 'name')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('name');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->after('id');
            
            $table->dropForeign(['ecole_id']);
            $table->dropIndex(['ecole_id']);
            $table->dropIndex(['code_utilisateur']);
            $table->dropIndex(['actif']);
            $table->dropIndex(['nom', 'prenom']);
            
            $table->dropColumn([
                'nom', 'prenom', 'ecole_id', 'code_utilisateur',
                'telephone', 'date_naissance', 'sexe', 'adresse',
                'ville', 'province', 'code_postal', 'pays', 'photo',
                'contact_urgence_nom', 'contact_urgence_telephone',
                'notes', 'actif', 'derniere_connexion', 'deleted_at'
            ]);
        });
    }
};
