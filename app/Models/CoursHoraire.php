<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasEcoleScope;

class CoursHoraire extends Model
{
    use HasFactory, HasEcoleScope;

    protected $fillable = [
        'cours_id',
        'ecole_id',
        'jour',
        'heure_debut',
        'heure_fin',
        'salle',
        'instructeur_id',
        'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
        'jour' => 'integer',
        'heure_debut' => 'datetime:H:i',
        'heure_fin' => 'datetime:H:i',
    ];

    public function cours()
    {
        return $this->belongsTo(Cours::class);
    }

    public function ecole()
    {
        return $this->belongsTo(Ecole::class);
    }

    public function instructeur()
    {
        return $this->belongsTo(User::class, 'instructeur_id');
    }
}
