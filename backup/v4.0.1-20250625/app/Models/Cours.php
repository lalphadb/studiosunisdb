<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cours extends Model
{
    use HasFactory;

    protected $fillable = [
        'ecole_id',
        'nom',
        'description',
        'niveau',
        'capacite_max',
        'prix',
        'duree_minutes',
        'instructeur',
        'active'
    ];

    protected $casts = [
        'prix' => 'decimal:2',
        'active' => 'boolean'
    ];

    public function ecole()
    {
        return $this->belongsTo(Ecole::class);
    }

    public function horaires()
    {
        return $this->hasMany(CoursHoraire::class);
    }

    public function inscriptions()
    {
        return $this->hasMany(InscriptionCours::class);
    }

    public function presences()
    {
        return $this->hasMany(Presence::class);
    }
}
