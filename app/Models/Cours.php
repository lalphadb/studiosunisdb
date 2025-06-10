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
        'ecole_id',
        'instructeur_id',
        'jour_semaine',
        'heure_debut',
        'heure_fin',
        'capacite_max',
        'age_min',
        'age_max',
        'niveau_requis',
        'type_cours',
        'status',
        'prix_mensuel',
        'prix_session',
        'date_debut',
        'date_fin',
    ];

    protected $casts = [
        'heure_debut' => 'datetime:H:i',
        'heure_fin' => 'datetime:H:i',
        'date_debut' => 'date',
        'date_fin' => 'date',
        'prix_mensuel' => 'decimal:2',
        'prix_session' => 'decimal:2',
    ];

    // Relations
    public function ecole(): BelongsTo
    {
        return $this->belongsTo(Ecole::class);
    }

    public function instructeur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructeur_id');
    }

    public function inscriptions(): HasMany
    {
        return $this->hasMany(InscriptionCours::class);
    }

    public function inscriptionsActives(): HasMany
    {
        return $this->hasMany(InscriptionCours::class)->where('status', 'active');
    }

    public function presences(): HasMany
    {
        return $this->hasMany(Presence::class);
    }

    // Accesseurs
    public function getHoraireFormatteAttribute(): string
    {
        return ucfirst($this->jour_semaine) . ' ' . 
               $this->heure_debut->format('H:i') . '-' . 
               $this->heure_fin->format('H:i');
    }

    public function getNombreInscritsAttribute(): int
    {
        return $this->inscriptionsActives()->count();
    }

    public function getPlacesDisponiblesAttribute(): int
    {
        return $this->capacite_max - $this->nombre_inscrits;
    }

    public function getEstCompletAttribute(): bool
    {
        return $this->nombre_inscrits >= $this->capacite_max;
    }

    // Scopes
    public function scopeActifs($query)
    {
        return $query->where('status', 'actif');
    }

    public function scopeParEcole($query, $ecoleId)
    {
        return $query->where('ecole_id', $ecoleId);
    }

    public function scopeParJour($query, $jour)
    {
        return $query->where('jour_semaine', $jour);
    }

    public function scopeParType($query, $type)
    {
        return $query->where('type_cours', $type);
    }
}
