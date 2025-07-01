<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    // Relations existantes
    public function userCeintures(): HasMany
    {
        return $this->hasMany(UserCeinture::class);
    }

    // Alias pour compatibilité
    public function membreCeintures(): HasMany
    {
        return $this->userCeintures();
    }

    // NOUVELLE RELATION AJOUTÉE
    /**
     * Relation Many-to-Many avec les utilisateurs via user_ceintures
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_ceintures')
                    ->withPivot([
                        'date_attribution',
                        'attribue_par',
                        'date_obtention', 
                        'examinateur',
                        'commentaires',
                        'certifie',
                        'valide',
                        'instructeur_id',
                        'examen_id',
                        'ecole_id'
                    ])
                    ->withTimestamps();
    }

    /**
     * Utilisateurs avec cette ceinture validée
     */
    public function usersValides(): BelongsToMany
    {
        return $this->users()->wherePivot('valide', true);
    }

    // Accesseurs existants
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

    // Scopes existants
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
