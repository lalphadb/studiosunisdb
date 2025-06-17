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
        'niveau',
        'ordre_affichage',
        'description',
        'pre_requis',
        'duree_minimum_mois',
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
            'blanc' => 'bg-white text-gray-800 border-gray-400',
            'jaune' => 'bg-yellow-300 text-yellow-900 border-yellow-500',
            'orange' => 'bg-orange-300 text-orange-900 border-orange-500',
            'violet' => 'bg-purple-300 text-purple-900 border-purple-500',
            'bleu' => 'bg-blue-300 text-blue-900 border-blue-500',
            'vert' => 'bg-green-300 text-green-900 border-green-500',
            'marron' => 'bg-amber-700 text-white border-amber-800',
            'noir' => 'bg-black text-white border-gray-600',
        ];

        return $couleurs[$this->couleur] ?? 'bg-gray-200 text-gray-800 border-gray-400';
    }

    // Méthode pour obtenir l'emoji de la ceinture
    public function getEmojiAttribute()
    {
        if (str_contains($this->nom, 'Dan')) {
            return '🥇';
        }

        return match ($this->couleur) {
            'blanc' => '🤍',
            'jaune' => '💛',
            'orange' => '🧡',
            'violet' => '💜',
            'bleu' => '💙',
            'vert' => '💚',
            'marron' => '🤎',
            'noir' => '🖤',
            default => '🥋'
        };
    }
}
