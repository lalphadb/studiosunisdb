<?php

namespace App\Models;

use App\Traits\HasEcoleScope;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasEcoleScope, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom',
        'prenom', 
        'email',
        'password',
        'ecole_id',
        'code_utilisateur',
        'telephone',
        'date_naissance',
        'sexe',
        'adresse',
        'ville',
        'province',
        'code_postal',
        'pays',
        'photo',
        'contact_urgence_nom',
        'contact_urgence_telephone',
        'notes',
        'actif',
        'derniere_connexion',
        'email_verified_at'
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
        'actif' => 'boolean',
        'date_naissance' => 'date',
        'derniere_connexion' => 'datetime',
    ];

    /**
     * Determine if the user can access the Filament panel.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // L'utilisateur doit être actif pour accéder au panel
        return $this->actif;
    }

    /**
     * Get the user's full name.
     */
    public function getNomCompletAttribute(): string
    {
        return "{$this->prenom} {$this->nom}";
    }

    /**
     * Relations
     */
    public function ecole()
    {
        return $this->belongsTo(Ecole::class);
    }

    public function ceintures()
    {
        return $this->hasMany(UserCeinture::class);
    }

    public function inscriptions()
    {
        return $this->hasMany(InscriptionCours::class);
    }

    public function presences()
    {
        return $this->hasMany(Presence::class);
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    

    public function coursInstructeur()
    {
        return $this->hasMany(CoursHoraire::class, 'instructeur_id');
    }

    public function sessionsInstructeur()
    {
        return $this->hasMany(SessionCours::class, 'instructeur_id');
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->code_utilisateur)) {
                $lastCode = static::withTrashed()
                    ->where('code_utilisateur', 'like', 'U%')
                    ->orderBy('code_utilisateur', 'desc')
                    ->value('code_utilisateur');

                $nextNumber = 1;
                if ($lastCode) {
                    $nextNumber = intval(substr($lastCode, 1)) + 1;
                }

                $user->code_utilisateur = 'U' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
            }

            if (empty($user->pays)) {
                $user->pays = 'Canada';
            }
        });
    }

    /**
     * Get the user name for Filament
     */
    


    /**
     * Get the name attribute for Filament
     * Filament utilise cette méthode pour afficher le nom de l'utilisateur
     */
    public function getFilamentName(): string
    {
        return $this->nom_complet ?: $this->email;
    }

    /**
     * Get name attribute (fallback pour Filament)
     */
    public function getNameAttribute(): string
    {
        return $this->nom_complet ?: $this->email;
    }

}