<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

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
     * Vérifier si l'utilisateur peut accéder à une école
     */
    public function canAccessEcole($ecoleId): bool
    {
        // SuperAdmin peut accéder à toutes les écoles
        if ($this->hasRole('superadmin')) {
            return true;
        }
        
        // Admin école peut accéder à son école uniquement
        if ($this->hasRole('admin')) {
            return $this->ecole_id == $ecoleId;
        }
        
        return false;
    }
}
