<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CoursResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'=>$this->id,
            'nom'=>$this->nom,
            // slug removed
            'niveau'=>$this->niveau,
            'age_min'=>$this->age_min,
            'age_max'=>$this->age_max,
            'places_max'=>$this->places_max,
            // places_reservees removed
            'jour_semaine'=>$this->jour_semaine,
            'heure_debut'=>$this->heure_debut,
            'heure_fin'=>$this->heure_fin,
            'instructeur'=> $this->whenLoaded('instructeur', function(){ return ['id'=>$this->instructeur->id,'name'=>$this->instructeur->name]; }),
            'actif'=>$this->actif,
            // inscriptions_ouvertes removed
            'places_disponibles'=>$this->places_disponibles,
            'taux_remplissage'=>$this->taux_remplissage,
        ];
    }
}
