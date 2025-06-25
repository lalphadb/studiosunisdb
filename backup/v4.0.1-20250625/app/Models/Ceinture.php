<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ceinture extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'couleur',
        'ordre',
        'description',
    ];

    protected $casts = [
        'ordre' => 'integer',
    ];

    // Relations
    public function userCeintures(): HasMany
    {
        return $this->hasMany(UserCeinture::class);
    }

    // Alias pour compatibilité
    public function membreCeintures(): HasMany
    {
        return $this->userCeintures();
    }

    // Accesseurs
    public function getNombreUtilisateursAttribute()
    {
        return $this->userCeintures()->where('valide', true)->count();
    }

    public function getEstKyuAttribute()
    {
        return $this->ordre <= 10; // Supposons que les 10 premiers sont des Kyu
    }

    public function getEstDanAttribute()
    {
        return $this->ordre > 10; // Les suivants sont des Dan
    }

    // Scopes
    public function scopeKyu($query)
    {
        return $query->where('ordre', '<=', 10)->orderBy('ordre');
    }

    public function scopeDan($query)
    {
        return $query->where('ordre', '>', 10)->orderBy('ordre');
    }

    public function scopeOrdreCroissant($query)
    {
        return $query->orderBy('ordre');
    }
}
