<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

/**
 * Modèle Membre Ultra-Professionnel Laravel 11
 * Gestion complète des élèves d'arts martiaux
 * Conforme aux standards PSR-12 et Laravel Best Practices
 */
class Membre extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'prenom', 'nom', 'date_naissance', 'sexe',
        'telephone', 'adresse', 'ville', 'code_postal',
        'contact_urgence_nom', 'contact_urgence_telephone', 'contact_urgence_relation',
        'statut', 'ceinture_actuelle_id', 'date_inscription', 'date_derniere_presence',
        'notes_medicales', 'allergies', 'conditions_medicales',
        'consentement_photos', 'consentement_communications', 'consentement_donnees',
        'notes_instructeur', 'notes_admin'
    ];

    protected $casts = [
        'date_naissance' => 'date',
        'date_inscription' => 'date',
        'date_derniere_presence' => 'datetime',
        'allergies' => 'array',
        'consentement_photos' => 'boolean',
        'consentement_communications' => 'boolean',
        'consentement_donnees' => 'boolean',
    ];

    // Relations Eloquent optimisées
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ceintureActuelle(): BelongsTo
    {
        return $this->belongsTo(Ceinture::class, 'ceinture_actuelle_id');
    }

    public function presences(): HasMany
    {
        return $this->hasMany(Presence::class);
    }

    public function paiements(): HasMany
    {
        return $this->hasMany(Paiement::class);
    }

    // Accessors modernes
    public function getNomCompletAttribute(): string
    {
        return "{$this->prenom} {$this->nom}";
    }

    public function getAgeAttribute(): int
    {
        return $this->date_naissance->diffInYears(now());
    }

    // Scopes pour requêtes optimisées
    public function scopeActif($query)
    {
        return $query->where('statut', 'actif');
    }

    public function scopeRecherche($query, $terme)
    {
        return $query->where(function($q) use ($terme) {
            $q->where('prenom', 'like', "%{$terme}%")
              ->orWhere('nom', 'like', "%{$terme}%")
              ->orWhere('telephone', 'like', "%{$terme}%");
        });
    }

    // Méthodes business logiques
    public function peutProgresse($nouvelleCeinture): bool
    {
        if (!$this->ceintureActuelle || !$nouvelleCeinture) return false;
        return $nouvelleCeinture->ordre > $this->ceintureActuelle->ordre;
    }
}
