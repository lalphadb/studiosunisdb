<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Seminaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'ecole_id',
        'titre',
        'description',
        'type',
        'date_debut',
        'date_fin',
        'heure_debut',
        'heure_fin',
        'instructeur',
        'capacite_max',
        'prix',
        'niveau_requis',
        'inscription_ouverte',
        'materiel_requis',
    ];

    protected function casts(): array
    {
        return [
            'date_debut' => 'date',
            'date_fin' => 'date',
            'heure_debut' => 'datetime:H:i',
            'heure_fin' => 'datetime:H:i',
            'prix' => 'decimal:2',
            'inscription_ouverte' => 'boolean',
        ];
    }

    // Relations
    public function ecole(): BelongsTo
    {
        return $this->belongsTo(Ecole::class);
    }

    public function inscriptions(): HasMany
    {
        return $this->hasMany(InscriptionSeminaire::class);
    }
}
