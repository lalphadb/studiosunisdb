<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'ecole_id',
        'telephone',
        'statut',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relation avec l'école assignée
     */
    public function ecole()
    {
        return $this->belongsTo(Ecole::class);
    }

    /**
     * Relation avec les cours comme instructeur
     */
    public function coursInstructeur()
    {
        return $this->hasMany(Cours::class, 'instructeur_id');
    }

    /**
     * Vérifier si l'utilisateur peut accéder à une école spécifique
     */
    public function canAccessEcole($ecoleId)
    {
        return $this->hasRole('superadmin') || $this->ecole_id == $ecoleId;
    }

    /**
     * Obtenir le nom complet de l'utilisateur
     */
    public function getFullNameAttribute()
    {
        return $this->name;
    }

    /**
     * Vérifier si l'utilisateur est un super administrateur
     */
    public function isSuperAdmin()
    {
        return $this->hasRole('superadmin');
    }

    /**
     * Vérifier si l'utilisateur est un administrateur d'école
     */
    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    /**
     * Vérifier si l'utilisateur est un instructeur
     */
    public function isInstructeur()
    {
        return $this->hasRole('instructeur');
    }
}
