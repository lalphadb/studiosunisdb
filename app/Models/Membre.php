<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Membre extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'ecole_id',
        'nom',
        'prenom', 
        'email',
        'telephone',
        'date_naissance',
        'sexe',
        'adresse',
        'ville',
        'code_postal',
        'contact_urgence_nom',
        'contact_urgence_telephone',
        'date_inscription',
        'active',
        'notes'
    ];

    protected $casts = [
        'date_naissance' => 'date',
        'date_inscription' => 'date',
        'active' => 'boolean'
    ];

    // Configuration Spatie ActivityLog
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => match($eventName) {
                'created' => 'Membre créé',
                'updated' => 'Membre modifié', 
                'deleted' => 'Membre supprimé',
                default => $eventName
            });
    }

    // Relations
    public function ecole(): BelongsTo
    {
        return $this->belongsTo(Ecole::class);
    }

    public function membre_ceintures(): HasMany
    {
        return $this->hasMany(MembreCeinture::class);
    }

    public function presences(): HasMany
    {
        return $this->hasMany(Presence::class);
    }

    public function paiements(): HasMany
    {
        return $this->hasMany(Paiement::class);
    }

    public function inscriptions_cours(): HasMany
    {
        return $this->hasMany(InscriptionCours::class);
    }

    public function inscriptions_seminaires(): HasMany
    {
        return $this->hasMany(InscriptionSeminaire::class);
    }

    // ACCESSEURS - MÉTHODES CORRIGÉES
    public function getNomCompletAttribute(): string
    {
        return trim($this->prenom . ' ' . $this->nom);
    }

    public function getInitialesAttribute(): string
    {
        $prenom = substr($this->prenom ?? '', 0, 1);
        $nom = substr($this->nom ?? '', 0, 1);
        return strtoupper($prenom . $nom);
    }

    public function getAgeAttribute(): ?int
    {
        return $this->date_naissance ? 
               $this->date_naissance->diffInYears(Carbon::now()) : 
               null;
    }

    public function getAncienneteAttribute(): int
    {
        return $this->date_inscription ? 
               $this->date_inscription->diffInYears(Carbon::now()) : 
               0;
    }

    // MÉTHODES CEINTURES - CORRIGÉES POUR L'AFFICHAGE
    public function getCeintureActuelle()
    {
        return $this->membre_ceintures()
                   ->with('ceinture')
                   ->orderBy('date_obtention', 'desc')
                   ->first();
    }

    public function getCeintureActuellePourAffichage()
    {
        $derniereCeinture = $this->getCeintureActuelle();
        return $derniereCeinture ? $derniereCeinture->ceinture : null;
    }

    // FIX: ceintureActuelle doit retourner une RELATION, pas un objet
    public function ceintureActuelle(): ?MembreCeinture
    {
        return $this->getCeintureActuelle();
    }

    // Scopes
    public function scopeActifs($query)
    {
        return $query->where('active', true);
    }

    public function scopeParEcole($query, $ecoleId)
    {
        return $query->where('ecole_id', $ecoleId);
    }

    public function scopeAvecContact($query)
    {
        return $query->whereNotNull('email')
                    ->orWhereNotNull('telephone');
    }
}
