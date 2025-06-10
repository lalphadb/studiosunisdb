<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ecole extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'adresse',
        'ville',
        'province',
        'code_postal',
        'telephone',
        'email',
        'site_web',
        'directeur',
        'capacite_max',
        'statut',
        'description', // Maintenant disponible
    ];

    protected $casts = [
        'capacite_max' => 'integer',
    ];

    public function membres()
    {
        return $this->hasMany(Membre::class);
    }

    public function cours()
    {
        return $this->hasMany(Cours::class);
    }
}
