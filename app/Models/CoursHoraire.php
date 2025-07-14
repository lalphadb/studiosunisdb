<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'instructeur_id',
        'actif',
    ];

    protected $casts = [
        'jour_semaine' => 'integer',
        'heure_debut' => 'datetime:H:i',
        'heure_fin' => 'datetime:H:i',
        'actif' => 'boolean',
    ];

    // Relations
    public function cours(): BelongsTo
    {
        return $this->belongsTo(Cours::class);
    }

    public function instructeur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructeur_id');
    }

    // Accessors
    public function getJourNomAttribute(): string
    {
        return match($this->jour_semaine) {
            1 => 'Lundi',
            2 => 'Mardi', 
            3 => 'Mercredi',
            4 => 'Jeudi',
            5 => 'Vendredi',
            6 => 'Samedi',
            7 => 'Dimanche',
            default => 'Inconnu'
        };
    }

    public function getJourEmojiAttribute(): string
    {
        return match($this->jour_semaine) {
            1 => '📅',
            2 => '📅',
            3 => '📅', 
            4 => '📅',
            5 => '📅',
            6 => '🎯',
            7 => '🎯',
            default => '📅'
        };
    }

    public function getHoraireFormatAttribute(): string
    {
        return $this->heure_debut->format('H:i') . ' - ' . $this->heure_fin->format('H:i');
    }
}
