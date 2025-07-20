<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cours extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'description',
        'instructeur_id',
        'niveau',
        'age_min',
        'age_max',
        'places_max',
        'jour_semaine',
        'heure_debut',
        'heure_fin',
        'date_debut',
        'date_fin',
        'tarif_mensuel',
        'actif',
    ];

    protected $casts = [
        'heure_debut' => 'datetime:H:i',
        'heure_fin' => 'datetime:H:i',
        'date_debut' => 'date',
        'date_fin' => 'date',
        'tarif_mensuel' => 'decimal:2',
        'actif' => 'boolean',
        'age_min' => 'integer',
        'age_max' => 'integer',
        'places_max' => 'integer',
    ];

    // Relations
    public function instructeur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructeur_id');
    }

    public function membres(): BelongsToMany
    {
        return $this->belongsToMany(Membre::class, 'cours_membres')
                   ->withPivot(['date_inscription', 'date_fin', 'statut'])
                   ->withTimestamps();
    }

    public function presences(): HasMany
    {
        return $this->hasMany(Presence::class);
    }

    // Scopes
    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }

    public function scopeParJour($query, $jour)
    {
        return $query->where('jour_semaine', $jour);
    }

    public function scopeParInstructeur($query, $instructeurId)
    {
        return $query->where('instructeur_id', $instructeurId);
    }

    public function scopeEnCours($query)
    {
        return $query->where('date_debut', '<=', now())
                    ->where(function($q) {
                        $q->whereNull('date_fin')
                          ->orWhere('date_fin', '>=', now());
                    });
    }

    // Accessors
    public function getPlacesRestantesAttribute(): int
    {
        return $this->places_max - $this->membres()->count();
    }

    public function getDureeAttribute(): string
    {
        $debut = \Carbon\Carbon::parse($this->heure_debut);
        $fin = \Carbon\Carbon::parse($this->heure_fin);
        
        return $debut->diffInMinutes($fin) . ' min';
    }

    public function getHoraireCompletAttribute(): string
    {
        return ucfirst($this->jour_semaine) . ' ' . 
               $this->heure_debut->format('H:i') . '-' . 
               $this->heure_fin->format('H:i');
    }

    // Méthodes utilitaires
    public function peutInscrire(Membre $membre): bool
    {
        // Vérifier l'âge
        if ($membre->age < $this->age_min || $membre->age > $this->age_max) {
            return false;
        }

        // Vérifier les places disponibles
        if ($this->places_restantes <= 0) {
            return false;
        }

        // Vérifier si déjà inscrit
        if ($this->membres()->where('membre_id', $membre->id)->exists()) {
            return false;
        }

        return true;
    }

    public function inscrireMembre(Membre $membre): bool
    {
        if (!$this->peutInscrire($membre)) {
            return false;
        }

        $this->membres()->attach($membre->id, [
            'date_inscription' => now(),
            'statut' => 'actif'
        ]);

        return true;
    }
}
