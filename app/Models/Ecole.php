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
        'proprietaire',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relations
    public function users()
    {
        return $this->hasMany(User::class);
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

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
