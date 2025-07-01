<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name', 'email', 'password', 'ecole_id', 'famille_principale_id',
        'telephone', 'date_naissance', 'sexe', 'adresse', 'ville', 
        'code_postal', 'contact_urgence_nom', 'contact_urgence_telephone',
        'active', 'date_inscription', 'notes', 'ceinture_actuelle_id'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'date_naissance' => 'date',
        'date_inscription' => 'date',
        'active' => 'boolean',
    ];

    // Relations existantes
    public function ecole(): BelongsTo
    {
        return $this->belongsTo(Ecole::class);
    }

    public function userCeintures(): HasMany
    {
        return $this->hasMany(UserCeinture::class);
    }

    public function ceintureActuelle(): BelongsTo
    {
        return $this->belongsTo(Ceinture::class, 'ceinture_actuelle_id');
    }

    // NOUVELLES RELATIONS AJOUTÉES
    /**
     * Relation Many-to-Many avec les ceintures via user_ceintures
     */
    public function ceintures(): BelongsToMany
    {
        return $this->belongsToMany(Ceinture::class, 'user_ceintures')
                    ->withPivot([
                        'date_attribution',
                        'attribue_par', 
                        'date_obtention',
                        'examinateur',
                        'commentaires',
                        'certifie',
                        'valide',
                        'instructeur_id',
                        'examen_id',
                        'ecole_id'
                    ])
                    ->withTimestamps()
                    ->orderBy('user_ceintures.date_obtention', 'desc');
    }

    /**
     * Ceinture actuelle validée (la plus récente)
     */
    public function ceintureActuelleValidee()
    {
        return $this->ceintures()
                    ->wherePivot('valide', true)
                    ->latest('user_ceintures.date_obtention')
                    ->first();
    }

    /**
     * Historique des ceintures
     */
    public function historiqueCeintures()
    {
        return $this->ceintures()
                    ->wherePivot('valide', true)
                    ->orderBy('user_ceintures.date_obtention', 'asc');
    }

    // ÉVÉNEMENT : Assigner automatiquement le rôle "membre"
    protected static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            if (!$user->roles()->exists()) {
                $user->assignRole('membre');
            }
        });
    }

    // Accesseurs existants
    public function getAgeAttribute()
    {
        return $this->date_naissance ? $this->date_naissance->age : null;
    }

    public function getDerniereCeintureAttribute()
    {
        return $this->userCeintures()
            ->with('ceinture')
            ->where('valide', true)
            ->latest('date_obtention')
            ->first();
    }
}
