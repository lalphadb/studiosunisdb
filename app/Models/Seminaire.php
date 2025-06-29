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
        'lieu',
        'instructeur',
        'niveau_requis',
        'max_participants',
        'cout',
        'statut',
        'inscription_ouverte',
        'certificat',
        'objectifs',
        'prerequis',
        'duree'
    ];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
        'inscription_ouverte' => 'boolean',
        'certificat' => 'boolean',
        'cout' => 'decimal:2'
    ];

    /**
     * RELATION MANQUANTE - École organisatrice
     */
    public function ecole(): BelongsTo
    {
        return $this->belongsTo(Ecole::class);
    }

    /**
     * Inscriptions au séminaire
     */
    public function inscriptions(): HasMany
    {
        return $this->hasMany(InscriptionSeminaire::class);
    }

    /**
     * Accesseurs pour affichage
     */
    public function getTypeTextAttribute(): string
    {
        return match($this->type) {
            'technique' => 'Technique',
            'kata' => 'Kata',
            'competition' => 'Compétition',
            'arbitrage' => 'Arbitrage',
            'grade' => 'Passage de Grade',
            'formation' => 'Formation',
            default => ucfirst($this->type)
        };
    }

    public function getNiveauRequisTextAttribute(): string
    {
        return match($this->niveau_requis) {
            'debutant' => 'Débutant',
            'intermediaire' => 'Intermédiaire',
            'avance' => 'Avancé',
            'tous_niveaux' => 'Tous niveaux',
            default => ucfirst($this->niveau_requis)
        };
    }

    public function getStatutTextAttribute(): string
    {
        return match($this->statut) {
            'planifie' => 'Planifié',
            'ouvert' => 'Ouvert',
            'complet' => 'Complet',
            'termine' => 'Terminé',
            'annule' => 'Annulé',
            default => ucfirst($this->statut)
        };
    }

    public function getStatutBadgeAttribute(): string
    {
        return match($this->statut) {
            'planifie' => 'bg-gray-600 text-white',
            'ouvert' => 'bg-green-600 text-white',
            'complet' => 'bg-yellow-600 text-white',
            'termine' => 'bg-blue-600 text-white',
            'annule' => 'bg-red-600 text-white',
            default => 'bg-gray-600 text-white'
        };
    }

    public function getDateDebutFormateeAttribute(): string
    {
        return $this->date_debut ? $this->date_debut->format('d/m/Y') : '';
    }

    public function getHeureDebutFormateeAttribute(): string
    {
        return $this->date_debut ? $this->date_debut->format('H:i') : '';
    }

    public function getHeureFinFormateeAttribute(): string
    {
        return $this->date_fin ? $this->date_fin->format('H:i') : '';
    }

    /**
     * Scopes pour filtrage
     */
    public function scopeForEcole($query, $ecoleId)
    {
        return $query->where('ecole_id', $ecoleId);
    }

    public function scopeOuvert($query)
    {
        return $query->where('statut', 'ouvert')->where('inscription_ouverte', true);
    }

    public function scopeAvecInscriptions($query)
    {
        return $query->with(['inscriptions.user', 'ecole']);
    }
}
