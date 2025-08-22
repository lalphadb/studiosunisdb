<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ecole extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'slug',
        'adresse',
        'ville',
        'code_postal',
        'province',
        'telephone',
        'email',
        'site_web',
        'logo',
        'configuration',
        'est_active',
    ];

    protected $casts = [
        'configuration' => 'array',
        'est_active' => 'boolean',
    ];

    // Relations
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function membres(): HasMany
    {
        return $this->hasMany(Membre::class);
    }

    public function cours(): HasMany
    {
        return $this->hasMany(Cours::class);
    }

    public function examens(): HasMany
    {
        return $this->hasMany(Examen::class);
    }

    public function paiements(): HasMany
    {
        return $this->hasMany(Paiement::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('est_active', true);
    }
}
