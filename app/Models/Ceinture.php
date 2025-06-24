<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ceinture extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'couleur',
        'ordre',
        'description',
    ];

    /**
     * Relation avec les utilisateurs via la table pivot
     */
    public function utilisateurCeintures()
    {
        return $this->hasMany(UtilisateurCeinture::class);
    }

    /**
     * Relation avec les utilisateurs
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'membre_ceintures')
            ->withPivot('date_obtention', 'examinateur', 'commentaires', 'valide')
            ->withTimestamps();
    }

    /**
     * Scope pour ordonner par ordre croissant
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('ordre');
    }
}
