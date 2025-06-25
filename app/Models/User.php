<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'ecole_id',
        'famille_principale_id',
        'telephone',
        'date_naissance',
        'sexe',
        'adresse',
        'ville',
        'code_postal',
        'contact_urgence_nom',
        'contact_urgence_telephone',
        'active',
        'date_inscription',
        'notes',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'date_naissance' => 'date',  
        'date_inscription' => 'date',
        'active' => 'boolean',
    ];

    // Relations
    public function ecole(): BelongsTo
    {
        return $this->belongsTo(Ecole::class);
    }

    public function famillePrincipale(): BelongsTo
    {
        return $this->belongsTo(User::class, 'famille_principale_id');
    }

    public function membresFamille(): HasMany
    {
        return $this->hasMany(User::class, 'famille_principale_id');
    }

    // NOUVELLE RELATION STANDARDISÉE
    public function userCeintures(): HasMany
    {
        return $this->hasMany(UserCeinture::class);
    }

    // ALIAS pour compatibilité avec l'ancien code
    public function membreCeintures(): HasMany
    {
        return $this->userCeintures();
    }

    public function inscriptionsCours(): HasMany
    {
        return $this->hasMany(InscriptionCours::class);
    }

    public function inscriptionsSeminaires(): HasMany
    {
        return $this->hasMany(InscriptionSeminaire::class);
    }

    public function presences(): HasMany
    {
        return $this->hasMany(Presence::class);
    }

    public function paiements(): HasMany
    {
        return $this->hasMany(Paiement::class);
    }

    // Accesseurs
    public function getCeintureActuelleAttribute()
    {
        return $this->userCeintures()
            ->where('valide', true)
            ->with('ceinture')
            ->orderByDesc('date_obtention')
            ->first()?->ceinture;
    }

    public function getAgeAttribute()
    {
        return $this->date_naissance?->age;
    }

    public function getRolePrincipalAttribute()
    {
        return $this->roles->first()?->name ?? 'user';
    }
}
