<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ecole extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'code',
        'adresse',
        'ville',
        'code_postal',
        'telephone',
        'email',
        'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];

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

    // Accessors
    public function getStatutColorAttribute(): string
    {
        return $this->actif ? 'success' : 'danger';
    }

    public function getStatutLabelAttribute(): string
    {
        return $this->actif ? '✅ Active' : '❌ Inactive';
    }

    public function getAdresseCompleteAttribute(): string
    {
        $adresse = collect([
            $this->adresse,
            $this->ville,
            $this->code_postal
        ])->filter()->implode(', ');
        
        return $adresse ?: 'Adresse non définie';
    }

    public function getInfosContactAttribute(): string
    {
        $contact = collect([
            $this->telephone,
            $this->email
        ])->filter()->implode(' • ');
        
        return $contact ?: 'Contact non défini';
    }

    // Méthodes utilitaires
    public function getTotalMembres(): int
    {
        return $this->users()->where('actif', true)->count();
    }

    public function getTotalCours(): int
    {
        return $this->cours()->where('actif', true)->count();
    }

    public function getTotalInstructeurs(): int
    {
        return $this->users()
            ->whereHas('roles', function ($query) {
                $query->whereIn('name', ['instructeur', 'admin', 'gestionnaire']);
            })
            ->where('actif', true)
            ->count();
    }

    public function getRevenuMensuel(): float
    {
        return $this->users()
            ->with('paiements')
            ->get()
            ->flatMap->paiements
            ->where('created_at', '>=', now()->startOfMonth())
            ->where('statut', 'complete')
            ->sum('montant');
    }

    // Scopes
    public function scopeActives($query)
    {
        return $query->where('actif', true);
    }

    public function scopeParCode($query, string $code)
    {
        return $query->where('code', strtoupper($code));
    }

    // Validation du code école
    public function setCodeAttribute($value): void
    {
        $this->attributes['code'] = strtoupper($value);
    }
}
