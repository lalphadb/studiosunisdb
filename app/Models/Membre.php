<?php

namespace App\Models;

use App\Traits\BelongsToEcole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Membre extends Model
{
    use HasFactory, SoftDeletes, BelongsToEcole;

    protected $fillable = [
        'ecole_id',
        'user_id',
        'prenom',
        'nom',
        'email',
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

    protected $casts = [
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

    protected $appends = [
        'nom_complet',
        'age'
    ];

    // Relations
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ecole(): BelongsTo
    {
        return $this->belongsTo(Ecole::class);
    }

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
        return $this->belongsToMany(Cours::class, 'cours_membres')
            ->withPivot(['date_inscription', 'date_fin', 'statut'])
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
        return $this->hasMany(LienFamilial::class, 'membre_id');
    }

    public function liensInverses(): HasMany
    {
        return $this->hasMany(LienFamilial::class, 'membre_lie_id');
    }

    // Accesseurs
    public function getNomCompletAttribute(): string
    {
        return "{$this->prenom} {$this->nom}";
    }

    public function getAgeAttribute(): int
    {
        return $this->date_naissance ? $this->date_naissance->age : 0;
    }

    public function getTousLiensFamiliauxAttribute()
    {
        return $this->liensFamiliaux->merge($this->liensInverses);
    }

    public function getEstActifAttribute(): bool
    {
        return $this->statut === 'actif';
    }

    // Scopes
    public function scopeActif($query)
    {
        return $query->where('statut', 'actif');
    }

    public function scopeRecherche($query, $terme)
    {
        if (!$terme) return $query;
        
        return $query->where(function($q) use ($terme) {
            $q->where('prenom', 'like', "%{$terme}%")
              ->orWhere('nom', 'like', "%{$terme}%")
              ->orWhere('email', 'like', "%{$terme}%")
              ->orWhere('telephone', 'like', "%{$terme}%");
        });
    }
}
