<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Étape 1: Migrer les données de membre_ceintures vers user_ceintures
        if (Schema::hasTable('membre_ceintures') && Schema::hasTable('user_ceintures')) {
            $membreData = DB::table('membre_ceintures')->get();
            
            foreach ($membreData as $record) {
                // Récupérer l'école de l'utilisateur
                $user = DB::table('users')->where('id', $record->user_id)->first();
                
                $insertData = [
                    'user_id' => $record->user_id,
                    'ceinture_id' => $record->ceinture_id,
                    'date_obtention' => $record->date_obtention,
                    'valide' => $record->valide ?? true,
                    'notes' => $record->notes ?? null,
                    'ecole_id' => $user->ecole_id ?? 1, // Fallback à l'école 1 si pas d'école
                    'created_at' => $record->created_at ?? now(),
                    'updated_at' => $record->updated_at ?? now(),
                ];
                
                // Insérer dans user_ceintures
                DB::table('user_ceintures')->insert($insertData);
                
                echo "Migré: " . $record->user_id . " -> " . $record->ceinture_id . "\n";
            }
            
            echo "Migration terminée: " . count($membreData) . " enregistrement(s)\n";
        }
        
        // Étape 2: Supprimer l'ancienne table
        Schema::dropIfExists('membre_ceintures');
        
        // Étape 3: S'assurer que user_ceintures a toutes les colonnes nécessaires
        Schema::table('user_ceintures', function (Blueprint $table) {
            if (!Schema::hasColumn('user_ceintures', 'instructeur_id')) {
                $table->foreignId('instructeur_id')->nullable()->constrained('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('user_ceintures', 'examen_id')) {
                $table->string('examen_id')->nullable();
            }
        });
        
        // Étape 4: Ajouter les index manquants
        try {
            Schema::table('user_ceintures', function (Blueprint $table) {
                $table->index('examen_id');
            });
        } catch (Exception $e) {
            // Index existe déjà
        }
        
        try {
            Schema::table('user_ceintures', function (Blueprint $table) {
                $table->index(['ecole_id', 'date_obtention']);
            });
        } catch (Exception $e) {
            // Index existe déjà
        }
        
        try {
            Schema::table('user_ceintures', function (Blueprint $table) {
                $table->index(['user_id', 'date_obtention']);
            });
        } catch (Exception $e) {
            // Index existe déjà
        }
    }

    public function down(): void
    {
        // Recréer membre_ceintures si nécessaire
        Schema::create('membre_ceintures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('ceinture_id')->constrained('ceintures')->onDelete('cascade');
            $table->date('date_obtention');
            $table->boolean('valide')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
        
        // Migrer les données en retour
        $userData = DB::table('user_ceintures')->get();
        foreach ($userData as $record) {
            DB::table('membre_ceintures')->insert([
                'user_id' => $record->user_id,
                'ceinture_id' => $record->ceinture_id,
                'date_obtention' => $record->date_obtention,
                'valide' => $record->valide,
                'notes' => $record->notes,
                'created_at' => $record->created_at,
                'updated_at' => $record->updated_at,
            ]);
        }
    }
};
