<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ceinture extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'nom_court',
        'couleur',
        'ordre',
        'mois_minimum',
        'description',
        'competences_requises',
        'actif',
    ];

    protected $casts = [
        'ordre' => 'integer',
        'mois_minimum' => 'integer',
        'competences_requises' => 'array',
        'actif' => 'boolean',
    ];

    // Relations
    public function userCeintures(): HasMany
    {
        return $this->hasMany(UserCeinture::class);
    }

    // Accessors
    public function getCouleurStyleAttribute(): string
    {
        return "background-color: {$this->couleur}; color: " . 
               ($this->couleur === '#FFFFFF' ? '#000000' : '#FFFFFF');
    }

    public function getNiveauLabelAttribute(): string
    {
        return match(true) {
            $this->ordre <= 2 => '🥋 Débutant',
            $this->ordre <= 4 => '📈 Intermédiaire', 
            $this->ordre <= 6 => '💪 Avancé',
            default => '🥇 Expert'
        };
    }

    // Scopes
    public function scopeActives($query)
    {
        return $query->where('actif', true);
    }

    public function scopeOrdreCroissant($query)
    {
        return $query->orderBy('ordre');
    }
}
