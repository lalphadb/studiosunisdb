<?php

namespace App\Models;

use App\Models\Concerns\BelongsToEcole;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, BelongsToEcole;

    /**
     * Désactiver le scope global ecole pour les superadmins
     */
    protected static $withoutEcoleScope = false;

    /**
     * Les attributs attribuables en masse.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'ecole_id',
    ];

    /**
     * Les attributs à cacher lors de la sérialisation.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Les types de cast natifs.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Vérifier si l'utilisateur est un super admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('superadmin');
    }

    /**
     * Vérifier si l'utilisateur est un admin d'école
     */
    public function isAdminEcole(): bool
    {
        return $this->hasRole('admin_ecole');
    }

    /**
     * Vérifier si l'utilisateur est un instructeur
     */
    public function isInstructeur(): bool
    {
        return $this->hasRole('instructeur');
    }

    /**
     * Vérifier si l'utilisateur est un membre
     */
    public function isMembre(): bool
    {
        return $this->hasRole('membre');
    }

    /**
     * Relation avec le membre associé
     */
    public function membre()
    {
        return $this->hasOne(Membre::class);
    }
}
