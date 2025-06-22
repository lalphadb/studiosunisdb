<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'active',
    ];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'prix' => 'decimal:2',
        ];
    }

    // Relations
    public function ecole(): BelongsTo
    {
        return $this->belongsTo(Ecole::class);
    }

    public function horaires(): HasMany
    {
        return $this->hasMany(CoursHoraire::class);
    }

    public function inscriptions(): HasMany
    {
        return $this->hasMany(InscriptionCours::class);
    }

    // Méthodes utiles pour statistiques
    public function getPlacesDisponiblesAttribute()
    {
        $inscriptions_actives = $this->inscriptions()->count();
        return $this->capacite_max - $inscriptions_actives;
    }

    public function getStatutOccupationAttribute()
    {
        $inscriptions = $this->inscriptions()->count();
        return $inscriptions . '/' . $this->capacite_max;
    }
}
