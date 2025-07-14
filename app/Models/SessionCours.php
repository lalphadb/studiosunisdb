<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class SessionCours extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'session_cours';

    protected $fillable = [
        'cours_id',
        'date',
        'heure_debut', 
        'heure_fin',
        'instructeur_id',
        'salle',
        'statut',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'heure_debut' => 'datetime:H:i',
        'heure_fin' => 'datetime:H:i',
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

    public function presences(): HasMany
    {
        return $this->hasMany(Presence::class);
    }

    // Accessors
    public function getStatutColorAttribute(): string
    {
        return match($this->statut) {
            'planifie' => 'gray',
            'en_cours' => 'warning',
            'complete' => 'success',
            'annule' => 'danger',
            default => 'gray'
        };
    }

    public function getStatutIconAttribute(): string
    {
        return match($this->statut) {
            'planifie' => 'heroicon-o-clock',
            'en_cours' => 'heroicon-o-play',
            'complete' => 'heroicon-o-check-circle',
            'annule' => 'heroicon-o-x-circle',
            default => 'heroicon-o-question-mark-circle'
        };
    }

    public function getPresentsCountAttribute(): int
    {
        return $this->presences()->where('status', 'present')->count();
    }

    public function getAbsentsCountAttribute(): int
    {
        return $this->presences()->where('status', 'absent')->count();
    }

    // Scopes
    public function scopeFutures($query)
    {
        return $query->where('date', '>=', Carbon::today());
    }

    public function scopePassees($query) 
    {
        return $query->where('date', '<', Carbon::today());
    }

    public function scopeAujourdhui($query)
    {
        return $query->whereDate('date', Carbon::today());
    }
}
