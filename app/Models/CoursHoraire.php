<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\MultiTenant;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CoursHoraire extends Model
{
    use HasFactory;
    use MultiTenant;

    protected $table = 'cours_horaires';

    protected $fillable = [
        'cours_id',
        'session_id',
        'ecole_id',
        'jour_semaine',
        'heure_debut',
        'heure_fin',
        'nom_affiche',
        'salle',
        'instructeur_affecte',
        'capacite_max',
        'prix',
        'actif'
    ];

    protected $casts = [
        'heure_debut' => 'datetime:H:i',
        'heure_fin' => 'datetime:H:i',
        'capacite_max' => 'integer',
        'prix' => 'decimal:2',
        'actif' => 'boolean'
    ];

    /**
     * Relations
     */
    public function cours(): BelongsTo
    {
        return $this->belongsTo(Cours::class);
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(SessionCours::class, 'session_id');
    }

    public function ecole(): BelongsTo
    {
        return $this->belongsTo(Ecole::class);
    }

    public function inscriptionsCours(): HasMany
    {
        return $this->hasMany(InscriptionCours::class, 'cours_horaire_id');
    }

    /**
     * Scopes
     */
    public function scopeActifs($query)
    {
        return $query->where('actif', true);
    }

    public function scopePourSession($query, $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }

    public function scopePourJour($query, $jour)
    {
        return $query->where('jour_semaine', $jour);
    }

    public function scopePourEcole($query, $ecoleId)
    {
        return $query->where('ecole_id', $ecoleId);
    }

    /**
     * Accesseurs
     */
    public function getHeureDebutFormateeAttribute(): string
    {
        return $this->heure_debut->format('H:i');
    }

    public function getHeureFinFormateeAttribute(): string
    {
        return $this->heure_fin->format('H:i');
    }

    public function getCreneauCompletAttribute(): string
    {
        return $this->jour_semaine . ' ' . $this->heure_debut_formatee . '-' . $this->heure_fin_formatee;
    }

    public function getPlacesDisponiblesAttribute(): int
    {
        $inscriptions = $this->inscriptionsCours()->where('statut', 'active')->count();
        return max(0, $this->capacite_max - $inscriptions);
    }

    public function getEstCompletAttribute(): bool
    {
        return $this->places_disponibles === 0;
    }

    /**
     * Méthodes métier
     */
    public function peutAccueillirInscription(): bool
    {
        return $this->actif && 
               $this->session->inscriptions_disponibles && 
               $this->places_disponibles > 0;
    }

    public function dupliquerVers(array $donnees): self
    {
        return static::create([
            'cours_id' => $this->cours_id,
            'session_id' => $donnees['session_id'] ?? $this->session_id,
            'ecole_id' => $this->ecole_id,
            'jour_semaine' => $donnees['jour_semaine'] ?? $this->jour_semaine,
            'heure_debut' => $donnees['heure_debut'] ?? $this->heure_debut,
            'heure_fin' => $donnees['heure_fin'] ?? $this->heure_fin,
            'nom_affiche' => $donnees['nom_affiche'] ?? $this->nom_affiche,
            'salle' => $donnees['salle'] ?? $this->salle,
            'instructeur_affecte' => $donnees['instructeur_affecte'] ?? $this->instructeur_affecte,
            'capacite_max' => $donnees['capacite_max'] ?? $this->capacite_max,
            'prix' => $donnees['prix'] ?? $this->prix,
            'actif' => $donnees['actif'] ?? true
        ]);
    }

    public function calculerConflits()
    {
        return static::where('ecole_id', $this->ecole_id)
            ->where('session_id', $this->session_id)
            ->where('jour_semaine', $this->jour_semaine)
            ->where('id', '!=', $this->id)
            ->where(function ($query) {
                $query->whereBetween('heure_debut', [$this->heure_debut, $this->heure_fin])
                      ->orWhereBetween('heure_fin', [$this->heure_debut, $this->heure_fin])
                      ->orWhere(function ($q) {
                          $q->where('heure_debut', '<=', $this->heure_debut)
                            ->where('heure_fin', '>=', $this->heure_fin);
                      });
            })
            ->get();
    }
}
