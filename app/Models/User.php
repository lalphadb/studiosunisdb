<?php

namespace App\Models;

use App\Traits\BelongsToEcole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Schema;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use BelongsToEcole, HasApiTokens, HasFactory, HasRoles, Notifiable, SoftDeletes;

    /**
     * DÉSACTIVÉ TEMPORAIREMENT - Global scope causait boucle infinie
     * TODO: Réimplémenter avec protection contre récursion
     */
    /*
    protected static function booted()
    {
        static::addGlobalScope('ecole', function ($query) {
            if (auth()->check() && !auth()->user()->hasRole('superadmin')) {
                try {
                    if (Schema::hasColumn('users', 'ecole_id')) {
                        $query->where('ecole_id', auth()->user()->ecole_id);
                    }
                } catch (\Exception $e) {
                    // Ignorer erreur pendant migration
                }
            }
        });
    }
    */

    /**
     * Les attributs attribuables en masse.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'ecole_id',
        'active',
        'last_login_at',
        'email_verified_at',
        // Nouvelles colonnes fusion
        'prenom',
        'nom',
        'telephone',
        'date_naissance',
        'sexe',
        'adresse',
        'ville',
        'code_postal',
        'province',
        'contact_urgence_nom',
        'contact_urgence_telephone',
        'contact_urgence_relation',
        'statut',
        'ceinture_actuelle_id',
        'date_inscription',
        'date_derniere_presence',
        'notes_medicales',
        'allergies',
        'medicaments',
        'consentement_photos',
        'consentement_communications',
        'date_consentement',
        'family_id',
        'champs_personnalises',
    ];

    /**
     * Les attributs à cacher lors de la sérialisation.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Les types de cast natifs.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_login_at' => 'datetime',
        'active' => 'boolean',
        'date_naissance' => 'date',
        'date_inscription' => 'date',
        'date_derniere_presence' => 'date',
        'date_consentement' => 'datetime',
        'consentement_photos' => 'boolean',
        'consentement_communications' => 'boolean',
        'allergies' => 'array',
        'medicaments' => 'array',
        'champs_personnalises' => 'array',
    ];

    /**
     * RÉDUIT: Attributs calculés sans relations complexes
     */
    protected $appends = [
        'nom_complet',
        'age',
        'est_actif',
    ];

    // Relations Spatie Permission
    public function ecole(): BelongsTo
    {
        return $this->belongsTo(Ecole::class);
    }

    // Relations karaté (ex-Membre)
    public function family(): BelongsTo
    {
        return $this->belongsTo(Family::class);
    }

    public function ceintureActuelle(): BelongsTo
    {
        return $this->belongsTo(Ceinture::class, 'ceinture_actuelle_id');
    }

    public function cours(): BelongsToMany
    {
        return $this->belongsToMany(Cours::class, 'cours_users')
            ->withPivot(['date_inscription', 'date_fin', 'statut_inscription', 'prix_personnalise', 'notes'])
            ->withTimestamps();
    }

    public function presences(): HasMany
    {
        return $this->hasMany(Presence::class);
    }

    public function paiements(): HasMany
    {
        return $this->hasMany(Paiement::class);
    }

    public function progressionCeintures(): HasMany
    {
        return $this->hasMany(ProgressionCeinture::class);
    }

    public function examens(): HasMany
    {
        return $this->hasMany(Examen::class);
    }

    // Liens familiaux bidirectionnels
    public function liensFamiliaux(): HasMany
    {
        return $this->hasMany(LienFamilial::class, 'user_id');
    }

    public function liensInverses(): HasMany
    {
        return $this->hasMany(LienFamilial::class, 'user_lie_id');
    }

    // Accesseurs SÉCURISÉS (ex-Membre)
    public function getNomCompletAttribute(): string
    {
        if (! empty($this->prenom) && ! empty($this->nom)) {
            return trim("{$this->prenom} {$this->nom}");
        }

        return $this->name ?? 'Utilisateur';
    }

    public function getAgeAttribute(): ?int
    {
        if (! $this->date_naissance) {
            return null;
        }

        try {
            return $this->date_naissance->age;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getTousLiensFamiliauxAttribute()
    {
        // SÉCURISÉ: Éviter chargement automatique des relations
        if (! $this->relationLoaded('liensFamiliaux') || ! $this->relationLoaded('liensInverses')) {
            return collect([]);
        }

        return $this->liensFamiliaux->merge($this->liensInverses);
    }

    public function getEstActifAttribute(): bool
    {
        return ($this->statut === 'actif' || $this->statut === null) && $this->active;
    }

    // Vérifications rôles SÉCURISÉES
    public function isSuperAdmin(): bool
    {
        try {
            return $this->hasRole('superadmin');
        } catch (\Exception $e) {
            return false;
        }
    }

    public function isAdminEcole(): bool
    {
        try {
            return $this->hasRole('admin_ecole');
        } catch (\Exception $e) {
            return false;
        }
    }

    public function isInstructeur(): bool
    {
        try {
            return $this->hasRole('instructeur');
        } catch (\Exception $e) {
            return false;
        }
    }

    public function isMembre(): bool
    {
        try {
            return $this->hasRole('membre');
        } catch (\Exception $e) {
            return false;
        }
    }

    // Nouveaux helpers pour membre/karaté
    public function isMembreKarate(): bool
    {
        return ! empty($this->prenom) && ! empty($this->nom) && ! empty($this->date_naissance);
    }

    public function isAdminOnly(): bool
    {
        return ! $this->isMembreKarate() && ($this->isAdminEcole() || $this->isSuperAdmin() || $this->isInstructeur());
    }

    // Scopes SÉCURISÉS (ex-Membre)
    public function scopeActif($query)
    {
        return $query->where(function ($q) {
            $q->where('statut', 'actif')
                ->orWhereNull('statut');
        })->where('active', true);
    }

    public function scopeRecherche($query, $terme)
    {
        if (! $terme) {
            return $query;
        }

        $terme = trim($terme);
        if (strlen($terme) < 2) {
            return $query;
        }

        return $query->where(function ($q) use ($terme) {
            $q->where('prenom', 'like', "%{$terme}%")
                ->orWhere('nom', 'like', "%{$terme}%")
                ->orWhere('name', 'like', "%{$terme}%")
                ->orWhere('email', 'like', "%{$terme}%")
                ->orWhere('telephone', 'like', "%{$terme}%");
        });
    }

    public function scopeMembresKarate($query)
    {
        return $query->whereNotNull('prenom')
            ->whereNotNull('nom')
            ->whereNotNull('date_naissance');
    }

    public function scopeAdminsOnly($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('prenom')
                ->orWhereNull('nom')
                ->orWhereNull('date_naissance');
        });
    }

    /**
     * Scope pour la même école SÉCURISÉ
     */
    public function scopeSameEcole($query, $ecoleId = null)
    {
        if (! $ecoleId) {
            // Éviter récursion en ne chargeant pas auth()->user()
            return $query;
        }

        return $query->where('ecole_id', $ecoleId);
    }
}
