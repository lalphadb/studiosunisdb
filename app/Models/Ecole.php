<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ecole extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'code',
        'adresse',
        'ville',
        'province',
        'code_postal',
        'telephone',
        'email',
        'site_web',
        'description',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean'
    ];

    // Relations
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function membres()
    {
        return $this->hasMany(Membre::class);
    }

    public function cours()
    {
        return $this->hasMany(Cours::class);
    }

    public function seminaires()
    {
        return $this->hasMany(Seminaire::class);
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    // Accesseurs
    public function getAdresseCompleteAttribute()
    {
        return $this->adresse . ', ' . $this->ville . ', ' . $this->province . ' ' . $this->code_postal;
    }
}
