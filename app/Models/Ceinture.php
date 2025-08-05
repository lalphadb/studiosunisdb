<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ceinture extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'couleur_hex',
        'ordre',
        'description',
        'prerequis_techniques',
        'duree_minimum_mois',
        'presences_minimum',
        'age_minimum',
        'tarif_examen',
        'examen_requis',
        'actif',
    ];

    protected $casts = [
        'prerequis_techniques' => 'array',
        'duree_minimum_mois' => 'integer',
        'presences_minimum' => 'integer',
        'age_minimum' => 'integer',
        'tarif_examen' => 'decimal:2',
        'examen_requis' => 'boolean',
        'actif' => 'boolean',
    ];

    // Relations
    public function membres(): HasMany
    {
        return $this->hasMany(Membre::class, 'ceinture_actuelle_id');
    }

    public function examens(): HasMany
    {
        return $this->hasMany(Examen::class, 'ceinture_cible_id');
    }

    // Scopes
    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }

    public function scopeParOrdre($query)
    {
        return $query->orderBy('ordre');
    }

    // Accessors
    public function getCouleurAttribute(): string
    {
        return $this->couleur_hex ?: '#6B7280';
    }

    public function getEstFinaleAttribute(): bool
    {
        return strpos(strtolower($this->nom), 'noir') !== false;
    }

    // Méthodes utilitaires
    public function peutProgresse(Membre $membre): bool
    {
        if (!$membre->date_derniere_presence) {
            return false;
        }

        $moisDepuisObtention = $membre->date_derniere_presence
            ->diffInMonths($membre->updated_at);

        return $moisDepuisObtention >= $this->duree_minimum_mois;
    }
}
