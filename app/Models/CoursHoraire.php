<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CoursHoraire extends Model
{
    use HasFactory;

    protected $fillable = [
        'cours_id',
        'jour_semaine',
        'heure_debut',
        'heure_fin',
        'date_debut',
        'date_fin',
        'recurrence',
        'actif'
    ];

    protected $casts = [
        'heure_debut' => 'datetime:H:i',
        'heure_fin' => 'datetime:H:i',
        'date_debut' => 'date',
        'date_fin' => 'date',
        'actif' => 'boolean',
    ];

    // Relations
    public function cours()
    {
        return $this->belongsTo(Cours::class);
    }

    // Scopes
    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }

    public function scopeParJour($query, $jour)
    {
        return $query->where('jour_semaine', $jour);
    }

    // Attributs calculés
    public function getDureeAttribute()
    {
        return $this->heure_debut->diffInMinutes($this->heure_fin);
    }

    // Méthodes utiles
    public function estEnCours($date = null)
    {
        $date = $date ?? now();
        
        return $this->actif && 
               (!$this->date_debut || $date->gte($this->date_debut)) &&
               (!$this->date_fin || $date->lte($this->date_fin));
    }
}
