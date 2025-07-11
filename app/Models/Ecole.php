<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ecole extends Model
{
    use HasFactory;
    // PAS de HasEcoleScope ici - une école ne peut pas avoir un scope école!

    protected $fillable = [
        'nom',
        'code',
        'adresse',
        'ville',
        'province',
        'code_postal',
        'pays',
        'telephone',
        'email',
        'site_web',
        'description',
        'logo',
        'config',
        'actif',
        'date_ouverture',
    ];

    protected $casts = [
        'actif' => 'boolean',
        'config' => 'json',
        'date_ouverture' => 'date',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function cours()
    {
        return $this->hasMany(Cours::class);
    }

    public function ceintures()
    {
        return $this->hasMany(Ceinture::class);
    }

    public function seminaires()
    {
        return $this->hasMany(Seminaire::class);
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    public function presences()
    {
        return $this->hasMany(Presence::class);
    }
}
