<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Schema;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * Global scope pour mono-école - DÉSACTIVÉ TEMPORAIREMENT POUR DEBUG
     */
    protected static function booted()
    {
        // Global Scope DÉSACTIVÉ temporairement - causes pages blanches
        /*
        static::addGlobalScope('ecole', function ($query) {
            if (auth()->check() && !auth()->user()->hasRole('superadmin')) {
                try {
                    if (Schema::hasColumn('users', 'ecole_id')) {
                        $query->where('ecole_id', auth()->user()->ecole_id);
                    }
                } catch (\Exception $e) {
                    // Ignorer erreur
                }
            }
        });
        */
    }

    /**
     * Les attributs attribuables en masse.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'ecole_id', // Ajouté pour mono-école
    'active',
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
    'last_login_at' => 'datetime',
    'active' => 'boolean',
    ];

    /**
     * Relation avec l'école
     */
    public function ecole()
    {
        return $this->belongsTo(Ecole::class);
    }

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

    /**
     * Scope pour la même école
     */
    public function scopeSameEcole($query, $ecoleId = null)
    {
        $ecoleId = $ecoleId ?? auth()->user()?->ecole_id;
        return $query->where('ecole_id', $ecoleId);
    }
}
