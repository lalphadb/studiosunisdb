<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ceinture extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'couleur',
        'niveau',
        'ordre_affichage',
        'description',
        'pre_requis',
        'duree_minimum_mois',
        'couleur_hex'
    ];

    protected $casts = [
        'niveau' => 'integer',
        'ordre_affichage' => 'integer',
        'duree_minimum_mois' => 'integer',
    ];

    // Relations
    public function membres()
    {
        return $this->hasMany(Membre::class, 'ceinture_actuelle_id');
    }

    public function membreCeintures()
    {
        return $this->hasMany(MembreCeinture::class);
    }

    // Scopes
    public function scopeOrdered($query)
    {
        return $query->orderBy('ordre_affichage');
    }

    public function scopeParNiveau($query, $niveau)
    {
        return $query->where('niveau', $niveau);
    }

    // Méthodes utiles
    public function prochaineCeinture()
    {
        return self::where('niveau', $this->niveau + 1)->first();
    }

    public function ceinturePrecedente()
    {
        return self::where('niveau', $this->niveau - 1)->first();
    }

    // Attribut couleur badge CSS
    public function getCouleurBadgeAttribute()
    {
        $couleurs = [
            'blanc' => 'bg-white text-gray-800 border-gray-300',
            'jaune' => 'bg-yellow-200 text-yellow-800 border-yellow-400',
            'orange' => 'bg-orange-200 text-orange-800 border-orange-400',
            'vert' => 'bg-green-200 text-green-800 border-green-400',
            'bleu' => 'bg-blue-200 text-blue-800 border-blue-400',
            'violet' => 'bg-purple-200 text-purple-800 border-purple-400',
            'marron' => 'bg-amber-600 text-white border-amber-700',
            'noir' => 'bg-black text-white border-gray-700',
            'rouge' => 'bg-red-600 text-white border-red-700',
            'or' => 'bg-yellow-400 text-yellow-800 border-yellow-500',
        ];

        return $couleurs[$this->couleur] ?? 'bg-gray-200 text-gray-800 border-gray-300';
    }
}
