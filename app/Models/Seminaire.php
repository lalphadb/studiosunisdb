<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\MultiTenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Seminaire extends Model
{
    use HasFactory;
    use MultiTenant;

    protected $fillable = [
        'ecole_id',
        'processed_by_user_id',
        'titre',
        'description',
        'type',
        'date_debut',
        'date_fin',
        'heure_debut',
        'heure_fin',
        'lieu',
        'adresse_lieu',
        'ville_lieu',
        'instructeur',
        'prix',
        'niveau_requis',
        'inscription_ouverte',
        'statut',
        'certificat',
        'materiel_requis',
        'objectifs',
        'prerequis',
        'notes_admin'
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'heure_debut' => 'datetime:H:i',
        'heure_fin' => 'datetime:H:i',
        'prix' => 'decimal:2',
        'inscription_ouverte' => 'boolean',
        'certificat' => 'boolean',
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

    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by_user_id');
    }

    public function inscriptions(): HasMany
    {
        return $this->hasMany(InscriptionSeminaire::class);
    }

    // Scopes
    public function scopeOuverts(Builder $query): Builder
    {
        return $query->where('statut', 'ouvert')
                    ->where('inscription_ouverte', true);
    }

    public function scopeAVenir(Builder $query): Builder
    {
        return $query->where('date_debut', '>', now());
    }

    public function scopeEnCours(Builder $query): Builder
    {
        return $query->where('date_debut', '<=', now())
                    ->where('date_fin', '>=', now());
    }

    public function scopeTermines(Builder $query): Builder
    {
        return $query->where('date_fin', '<', now());
    }

    public function scopeForType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    public function scopeForNiveau(Builder $query, string $niveau): Builder
    {
        return $query->where('niveau_requis', $niveau);
    }

    // Accessors
    public function getTypeLibelleAttribute(): string
    {
        $types = [
            'technique' => 'Technique',
            'kata' => 'Kata',
            'competition' => 'Compétition',
            'arbitrage' => 'Arbitrage'
        ];

        return $types[$this->type] ?? $this->type;
    }

    public function getNiveauLibelleAttribute(): string
    {
        $niveaux = [
            'debutant' => 'Débutant',
            'intermediaire' => 'Intermédiaire',
            'avance' => 'Avancé',
            'tous_niveaux' => 'Tous niveaux'
        ];

        return $niveaux[$this->niveau_requis] ?? $this->niveau_requis;
    }

    public function getStatutLibelleAttribute(): string
    {
        $statuts = [
            'planifie' => 'Planifié',
            'ouvert' => 'Ouvert',
            'complet' => 'Complet',
            'termine' => 'Terminé',
            'annule' => 'Annulé'
        ];

        return $statuts[$this->statut] ?? $this->statut;
    }

    public function getStatutClassAttribute(): string
    {
        return match($this->statut) {
            'ouvert' => 'text-emerald-400',
            'complet' => 'text-amber-400',
            'termine' => 'text-blue-400',
            'annule' => 'text-red-400',
            default => 'text-slate-400'
        };
    }

    public function getDureeAttribute(): string
    {
        if ($this->date_debut->isSameDay($this->date_fin)) {
            return $this->date_debut->format('d/m/Y') . 
                   ' de ' . $this->heure_debut->format('H:i') . 
                   ' à ' . $this->heure_fin->format('H:i');
        }

        return 'Du ' . $this->date_debut->format('d/m/Y') . 
               ' au ' . $this->date_fin->format('d/m/Y');
    }

    public function getNombreInscriptsAttribute(): int
    {
        return $this->inscriptions()->count();
    }
}
