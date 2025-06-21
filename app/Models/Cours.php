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
        'instructeur_principal_id',
        'instructeur_assistant_id',
        'capacite_max',
        'age_min',
        'age_max',
        'prix_session',
        'duree_session_semaines',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }

    // Relations
    public function ecole(): BelongsTo
    {
        return $this->belongsTo(Ecole::class);
    }

    public function instructeur_principal(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructeur_principal_id');
    }

    public function instructeur_assistant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructeur_assistant_id');
    }

    public function horaires(): HasMany
    {
        return $this->hasMany(CoursHoraire::class);
    }

    public function inscriptions(): HasMany
    {
        return $this->hasMany(InscriptionCours::class);
    }

    public function presences(): HasMany
    {
        return $this->hasMany(Presence::class);
    }
}
