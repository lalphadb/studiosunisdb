<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoursHoraire extends Model
{
    use HasFactory;

    protected $table = 'cours_horaires';

    protected $fillable = [
        'cours_id',
        'jour_semaine',
        'heure_debut',
        'heure_fin',
        'salle',
        'est_actif'
    ];

    protected $casts = [
        'heure_debut' => 'datetime:H:i:s',
        'heure_fin' => 'datetime:H:i:s',
        'est_actif' => 'boolean'
    ];

    public function cours()
    {
        return $this->belongsTo(Cours::class);
    }

    public function scopeActif($query)
    {
        return $query->where('est_actif', true);
    }
}
