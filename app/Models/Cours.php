<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cours extends Model
{
    use HasFactory;

    protected $table = 'cours';

    protected $fillable = [
        'nom',
        'description',
        'niveau',
        'ecole_id',
        'capacite_max_defaut',
        'prix_defaut',
        'instructeur_defaut',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean',
        'capacite_max_defaut' => 'integer',
        'prix_defaut' => 'decimal:2'
    ];

    /**
     * Relations
     */
    public function ecole(): BelongsTo
    {
        return $this->belongsTo(Ecole::class);
    }

    public function coursHoraires(): HasMany
    {
        return $this->hasMany(CoursHoraire::class, 'cours_id');
    }

    public function inscriptionsCours(): HasMany
    {
        return $this->hasMany(InscriptionCours::class, 'cours_id');
    }

    /**
     * Scopes
     */
    public function scopeActifs($query)
    {
        return $query->where('active', true);
    }

    public function scopePourEcole($query, $ecoleId)
    {
        return $query->where('ecole_id', $ecoleId);
    }

    /**
     * Accesseurs
     */
    public function getStatutAttribute(): string
    {
        return $this->active ? 'actif' : 'inactif';
    }

    public function getNombreHorairesAttribute(): int
    {
        return $this->coursHoraires()->count();
    }

    public function getSessionsUtiliseesAttribute()
    {
        return $this->coursHoraires()
            ->with('session')
            ->get()
            ->pluck('session')
            ->unique('id')
            ->filter();
    }

    /**
     * Méthodes métier
     */
    public function peutEtreModifie(): bool
    {
        // Un cours peut être modifié s'il n'a pas d'inscriptions actives
        return $this->inscriptionsCours()
            ->where('statut', 'active')
            ->count() === 0;
    }

    public function peutEtreSupprime(): bool
    {
        return $this->coursHoraires()->count() === 0 && 
               $this->inscriptionsCours()->count() === 0;
    }

    public function dupliquerVersSession($sessionId, array $options = []): int
    {
        $session = \App\Models\SessionCours::findOrFail($sessionId);
        
        if ($session->ecole_id !== $this->ecole_id) {
            throw new \Exception('Session appartient à une autre école');
        }

        $horairesDupliques = 0;
        
        foreach ($this->coursHoraires as $horaire) {
            // Éviter les doublons
            $exists = CoursHoraire::where('cours_id', $this->id)
                ->where('session_id', $sessionId)
                ->where('jour_semaine', $horaire->jour_semaine)
                ->where('heure_debut', $horaire->heure_debut)
                ->exists();

            if (!$exists) {
                CoursHoraire::create([
                    'cours_id' => $this->id,
                    'session_id' => $sessionId,
                    'ecole_id' => $this->ecole_id,
                    'jour_semaine' => $horaire->jour_semaine,
                    'heure_debut' => $horaire->heure_debut,
                    'heure_fin' => $horaire->heure_fin,
                    'nom_affiche' => $horaire->nom_affiche,
                    'salle' => $horaire->salle,
                    'instructeur_affecte' => $horaire->instructeur_affecte,
                    'capacite_max' => $horaire->capacite_max,
                    'prix' => $options['ajuster_prix'] ?? false ? 
                        $horaire->prix * (1 + ($options['pourcentage'] ?? 0) / 100) : 
                        $horaire->prix,
                    'actif' => true
                ]);
                $horairesDupliques++;
            }
        }

        return $horairesDupliques;
    }
}
