<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\MultiTenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InscriptionSeminaire extends Model
{
    use HasFactory;
    use MultiTenant;

    protected $table = 'inscriptions_seminaires';

    protected $fillable = [
        'user_id',
        'ecole_id',
        'seminaire_id',
        'date_inscription',
        'statut',
        'notes'
    ];

    protected $casts = [
        'date_inscription' => 'date',
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
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ecole(): BelongsTo
    {
        return $this->belongsTo(Ecole::class);
    }

    public function seminaire(): BelongsTo
    {
        return $this->belongsTo(Seminaire::class);
    }

    // Scopes
    public function scopeInscrits(Builder $query): Builder
    {
        return $query->where('statut', 'inscrit');
    }

    public function scopeConfirmes(Builder $query): Builder
    {
        return $query->where('statut', 'confirme');
    }

    public function scopePresents(Builder $query): Builder
    {
        return $query->where('statut', 'present');
    }

    public function scopeAnnules(Builder $query): Builder
    {
        return $query->where('statut', 'annule');
    }

    // Accessors
    public function getStatutLibelleAttribute(): string
    {
        $statuts = [
            'inscrit' => 'Inscrit',
            'confirme' => 'Confirmé',
            'present' => 'Présent',
            'absent' => 'Absent',
            'annule' => 'Annulé'
        ];

        return $statuts[$this->statut] ?? $this->statut;
    }

    public function getStatutClassAttribute(): string
    {
        return match($this->statut) {
            'present' => 'text-emerald-400',
            'confirme' => 'text-blue-400',
            'inscrit' => 'text-amber-400',
            'absent' => 'text-red-400',
            'annule' => 'text-slate-400',
            default => 'text-slate-400'
        };
    }
}
