<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Étape 1: Ajouter la colonne ecole_id manquante à user_ceintures
        if (!Schema::hasColumn('user_ceintures', 'ecole_id')) {
            Schema::table('user_ceintures', function (Blueprint $table) {
                $table->foreignId('ecole_id')->nullable()->constrained('ecoles')->onDelete('cascade');
            });
        }
        
        // Étape 2: Migrer les données avec les bonnes colonnes
        if (Schema::hasTable('membre_ceintures') && Schema::hasTable('user_ceintures')) {
            $membreData = DB::table('membre_ceintures')->get();
            
            foreach ($membreData as $record) {
                // Récupérer l'école de l'utilisateur
                $user = DB::table('users')->where('id', $record->user_id)->first();
                
                $insertData = [
                    'user_id' => $record->user_id,
                    'ceinture_id' => $record->ceinture_id,
                    'date_obtention' => $record->date_obtention,
                    'valide' => $record->valide ?? 0,
                    'examinateur' => $record->examinateur ?? null,  // Garder examinateur
                    'commentaires' => $record->commentaires ?? null, // Garder commentaires
                    'ecole_id' => $user->ecole_id ?? 1,
                    'created_at' => $record->created_at ?? now(),
                    'updated_at' => $record->updated_at ?? now(),
                ];
                
                // Insérer dans user_ceintures
                DB::table('user_ceintures')->insert($insertData);
                
                echo "Migré: User " . $record->user_id . " -> Ceinture " . $record->ceinture_id . "\n";
            }
            
            echo "Migration terminée: " . count($membreData) . " enregistrement(s)\n";
        }
        
        // Étape 3: Supprimer l'ancienne table membre_ceintures
        Schema::dropIfExists('membre_ceintures');
        
        // Étape 4: Ajouter les index pour les performances
        Schema::table('user_ceintures', function (Blueprint $table) {
            try {
                $table->index(['ecole_id', 'date_obtention']);
            } catch (Exception $e) {
                // Index existe déjà
            }
            
            try {
                $table->index(['user_id', 'date_obtention']);
            } catch (Exception $e) {
                // Index existe déjà
            }
        });
    }

    public function down(): void
    {
        // Recréer membre_ceintures si nécessaire
        Schema::create('membre_ceintures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('ceinture_id')->constrained('ceintures')->onDelete('cascade');
            $table->date('date_obtention');
            $table->string('examinateur')->nullable();
            $table->text('commentaires')->nullable();
            $table->boolean('valide')->default(false);
            $table->timestamps();
        });
        
        // Migrer les données en retour
        $userData = DB::table('user_ceintures')->get();
        foreach ($userData as $record) {
            DB::table('membre_ceintures')->insert([
                'user_id' => $record->user_id,
                'ceinture_id' => $record->ceinture_id,
                'date_obtention' => $record->date_obtention,
                'examinateur' => $record->examinateur,
                'commentaires' => $record->commentaires,
                'valide' => $record->valide,
                'created_at' => $record->created_at,
                'updated_at' => $record->updated_at,
            ]);
        }
    }
};
