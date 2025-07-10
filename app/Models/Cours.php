<?php

namespace App\Models;

use App\Traits\HasEcoleScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cours extends Model
{
    use HasFactory, HasEcoleScope, SoftDeletes;

    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom',
        'code',
        'description',
        'type',
        'niveau',
        'duree_minutes',
        'capacite_max',
        'prix_mensuel',
        'prix_seance',
        'couleur',
        'image',
        'prerequis',
        'objectifs',
        'ecole_id',
        'actif',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'duree_minutes' => 'integer',
        'capacite_max' => 'integer',
        'prix_mensuel' => 'decimal:2',
        'prix_seance' => 'decimal:2',
        'actif' => 'boolean',
        'prerequis' => 'array',
        'objectifs' => 'array',
    ];

    /**
     * Les valeurs par défaut
     */
    protected $attributes = [
        'type' => 'regulier',
        'actif' => true,
        'duree_minutes' => 60,
    ];

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        // Générer automatiquement le code du cours
        static::creating(function ($cours) {
            if (empty($cours->code)) {
                $prefix = strtoupper(substr(str_replace(' ', '', $cours->nom), 0, 3));
                $cours->code = $prefix . '-' . str_pad(Cours::where('ecole_id', $cours->ecole_id)->count() + 1, 3, '0', STR_PAD_LEFT);
            }
        });
    }

    /**
     * Relation avec les horaires
     */
    public function horaires()
    {
        return $this->hasMany(CoursHoraire::class);
    }

    /**
     * Relation avec les sessions
     */
    public function sessions()
    {
        return $this->hasMany(SessionCours::class);
    }

    /**
     * Relation avec les inscriptions
     */
    public function inscriptions()
    {
        return $this->hasMany(InscriptionCours::class);
    }

    /**
     * Relation avec les utilisateurs (via inscriptions)
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'inscription_cours')
            ->withPivot('date_debut', 'date_fin', 'actif')
            ->withTimestamps();
    }

    /**
     * Relation avec les instructeurs
     */
    public function instructeurs()
    {
        return $this->belongsToMany(User::class, 'cours_instructeurs')
            ->withTimestamps();
    }

    /**
     * Relation avec les présences
     */
    public function presences()
    {
        return $this->hasManyThrough(Presence::class, SessionCours::class);
    }

    /**
     * Scope pour les cours actifs
     */
    public function scopeActifs($query)
    {
        return $query->where('actif', true);
    }

    /**
     * Scope pour les cours inactifs
     */
    public function scopeInactifs($query)
    {
        return $query->where('actif', false);
    }

    /**
     * Scope pour les cours par type
     */
    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope pour recherche
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('nom', 'like', "%{$search}%")
              ->orWhere('code', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    /**
     * Obtenir le nombre d'inscrits actifs
     */
    public function getNombreInscritsActifs()
    {
        return $this->inscriptions()
            ->where('actif', true)
            ->count();
    }

    /**
     * Vérifier si le cours est complet
     */
    public function estComplet()
    {
        if (!$this->capacite_max) {
            return false;
        }

        return $this->getNombreInscritsActifs() >= $this->capacite_max;
    }

    /**
     * Obtenir les places disponibles
     */
    public function getPlacesDisponibles()
    {
        if (!$this->capacite_max) {
            return null;
        }

        return max(0, $this->capacite_max - $this->getNombreInscritsActifs());
    }

    /**
     * Obtenir la prochaine session
     */
    public function getProchaineSession()
    {
        return $this->sessions()
            ->where('date', '>=', now())
            ->where('statut', '!=', 'annule')
            ->orderBy('date')
            ->orderBy('heure_debut')
            ->first();
    }

    /**
     * Obtenir les statistiques du cours
     */
    public function getStatistiques($dateDebut = null, $dateFin = null)
    {
        $query = $this->presences();

        if ($dateDebut) {
            $query->whereHas('sessionCours', function ($q) use ($dateDebut) {
                $q->where('date', '>=', $dateDebut);
            });
        }

        if ($dateFin) {
            $query->whereHas('sessionCours', function ($q) use ($dateFin) {
                $q->where('date', '<=', $dateFin);
            });
        }

        $total = $query->count();
        $presents = $query->where('status', 'present')->count();

        return [
            'total_presences' => $total,
            'presents' => $presents,
            'absents' => $query->where('status', 'absent')->count(),
            'retards' => $query->where('status', 'retard')->count(),
            'taux_presence' => $total > 0 ? round(($presents / $total) * 100, 2) : 0,
            'inscrits_actifs' => $this->getNombreInscritsActifs(),
            'sessions_totales' => $this->sessions()->count(),
            'sessions_realisees' => $this->sessions()->where('statut', 'complete')->count(),
        ];
    }

    /**
     * Vérifier si un utilisateur peut s'inscrire
     */
    public function peutSInscrire(User $user)
    {
        // Vérifier si déjà inscrit
        if ($this->users()->where('users.id', $user->id)->wherePivot('actif', true)->exists()) {
            return false;
        }

        // Vérifier si le cours est complet
        if ($this->estComplet()) {
            return false;
        }

        // Vérifier si le cours est actif
        if (!$this->actif) {
            return false;
        }

        return true;
    }

    /**
     * Inscrire un utilisateur
     */
    public function inscrire(User $user, $dateDebut = null)
    {
        if (!$this->peutSInscrire($user)) {
            throw new \Exception('L\'utilisateur ne peut pas s\'inscrire à ce cours');
        }

        $this->users()->attach($user->id, [
            'date_debut' => $dateDebut ?? now(),
            'actif' => true,
            'ecole_id' => $this->ecole_id
        ]);

        activity()
            ->performedOn($this)
            ->causedBy(auth()->user())
            ->withProperties(['user_id' => $user->id])
            ->log("Inscription de {$user->nom_complet} au cours {$this->nom}");
    }

    /**
     * Désinscrire un utilisateur
     */
    public function desinscrire(User $user, $dateFin = null)
    {
        $this->users()->updateExistingPivot($user->id, [
            'date_fin' => $dateFin ?? now(),
            'actif' => false
        ]);

        activity()
            ->performedOn($this)
            ->causedBy(auth()->user())
            ->withProperties(['user_id' => $user->id])
            ->log("Désinscription de {$user->nom_complet} du cours {$this->nom}");
    }

    /**
     * Cloner le cours
     */
    public function cloner($nouveauNom = null)
    {
        $clone = $this->replicate(['code']);
        
        if ($nouveauNom) {
            $clone->nom = $nouveauNom;
        } else {
            $clone->nom = $this->nom . ' (Copie)';
        }
        
        $clone->save();

        // Cloner les horaires
        foreach ($this->horaires as $horaire) {
            $clone->horaires()->create($horaire->toArray());
        }

        activity()
            ->performedOn($clone)
            ->causedBy(auth()->user())
            ->log("Cours cloné depuis {$this->nom}");

        return $clone;
    }
}
