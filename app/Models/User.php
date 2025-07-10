<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, TwoFactorAuthenticatable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'ecole_id',
        'phone',
        'date_naissance',
        'adresse',
        'is_active',
        'last_login_at',
        'profile_photo_path',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'date_naissance' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Scope global multi-tenant
     */
    protected static function boot()
    {
        parent::boot();
        
        // Appliquer le scope multi-tenant sauf pour SuperAdmin
        static::addGlobalScope('ecole_scope', function ($builder) {
            if (auth()->check() && !auth()->user()->hasRole('superadmin')) {
                $builder->where('ecole_id', auth()->user()->ecole_id);
            }
        });
    }

    /**
     * Relation avec l'école
     */
    public function ecole(): BelongsTo
    {
        return $this->belongsTo(Ecole::class);
    }

    /**
     * Relation avec les présences
     */
    public function presences()
    {
        return $this->hasMany(Presence::class);
    }

    /**
     * Relation avec les paiements
     */
    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    /**
     * Relation avec les cours inscrits
     */
    public function coursInscrits()
    {
        return $this->belongsToMany(Cours::class, 'cours_users')
                    ->withPivot(['date_inscription', 'is_active'])
                    ->withTimestamps();
    }

    /**
     * Relation avec la ceinture actuelle
     */
    public function ceintureActuelle()
    {
        return $this->belongsTo(Ceinture::class, 'ceinture_id');
    }

    /**
     * Vérifier si l'utilisateur est admin de son école
     */
    public function isAdminEcole(): bool
    {
        return $this->hasRole('admin_ecole');
    }

    /**
     * Vérifier si l'utilisateur est SuperAdmin
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('superadmin');
    }

    /**
     * Obtenir le nom complet avec école
     */
    public function getFullNameWithEcoleAttribute(): string
    {
        $name = $this->name;
        if ($this->ecole) {
            $name .= " ({$this->ecole->nom})";
        }
        return $name;
    }

    /**
     * Scope pour filtrer par rôle
     */
    public function scopeByRole($query, string $role)
    {
        return $query->whereHas('roles', function ($q) use ($role) {
            $q->where('name', $role);
        });
    }

    /**
     * Scope pour utilisateurs actifs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
