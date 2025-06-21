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
        'province',
        'contact_urgence_nom',
        'contact_urgence_telephone',
        'ceinture_actuelle_id',
        'date_inscription',
        'active',
        'notes',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_naissance' => 'date',
            'date_inscription' => 'date',
            'active' => 'boolean',
        ];
    }

    /**
     * Relation avec l'école
     */
    public function ecole()
    {
        return $this->belongsTo(Ecole::class);
    }

    /**
     * Ceinture actuelle
     */
    public function ceinture_actuelle()
    {
        return $this->belongsTo(Ceinture::class, 'ceinture_actuelle_id');
    }

    /**
     * Historique des ceintures
     */
    public function membre_ceintures()
    {
        return $this->hasMany(MembreCeinture::class, 'user_id');
    }

    /**
     * Présences
     */
    public function presences()
    {
        return $this->hasMany(Presence::class, 'user_id');
    }

    /**
     * Inscriptions aux cours
     */
    public function inscriptions_cours()
    {
        return $this->hasMany(InscriptionCours::class, 'user_id');
    }

    /**
     * Inscriptions aux séminaires
     */
    public function inscriptions_seminaires()
    {
        return $this->hasMany(InscriptionSeminaire::class, 'user_id');
    }

    /**
     * Paiements
     */
    public function paiements()
    {
        return $this->hasMany(Paiement::class, 'user_id');
    }

    /**
     * Cours où l'utilisateur est instructeur principal
     */
    public function cours_instructeur_principal()
    {
        return $this->hasMany(Cours::class, 'instructeur_principal_id');
    }

    /**
     * Cours où l'utilisateur est instructeur assistant
     */
    public function cours_instructeur_assistant()
    {
        return $this->hasMany(Cours::class, 'instructeur_assistant_id');
    }

    /**
     * Scope pour récupérer uniquement les utilisateurs qui sont des "membres" (karatékas)
     */
    public function scopeMembres($query)
    {
        return $query->whereHas('roles', function($q) {
            $q->whereIn('name', ['membre', 'instructeur']);
        });
    }

    /**
     * Scope pour récupérer uniquement les vrais membres (pas instructeurs)
     */
    public function scopeMembresOnly($query)
    {
        return $query->whereHas('roles', function($q) {
            $q->where('name', 'membre');
        });
    }

    /**
     * Scope pour récupérer les instructeurs
     */
    public function scopeInstructeurs($query)
    {
        return $query->whereHas('roles', function($q) {
            $q->whereIn('name', ['instructeur', 'admin']);
        });
    }

    /**
     * Scope pour filtrer par école
     */
    public function scopeForEcole($query, $ecole_id)
    {
        return $query->where('ecole_id', $ecole_id);
    }

    /**
     * Scope pour les membres actifs
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope pour les utilisateurs avec un rôle spécifique
     */
    public function scopeWithRole($query, $role)
    {
        return $query->whereHas('roles', function($q) use ($role) {
            $q->where('name', $role);
        });
    }

    /**
     * Obtenir l'âge à partir de la date de naissance
     */
    public function getAgeAttribute()
    {
        return $this->date_naissance ? $this->date_naissance->age : null;
    }

    /**
     * Obtenir le nom complet formaté
     */
    public function getNomCompletAttribute()
    {
        return $this->name;
    }

    /**
     * Vérifier si l'utilisateur peut gérer une école
     */
    public function canManageEcole($ecole_id)
    {
        if ($this->hasRole('superadmin')) {
            return true;
        }
        
        return $this->ecole_id == $ecole_id;
    }

    /**
     * Obtenir le taux de présence (approximatif)
     */
    public function getTauxPresenceAttribute()
    {
        $totalCours = $this->inscriptions_cours()->count();
        if ($totalCours == 0) return 0;
        
        $presences = $this->presences()->where('present', true)->count();
        return round(($presences / $totalCours) * 100, 1);
    }

    /**
     * Vérifier si c'est un membre (pas admin/instructeur)
     */
    public function isMembre()
    {
        return $this->hasRole('membre');
    }

    /**
     * Vérifier si c'est un instructeur
     */
    public function isInstructeur()
    {
        return $this->hasAnyRole(['instructeur', 'admin', 'superadmin']);
    }

    /**
     * Vérifier si c'est un admin d'école
     */
    public function isAdmin()
    {
        return $this->hasAnyRole(['admin', 'superadmin']);
    }

    /**
     * Activité des logs
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'ecole_id', 'ceinture_actuelle_id'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
