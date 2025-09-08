<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('cours')) {
            Schema::table('cours', function (Blueprint $table) {
                // Supprimer doublons avant index
                $duplicates = DB::table('cours')
                    ->select('nom', 'jour_semaine', 'heure_debut', 'heure_fin', DB::raw('COUNT(*) c'))
                    ->groupBy('nom', 'jour_semaine', 'heure_debut', 'heure_fin')
                    ->having('c', '>', 1)->get();
                foreach ($duplicates as $dup) {
                    $ids = DB::table('cours')
                        ->where('nom', $dup->nom)
                        ->where('jour_semaine', $dup->jour_semaine)
                        ->where('heure_debut', $dup->heure_debut)
                        ->where('heure_fin', $dup->heure_fin)
                        ->orderBy('id')
                        ->pluck('id')->toArray();
                    // garder le premier
                    array_shift($ids);
                    if ($ids) {
                        DB::table('cours')->whereIn('id', $ids)->delete();
                    }
                }
                // Créer index unique si pas déjà là
                try {
                    $table->unique(['nom', 'jour_semaine', 'heure_debut', 'heure_fin'], 'cours_unique_planning');
                } catch (\Throwable $e) {
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('cours')) {
            Schema::table('cours', function (Blueprint $table) {
                try {
                    $table->dropUnique('cours_unique_planning');
                } catch (\Throwable $e) {
                }
            });
        }
    }
};
