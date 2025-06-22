<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;  // ✅ HasApiTokens supprimé

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

    public function getAgeAttribute()
    {
        if (!$this->date_naissance) {
            return null;
        }
        return $this->date_naissance->age;
    }

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

    public function famillePrincipale()
    {
        return $this->belongsTo(User::class, 'famille_principale_id');
    }

    public function membresFamille()
    {
        return $this->hasMany(User::class, 'famille_principale_id');
    }

    public function isSuperAdmin()
    {
        return $this->hasRole('superadmin');
    }

    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    public function isInstructeur()
    {
        return $this->hasRole('instructeur');
    }

    public function isMembre()
    {
        return $this->hasRole('membre');
    }

    public function ceintureActuelle()
    {
        return $this->membre_ceintures()
            ->with('ceinture')
            ->where('valide', true)
            ->orderBy('date_obtention', 'desc')
            ->first();
    }

    public function scopeForEcole($query, $ecoleId)
    {
        return $query->where('ecole_id', $ecoleId);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
