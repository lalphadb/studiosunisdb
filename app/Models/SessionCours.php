<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\MultiTenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SessionCours extends Model
{
    use HasFactory;
    use MultiTenant;

    protected $table = 'sessions_cours';

    protected $fillable = [
        'ecole_id',
        'nom',
        'date_debut',
        'date_fin',
        'actif',
        'inscriptions_ouvertes',
        'date_limite_inscription',
        'description',
        'ordre'
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'date_limite_inscription' => 'date',
        'actif' => 'boolean',
        'inscriptions_ouvertes' => 'boolean',
        'ordre' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected static function booted(): void
    {
        parent::booted();
        
        // Global scope multi-tenant
        static::addGlobalScope('ecole', function (Builder $builder) {
            if (auth()->check() && !auth()->user()->hasRole('superadmin')) {
                $builder->where('ecole_id', auth()->user()->ecole_id);
            }
        });
    }

    // Relations
    public function ecole(): BelongsTo
    {
        return $this->belongsTo(Ecole::class);
    }

    public function coursHoraires(): HasMany
    {
        return $this->hasMany(CoursHoraire::class, 'session_id');
    }

    public function inscriptions(): HasMany
    {
        return $this->hasMany(InscriptionCours::class, 'session_id');
    }

    // Scopes
    public function scopeActives(Builder $query): Builder
    {
        return $query->where('actif', true);
    }

    public function scopeInscriptionsOuvertes(Builder $query): Builder
    {
        return $query->where('inscriptions_ouvertes', true);
    }

    public function scopeEnCours(Builder $query): Builder
    {
        return $query->where('date_debut', '<=', now())
                    ->where('date_fin', '>=', now());
    }

    public function scopeAVenir(Builder $query): Builder
    {
        return $query->where('date_debut', '>', now());
    }

    public function scopeTerminees(Builder $query): Builder
    {
        return $query->where('date_fin', '<', now());
    }

    // Accessors
    public function getStatutAttribute(): string
    {
        if (!$this->actif) return 'Inactive';
        
        if ($this->date_fin < now()) return 'Terminée';
        if ($this->date_debut > now()) return 'À venir';
        
        return 'En cours';
    }

    public function getStatutClassAttribute(): string
    {
        return match($this->statut) {
            'En cours' => 'text-emerald-400',
            'À venir' => 'text-blue-400',
            'Terminée' => 'text-slate-400',
            default => 'text-red-400'
        };
    }

    public function getDureeAttribute(): string
    {
        return 'Du ' . $this->date_debut->format('d/m/Y') . 
               ' au ' . $this->date_fin->format('d/m/Y');
    }

    public function getNombreInscriptsAttribute(): int
    {
        return $this->inscriptions()->count();
    }

    public function getNombreCoursAttribute(): int
    {
        return $this->coursHoraires()->count();
    }
}
