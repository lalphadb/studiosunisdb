<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class SessionCours extends Model
{
    use HasFactory;

    protected $table = 'sessions_cours';

    protected $fillable = [
        'nom',
        'description',
        'date_debut',
        'date_fin',
        'ecole_id',
        'actif',
        'inscriptions_ouvertes',
        'date_limite_inscription'
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'date_limite_inscription' => 'date',
        'actif' => 'boolean',
        'inscriptions_ouvertes' => 'boolean'
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
        return $this->hasMany(CoursHoraire::class, 'session_id');
    }

    public function inscriptionsHistorique(): HasMany
    {
        return $this->hasMany(InscriptionHistorique::class, 'session_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(SessionNotification::class, 'session_id');
    }

    /**
     * Scopes
     */
    public function scopeActives($query)
    {
        return $query->where('actif', true);
    }

    public function scopePourEcole($query, $ecoleId)
    {
        return $query->where('ecole_id', $ecoleId);
    }

    public function scopeInscriptionsOuvertes($query)
    {
        return $query->where('inscriptions_ouvertes', true)
                    ->where('date_limite_inscription', '>=', now());
    }

    public function scopeProcheFermeture($query, $jours = 3)
    {
        return $query->where('inscriptions_ouvertes', true)
                    ->whereBetween('date_limite_inscription', [
                        now(),
                        now()->addDays($jours)
                    ]);
    }

    /**
     * Accesseurs
     */
    public function getStatutAttribute(): string
    {
        if (!$this->actif) return 'inactive';
        if ($this->date_fin < now()) return 'terminee';
        if ($this->date_debut > now()) return 'future';
        return 'en_cours';
    }

    public function getInscriptionsDisponiblesAttribute(): bool
    {
        return $this->inscriptions_ouvertes && 
               $this->date_limite_inscription >= now();
    }

    /**
     * Méthodes métier
     */
    public function peutEtreDupliquee(): bool
    {
        return $this->coursHoraires()->count() > 0;
    }

    public function dupliquerVers(array $donnees): self
    {
        $nouvelleSession = static::create([
            'nom' => $donnees['nom'],
            'description' => $donnees['description'] ?? $this->description,
            'date_debut' => $donnees['date_debut'],
            'date_fin' => $donnees['date_fin'],
            'ecole_id' => $this->ecole_id,
            'actif' => false, // Inactive par défaut
            'inscriptions_ouvertes' => false,
            'date_limite_inscription' => $donnees['date_limite_inscription'] ?? null
        ]);

        // Dupliquer tous les horaires
        foreach ($this->coursHoraires as $horaire) {
            $nouvelleSession->coursHoraires()->create([
                'cours_id' => $horaire->cours_id,
                'ecole_id' => $this->ecole_id,
                'jour_semaine' => $horaire->jour_semaine,
                'heure_debut' => $horaire->heure_debut,
                'heure_fin' => $horaire->heure_fin,
                'nom_affiche' => $horaire->nom_affiche,
                'salle' => $horaire->salle,
                'instructeur_affecte' => $horaire->instructeur_affecte,
                'capacite_max' => $horaire->capacite_max,
                'prix' => $donnees['ajuster_prix'] ?? false ? 
                    $horaire->prix * (1 + ($donnees['pourcentage_augmentation'] ?? 0) / 100) : 
                    $horaire->prix,
                'actif' => true
            ]);
        }

        return $nouvelleSession;
    }

    public function ouvrirInscriptions(?Carbon $dateLimite = null): void
    {
        $this->update([
            'inscriptions_ouvertes' => true,
            'date_limite_inscription' => $dateLimite ?? $this->date_debut->subWeek()
        ]);

        // Déclencher les notifications
        event(new \App\Events\SessionInscriptionsOuvertes($this));
    }

    public function fermerInscriptions(): void
    {
        $this->update(['inscriptions_ouvertes' => false]);
    }

    public function getMembresAvecHistorique()
    {
        return User::whereHas('inscriptionsHistorique', function ($query) {
            $query->where('ecole_id', $this->ecole_id);
        })->with(['inscriptionsHistorique' => function ($query) {
            $query->where('ecole_id', $this->ecole_id)
                  ->latest('date_inscription')
                  ->limit(1);
        }]);
    }
}
