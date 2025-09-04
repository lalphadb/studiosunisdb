<?php

namespace App\Services;

use App\Models\Cours;
use App\Models\Membre;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

/**
 * CourseService
 * Rassemble la logique business du module Cours
 * (création, mise à jour, duplication, stats, planning).
 */
class CourseService
{
    /**
     * Créer un cours.
     */
    public function create(array $data): Cours
    { return DB::transaction(fn()=> Cours::create($data)); }

    /** Mise à jour */
    public function update(Cours $cours, array $data): Cours
    {
        DB::transaction(function() use ($cours, $data) { $cours->update($data); });
        return $cours->refresh();
    }

    /** Suppression (soft ou force) */
    public function delete(Cours $cours, bool $force = false): void
    {
        DB::transaction(function() use ($cours, $force) {
            if ($force) { $cours->forceDelete(); } else { $cours->delete(); }
        });
    }

    /** Duplication simple */
    public function duplicate(Cours $cours): Cours
    { return DB::transaction(function() use ($cours){ $copy=$cours->replicate(); $copy->nom=$cours->nom.' (Copie)'; $copy->actif=false; $copy->save(); return $copy; }); }

    /** Duplication pour autre jour */
    public function duplicateForDay(Cours $cours, string $jour): Cours
    { return DB::transaction(function() use ($cours,$jour){ $n=$cours->replicate(); $n->jour_semaine=$jour; $n->nom=$cours->nom.' ('.ucfirst($jour).')'; $n->actif=false; $n->save(); return $n; }); }

    /** Vérifie conflit horaire instructeur */
    public function hasScheduleConflict(string $jour, string $debut, string $fin, int $instructeurId, ?int $excludeId=null): bool
    {
        $q = Cours::where('jour_semaine',$jour)
            ->where('instructeur_id',$instructeurId)
            ->where('actif',true)
            ->where(function($q) use ($debut,$fin){
                $q->whereBetween('heure_debut',[$debut,$fin])
                  ->orWhereBetween('heure_fin',[$debut,$fin])
                  ->orWhere(function($q2) use ($debut,$fin){ $q2->where('heure_debut','<=',$debut)->where('heure_fin','>=',$fin); });
            });
        if($excludeId) $q->where('id','!=',$excludeId);
        return $q->exists();
    }

    /** Statistiques simplifiées */
    public function stats(Cours $cours): array
    {
        $inscrits = $cours->membres()->count();
        $places = max(0,$cours->places_max - $inscrits);
        return [
            'totalInscrits'=>$inscrits,
            'placesDisponibles'=>$places,
            'tauxRemplissage'=>$cours->places_max>0? ($inscrits/$cours->places_max)*100:0,
        ];
    }
}
