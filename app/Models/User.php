<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'adresse',
        'date_naissance',
        'genre',
        'personne_contact_urgence',
        'telephone_urgence',
        'ceinture_actuelle_id',
        'date_debut',
        'statut',
        'notes',
        'nom_famille',
        'contact_principal_famille',
        'telephone_principal_famille',
        'notes_famille'
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
            'date_naissance' => 'date',
            'date_debut' => 'date',
        ];
    }

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

    /**
     * Relation many-to-many avec les ceintures via la table pivot user_ceintures
     */
    public function ceintures(): BelongsToMany
    {
        return $this->belongsToMany(Ceinture::class, 'user_ceintures')
                    ->withPivot([
                        'date_obtention', 
                        'examinateur', 
                        'lieu_examen', 
                        'notes',
                        'validation_officielle',
                        'certificat_numero'
                    ])
                    ->withTimestamps()
                    ->orderBy('user_ceintures.date_obtention', 'desc');
    }

    /**
     * Récupère la ceinture actuelle avec validation officielle
     */
    public function ceintureActuelleValidee()
    {
        return $this->ceintures()
                    ->wherePivot('validation_officielle', true)
                    ->orderBy('user_ceintures.date_obtention', 'desc')
                    ->first();
    }

    /**
     * Historique complet des ceintures obtenues
     */
    public function historiqueCeintures()
    {
        return $this->ceintures()->orderBy('user_ceintures.date_obtention', 'asc');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            // Assigner automatiquement la ceinture blanche aux nouveaux utilisateurs
            if (!$user->ceinture_actuelle_id) {
                $ceintureBlanche = Ceinture::where('nom', 'like', '%Blanche%')->first();
                if ($ceintureBlanche) {
                    $user->update(['ceinture_actuelle_id' => $ceintureBlanche->id]);
                }
            }
        });
    }

    public function getAgeAttribute()
    {
        return $this->date_naissance ? $this->date_naissance->age : null;
    }

    public function getDerniereCeintureAttribute()
    {
        return $this->ceintures()->orderBy('user_ceintures.date_obtention', 'desc')->first();
    }

    /**
     * Les inscriptions aux cours de cet utilisateur
     */
    public function inscriptionsCours(): HasMany
    {
        return $this->hasMany(InscriptionCours::class);
    }

    /**
     * Les inscriptions aux séminaires de cet utilisateur
     */
    public function inscriptionsSeminaires(): HasMany
    {
        return $this->hasMany(InscriptionSeminaire::class);
    }

    /**
     * Les présences de cet utilisateur
     */
    public function presences(): HasMany
    {
        return $this->hasMany(Presence::class);
    }

    /**
     * Les paiements de cet utilisateur
     */
    public function paiements(): HasMany
    {
        return $this->hasMany(Paiement::class);
    }

    /**
     * Obtenir les membres de la famille (par nom_famille ou adresse)
     */
    public function getMembresFamilleAttribute()
    {
        if ($this->nom_famille) {
            return User::where('ecole_id', $this->ecole_id)
                ->where('nom_famille', $this->nom_famille)
                ->where('id', '!=', $this->id)
                ->get();
        } elseif ($this->adresse && strlen($this->adresse) > 10) {
            return User::where('ecole_id', $this->ecole_id)
                ->where('adresse', $this->adresse)
                ->where('id', '!=', $this->id)
                ->get();
        }
        
        return collect();
    }
}
