<?php

namespace App\Models;

use App\Traits\HasEcoleScope;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasEcoleScope;

    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'password',
        'telephone',
        'date_naissance',
        'adresse',
        'ville',
        'code_postal',
        'province',
        'pays',
        'ecole_id',
        'actif',
        'photo',
        'code_utilisateur',
        'contact_urgence_nom',
        'contact_urgence_telephone',
        'notes',
    ];

    /**
     * Les attributs qui doivent être cachés pour la sérialisation.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_naissance' => 'date',
        'actif' => 'boolean',
        'password' => 'hashed',
    ];

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        // Générer automatiquement le code utilisateur
        static::creating(function ($user) {
            if (empty($user->code_utilisateur)) {
                $user->code_utilisateur = 'U' . str_pad(User::max('id') + 1, 6, '0', STR_PAD_LEFT);
            }
        });
    }

    /**
     * Relation avec l'école
     */
    public function ecole()
    {
        return $this->belongsTo(Ecole::class);
    }

    /**
     * Relation avec les ceintures
     */
    public function ceintures()
    {
        return $this->hasMany(UserCeinture::class)->orderBy('date_obtention', 'desc');
    }

    /**
     * Ceinture actuelle
     */
    public function ceintureActuelle()
    {
        return $this->hasOne(UserCeinture::class)->latestOfMany('date_obtention');
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
     * Relation avec les inscriptions aux cours
     */
    public function inscriptionsCours()
    {
        return $this->hasMany(InscriptionCours::class);
    }

    /**
     * Relation avec les cours (via inscriptions)
     */
    public function cours()
    {
        return $this->belongsToMany(Cours::class, 'inscription_cours')
            ->withPivot('date_debut', 'date_fin', 'actif')
            ->withTimestamps();
    }

    /**
     * Relation avec les inscriptions aux séminaires
     */
    public function inscriptionsSeminaires()
    {
        return $this->hasMany(InscriptionSeminaire::class);
    }

    /**
     * Relation avec les séminaires (via inscriptions)
     */
    public function seminaires()
    {
        return $this->belongsToMany(Seminaire::class, 'inscription_seminaires')
            ->withPivot('statut_paiement', 'montant_paye', 'date_paiement')
            ->withTimestamps();
    }

    /**
     * Relation famille - membres
     */
    public function familleMembres()
    {
        return $this->belongsToMany(User::class, 'famille_membres', 'responsable_id', 'membre_id')
            ->withPivot('relation')
            ->withTimestamps();
    }

    /**
     * Relation famille - responsable
     */
    public function familleResponsable()
    {
        return $this->belongsToMany(User::class, 'famille_membres', 'membre_id', 'responsable_id')
            ->withPivot('relation')
            ->withTimestamps();
    }

    /**
     * Accesseur pour le nom complet
     */
    public function getNomCompletAttribute()
    {
        return $this->prenom . ' ' . $this->nom;
    }

    /**
     * Accesseur pour l'URL de l'avatar
     */
    public function getAvatarUrlAttribute()
    {
        if ($this->photo) {
            return Storage::url($this->photo);
        }

        return asset('images/default-avatar.png');
    }

    /**
     * Vérifier si l'utilisateur est admin
     */
    public function getIsAdminAttribute()
    {
        return $this->hasAnyRole(['superadmin', 'admin_ecole']);
    }

    /**
     * Vérifier si l'utilisateur est superadmin
     */
    public function isSuperAdmin()
    {
        return $this->hasRole('superadmin');
    }

    /**
     * Vérifier si l'utilisateur est admin école
     */
    public function isAdminEcole()
    {
        return $this->hasRole('admin_ecole');
    }

    /**
     * Vérifier si l'utilisateur est instructeur
     */
    public function isInstructeur()
    {
        return $this->hasRole('instructeur');
    }

    /**
     * Scope pour les utilisateurs actifs
     */
    public function scopeActifs($query)
    {
        return $query->where('actif', true);
    }

    /**
     * Scope pour les utilisateurs inactifs
     */
    public function scopeInactifs($query)
    {
        return $query->where('actif', false);
    }

    /**
     * Scope pour les instructeurs
     */
    public function scopeInstructeurs($query)
    {
        return $query->role('instructeur');
    }

    /**
     * Scope pour les membres
     */
    public function scopeMembres($query)
    {
        return $query->role('membre');
    }

    /**
     * Scope pour recherche
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('nom', 'like', "%{$search}%")
              ->orWhere('prenom', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('telephone', 'like', "%{$search}%")
              ->orWhere('code_utilisateur', 'like', "%{$search}%");
        });
    }

    /**
     * Obtenir le solde du compte
     */
    public function getSoldeAttribute()
    {
        return $this->paiements()
            ->where('statut', 'valide')
            ->sum('montant');
    }

    /**
     * Obtenir les statistiques de présence
     */
    public function getStatistiquesPresences($dateDebut = null, $dateFin = null)
    {
        $query = $this->presences();

        if ($dateDebut) {
            $query->whereHas('sessionCours', function ($q) use ($dateDebut) {
                $q->where('date', '>=', $dateDebut);
            });
        }

        if ($dateFin) {
            $query->whereHas('sessionCours', function ($q) use ($dateFin) {
                $q->where('date', '<=', $dateFin);
            });
        }

        $total = $query->count();
        $presents = $query->where('status', 'present')->count();
        $absents = $query->where('status', 'absent')->count();
        $retards = $query->where('status', 'retard')->count();
        $excuses = $query->where('status', 'excuse')->count();

        return [
            'total' => $total,
            'presents' => $presents,
            'absents' => $absents,
            'retards' => $retards,
            'excuses' => $excuses,
            'taux_presence' => $total > 0 ? round(($presents / $total) * 100, 2) : 0
        ];
    }

    /**
     * Vérifier si l'utilisateur peut accéder à une école
     */
    public function canAccessEcole($ecoleId)
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return $this->ecole_id == $ecoleId;
    }

    /**
     * Obtenir les cours actifs
     */
    public function getCoursActifs()
    {
        return $this->cours()->wherePivot('actif', true)->get();
    }

    /**
     * Vérifier si l'utilisateur est inscrit à un cours
     */
    public function estInscritAuCours($coursId)
    {
        return $this->cours()
            ->where('cours.id', $coursId)
            ->wherePivot('actif', true)
            ->exists();
    }

    /**
     * Obtenir le dernier paiement
     */
    public function getDernierPaiement()
    {
        return $this->paiements()
            ->where('statut', 'valide')
            ->latest('date_paiement')
            ->first();
    }

    /**
     * Vérifier si l'utilisateur est à jour dans ses paiements
     */
    public function estAJourPaiements()
    {
        $dernierPaiement = $this->getDernierPaiement();

        if (!$dernierPaiement) {
            return false;
        }

        return $dernierPaiement->periode_fin >= now();
    }
}
