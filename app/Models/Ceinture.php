<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ceinture extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'couleur',
        'ordre',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'ordre' => 'integer',
        ];
    }

    // Relations
    public function membre_ceintures(): HasMany
    {
        return $this->hasMany(MembreCeinture::class);
    }

    // Scopes
    public function scopeOrdered($query)
    {
        return $query->orderBy('ordre');
    }

    // Accessors
    public function getNomCompletAttribute(): string
    {
        return $this->nom . ' (' . $this->couleur . ')';
    }
}
