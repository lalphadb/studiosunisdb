<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'date_naissance' => 'date',
        'date_inscription' => 'date',
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
     * Relation avec le chef de famille
     */
    public function chefDeFamille()
    {
        return $this->belongsTo(User::class, 'famille_principale_id');
    }

    /**
     * Relation avec les membres de la famille
     */
    public function membresDeFamille()
    {
        return $this->hasMany(User::class, 'famille_principale_id');
    }

    /**
     * Relation avec les utilisateur_ceintures (selon structure DB réelle)
     */
    public function utilisateurCeintures()
    {
        return $this->hasMany(UtilisateurCeinture::class);
    }

    /**
     * Relation avec les cours
     */
    public function cours()
    {
        return $this->belongsToMany(Cours::class, 'inscriptions_cours')
            ->withPivot('date_inscription', 'date_fin', 'statut')
            ->withTimestamps();
    }

    /**
     * Relation avec les inscriptions cours
     */
    public function inscriptionsCours()
    {
        return $this->hasMany(InscriptionCours::class);
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
     * Relation avec les séminaires
     */
    public function seminaires()
    {
        return $this->belongsToMany(Seminaire::class, 'inscriptions_seminaires')
            ->withPivot('date_inscription', 'statut', 'notes')
            ->withTimestamps();
    }

    /**
     * Relation avec les cours en tant qu'instructeur
     */
    public function coursInstructeur()
    {
        return $this->hasMany(Cours::class, 'instructeur_id');
    }

    /**
     * Obtenir la ceinture actuelle de l'utilisateur
     */
    public function ceintureActuelle()
    {
        return $this->hasMany(UtilisateurCeinture::class, 'user_id')
            ->with('ceinture')
            ->latest('date_obtention')
            ->first();
    }

    /**
     * Obtenir l'âge de l'utilisateur
     */
    public function getAgeAttribute()
    {
        return $this->date_naissance ? $this->date_naissance->age : null;
    }

    /**
     * Vérifier si l'utilisateur est actif
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * Scope pour les utilisateurs actifs
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope pour les utilisateurs d'une école spécifique
     */
    public function scopeByEcole($query, $ecoleId)
    {
        return $query->where('ecole_id', $ecoleId);
    }
}
