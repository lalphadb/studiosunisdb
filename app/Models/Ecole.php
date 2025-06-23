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
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relation avec users (PAS membres)
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Relation avec cours
    public function cours()
    {
        return $this->hasMany(Cours::class);
    }

    // Relation avec seminaires
    public function seminaires()
    {
        return $this->hasMany(Seminaire::class);
    }

    // Relation avec paiements
    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
