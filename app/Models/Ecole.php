<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ecole extends Model
{
    use HasFactory;  // Retiré SoftDeletes

    protected $table = 'ecoles';

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
        'active'
    ];

    protected $casts = [
        'active' => 'boolean'
    ];

    protected $attributes = [
        'active' => true
    ];

    // ===== RELATIONS =====

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'ecole_id');
    }

    public function cours(): HasMany
    {
        return $this->hasMany(Cours::class, 'ecole_id');
    }

    public function ceintures(): HasMany
    {
        return $this->hasMany(Ceinture::class, 'ecole_id');
    }

    // ===== SCOPES =====

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    // ===== ACCESSEURS =====

    public function getNombreUsersAttribute(): int
    {
        return $this->users()->count();
    }

    public function getNombreCoursAttribute(): int
    {
        return $this->cours()->count();
    }

    // ===== MÉTHODES =====

    public function toggleStatus(): bool
    {
        $this->active = !$this->active;
        return $this->save();
    }
}
