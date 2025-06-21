<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, LogsActivity;

    protected $fillable = [
        'name',
        'email',
        'password',
        'ecole_id',
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
    public function ecole()
    {
        return $this->belongsTo(Ecole::class);
    }

    public function membre_ceintures()
    {
        return $this->hasMany(MembreCeinture::class);
    }

    public function presences()
    {
        return $this->hasMany(Presence::class);
    }

    public function inscriptions_cours()
    {
        return $this->hasMany(InscriptionCours::class);
    }

    public function inscriptions_seminaires()
    {
        return $this->hasMany(InscriptionSeminaire::class);
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    public function paiements_traites()
    {
        return $this->hasMany(Paiement::class, 'processed_by_user_id');
    }

    // Scopes
    public function scopeMembresOnly($query)
    {
        return $query->whereHas('roles', fn($q) => $q->where('name', 'membre'));
    }

    public function scopeInstructeurs($query)
    {
        return $query->whereHas('roles', fn($q) => $q->where('name', 'instructeur'));
    }

    public function scopeAdmins($query)
    {
        return $query->whereHas('roles', fn($q) => $q->whereIn('name', ['admin', 'superadmin']));
    }

    public function scopeForEcole($query, $ecole_id)
    {
        return $query->where('ecole_id', $ecole_id);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeWithRole($query, $role)
    {
        return $query->whereHas('roles', fn($q) => $q->where('name', $role));
    }

    // Méthodes utilitaires pour les rôles
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('superadmin');
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isInstructeur(): bool
    {
        return $this->hasRole('instructeur');
    }

    public function isMembre(): bool
    {
        return $this->hasRole('membre');
    }

    public function canManageEcole($ecole_id): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        if ($this->isAdmin() && $this->ecole_id == $ecole_id) {
            return true;
        }

        return false;
    }

    // Accessors
    public function getAgeAttribute()
    {
        if (!$this->date_naissance) {
            return null;
        }
        return $this->date_naissance->diffInYears(now());
    }

    public function getNomCompletAttribute()
    {
        return $this->name;
    }

    // Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'ecole_id', 'active'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
