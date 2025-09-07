<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Schema;
use App\Traits\BelongsToEcole;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes, BelongsToEcole;

    /**
     * Global scope pour mono-école
     */
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
        'champs_personnalises'
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
        'champs_personnalises' => 'array'
    ];

    /**
     * Attributs calculés
     */
    protected $appends = [
        'nom_complet',
        'age',
        'est_actif',
        'tous_liens_familiaux'
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

    // Accesseurs (ex-Membre)
    public function getNomCompletAttribute(): string
    {
        if ($this->prenom && $this->nom) {
            return "{$this->prenom} {$this->nom}";
        }
        return $this->name;
    }

    public function getAgeAttribute(): ?int
    {
        return $this->date_naissance ? $this->date_naissance->age : null;
    }

    public function getTousLiensFamiliauxAttribute()
    {
        return $this->liensFamiliaux->merge($this->liensInverses);
    }

    public function getEstActifAttribute(): bool
    {
        return $this->statut === 'actif' && $this->active;
    }

    // Vérifications rôles (anciennes méthodes)
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('superadmin');
    }

    public function isAdminEcole(): bool
    {
        return $this->hasRole('admin_ecole');
    }

    public function isInstructeur(): bool
    {
        return $this->hasRole('instructeur');
    }

    public function isMembre(): bool
    {
        return $this->hasRole('membre');
    }

    // Nouveaux helpers pour membre/karaté
    public function isMembreKarate(): bool
    {
        return !empty($this->prenom) && !empty($this->nom) && !empty($this->date_naissance);
    }

    public function isAdminOnly(): bool
    {
        return !$this->isMembreKarate() && ($this->isAdminEcole() || $this->isSuperAdmin() || $this->isInstructeur());
    }

    // Scopes (ex-Membre)
    public function scopeActif($query)
    {
        return $query->where('statut', 'actif')->where('active', true);
    }

    public function scopeRecherche($query, $terme)
    {
        if (!$terme) return $query;
        
        return $query->where(function($q) use ($terme) {
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
        return $query->whereNull('prenom')
                    ->orWhereNull('nom')
                    ->orWhereNull('date_naissance');
    }

    /**
     * Scope pour la même école
     */
    public function scopeSameEcole($query, $ecoleId = null)
    {
        $ecoleId = $ecoleId ?? auth()->user()?->ecole_id;
        return $query->where('ecole_id', $ecoleId);
    }
}