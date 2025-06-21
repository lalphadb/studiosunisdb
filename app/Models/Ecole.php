<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ecole extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'nom',
        'adresse',
        'ville',
        'code_postal',
        'telephone',
        'email',
        'directeur',
        'capacite_max',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }

    // Relations
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function cours(): HasMany
    {
        return $this->hasMany(Cours::class);
    }

    public function seminaires(): HasMany
    {
        return $this->hasMany(Seminaire::class);
    }

    public function paiements(): HasMany
    {
        return $this->hasMany(Paiement::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    // Accessors
    public function getNomCompletAttribute(): string
    {
        return $this->code . ' - ' . $this->nom;
    }
}
