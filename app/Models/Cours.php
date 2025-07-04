<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

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
        'active',
        'duree_minutes',
        'instructeur',
        'capacite_max',
        'prix'
    ];

    protected $casts = [
        'active' => 'boolean',
        'capacite_max_defaut' => 'integer',
        'prix_defaut' => 'decimal:2',
        'duree_minutes' => 'integer',
        'capacite_max' => 'integer',
        'prix' => 'decimal:2'
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

    // Alias pour compatibilité
    public function inscriptions(): HasMany
    {
        return $this->inscriptionsCours();
    }

    /**
     * Boot method pour Global Scope Multi-tenant
     */
    protected static function booted(): void
    {
        static::addGlobalScope('ecole', function (Builder $builder) {
            if (auth()->check() && !auth()->user()->hasRole('super_admin')) {
                $builder->where('ecole_id', auth()->user()->ecole_id);
            }
        });
    }

    /**
     * Scopes
     */
    public function scopeActifs(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    public function scopePourEcole(Builder $query, int $ecoleId): Builder
    {
        return $query->where('ecole_id', $ecoleId);
    }

    public function scopeAvecHoraires(Builder $query): Builder
    {
        return $query->with(['coursHoraires' => function($q) {
            $q->where('actif', true);
        }]);
    }

    public function scopeAvecInscriptions(Builder $query): Builder
    {
        return $query->withCount(['inscriptions' => function($q) {
            $q->where('statut', 'active');
        }]);
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

    public function getNombreInscriptionsAttribute(): int
    {
        return $this->inscriptions()->where('statut', 'active')->count();
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

    public function getCapaciteTotaleAttribute(): int
    {
        return $this->coursHoraires()->sum('capacite_max') ?: 0;
    }

    public function getPlacesDisponiblesAttribute(): int
    {
        return max(0, $this->capacite_totale - $this->nombre_inscriptions);
    }

    /**
     * Méthodes métier
     */
    public function peutEtreModifie(): bool
    {
        return $this->inscriptions()
            ->where('statut', 'active')
            ->count() === 0;
    }

    public function peutEtreSupprime(): bool
    {
        return $this->coursHoraires()->count() === 0 && 
               $this->inscriptions()->count() === 0;
    }

    public function dupliquerVersSession(int $sessionId, array $options = []): int
    {
        $session = SessionCours::findOrFail($sessionId);
        
        if ($session->ecole_id !== $this->ecole_id) {
            throw new \Exception('Session appartient à une autre école');
        }

        $horairesDupliques = 0;
        
        foreach ($this->coursHoraires as $horaire) {
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

    public function calculerStatistiques(): array
    {
        return [
            'total_horaires' => $this->nombre_horaires,
            'total_inscriptions' => $this->nombre_inscriptions,
            'capacite_totale' => $this->capacite_totale,
            'places_disponibles' => $this->places_disponibles,
            'taux_occupation' => $this->capacite_totale > 0 ? 
                round(($this->nombre_inscriptions / $this->capacite_totale) * 100, 1) : 0,
            'sessions_actives' => $this->sessions_utilisees->count(),
            'revenus_potentiels' => $this->coursHoraires()->sum('prix') * 4 // 4 semaines moyenne
        ];
    }
}
