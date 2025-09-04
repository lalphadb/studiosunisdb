<?php

namespace App\Services;

use App\Models\Cours;
use App\Models\Membre;
use Illuminate\Support\Facades\DB;

class EnrollmentService
{
    public function enroll(Cours $cours, Membre $membre): bool
    {
        if(!$cours->peutInscrire($membre)) return false;
        return DB::transaction(function() use ($cours,$membre){
            $cours->membres()->attach($membre->id,[ 'date_inscription'=>now(),'statut_inscription'=>'actif']);
            $cours->increment('places_reservees');
            return true;
        });
    }

    public function unenroll(Cours $cours, Membre $membre): bool
    {
        return DB::transaction(function() use ($cours,$membre){
            $cours->membres()->updateExistingPivot($membre->id,['statut_inscription'=>'termine']);
            $cours->decrement('places_reservees');
            return true;
        });
    }
}
