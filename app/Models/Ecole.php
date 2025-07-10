<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Ecole extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom',
        'code',
        'adresse',
        'ville',
        'code_postal',
        'province',
        'pays',
        'telephone',
        'email',
        'site_web',
        'logo',
        'proprietaire_nom',
        'proprietaire_email',
        'proprietaire_telephone',
        'actif',
        'configuration',
        'date_creation',
        'numero_entreprise',
        'taxes_applicables',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'actif' => 'boolean',
        'configuration' => 'array',
        'date_creation' => 'date',
        'taxes_applicables' => 'array',
    ];

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        // Générer automatiquement le code école
        static::creating(function ($ecole) {
            if (empty($ecole->code)) {
                $ecole->code = 'E' . strtoupper(substr(str_replace(' ', '', $ecole->nom), 0, 3)) . rand(100, 999);
            }
        });
    }

    /**
     * Relation avec les utilisateurs
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Relation avec les cours
     */
    public function cours()
    {
        return $this->hasMany(Cours::class);
    }

    /**
     * Relation avec les ceintures
     */
    public function ceintures()
    {
        return $this->hasMany(Ceinture::class);
    }

    /**
     * Relation avec les séminaires
     */
    public function seminaires()
    {
        return $this->hasMany(Seminaire::class);
    }

    /**
     * Relation avec les paiements
     */
    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    /**
     * Relation avec les présences
     */
    public function presences()
    {
        return $this->hasMany(Presence::class);
    }

    /**
     * Accesseur pour l'URL du logo
     */
    public function getLogoUrlAttribute()
    {
        if ($this->logo) {
            return Storage::url($this->logo);
        }

        return asset('images/default-logo.png');
    }

    /**
     * Scope pour les écoles actives
     */
    public function scopeActives($query)
    {
        return $query->where('actif', true);
    }

    /**
     * Scope pour les écoles inactives
     */
    public function scopeInactives($query)
    {
        return $query->where('actif', false);
    }

    /**
     * Obtenir la configuration
     */
    public function getConfig($key, $default = null)
    {
        return data_get($this->configuration, $key, $default);
    }

    /**
     * Définir la configuration
     */
    public function setConfig($key, $value)
    {
        $config = $this->configuration ?? [];
        data_set($config, $key, $value);
        $this->configuration = $config;
        $this->save();
        
        return $this;
    }

    /**
     * Obtenir les statistiques
     */
    public function getStatistiques()
    {
        return [
            'total_users' => $this->users()->count(),
            'users_actifs' => $this->users()->where('actif', true)->count(),
            'total_cours' => $this->cours()->count(),
            'cours_actifs' => $this->cours()->where('actif', true)->count(),
            'total_seminaires' => $this->seminaires()->count(),
            'seminaires_planifies' => $this->seminaires()->where('statut', 'planifie')->count(),
            'total_paiements' => $this->paiements()->where('statut', 'valide')->count(),
            'montant_total_paiements' => $this->paiements()->where('statut', 'valide')->sum('montant'),
        ];
    }

    /**
     * Obtenir le nombre d'instructeurs
     */
    public function getNombreInstructeurs()
    {
        return $this->users()->role('instructeur')->count();
    }

    /**
     * Obtenir le nombre de membres
     */
    public function getNombreMembres()
    {
        return $this->users()->role('membre')->count();
    }

    /**
     * Obtenir les revenus du mois
     */
    public function getRevenusMois($mois = null, $annee = null)
    {
        $mois = $mois ?? now()->month;
        $annee = $annee ?? now()->year;

        return $this->paiements()
            ->where('statut', 'valide')
            ->whereMonth('date_paiement', $mois)
            ->whereYear('date_paiement', $annee)
            ->sum('montant');
    }

    /**
     * Obtenir les revenus de l'année
     */
    public function getRevenusAnnee($annee = null)
    {
        $annee = $annee ?? now()->year;

        return $this->paiements()
            ->where('statut', 'valide')
            ->whereYear('date_paiement', $annee)
            ->sum('montant');
    }

    /**
     * Vérifier si l'école a des taxes
     */
    public function hasTaxes()
    {
        return !empty($this->taxes_applicables);
    }

    /**
     * Calculer le montant avec taxes
     */
    public function calculerMontantAvecTaxes($montant)
    {
        if (!$this->hasTaxes()) {
            return $montant;
        }

        $totalTaxes = 0;
        foreach ($this->taxes_applicables as $taxe) {
            $totalTaxes += $montant * ($taxe['taux'] / 100);
        }

        return round($montant + $totalTaxes, 2);
    }

    /**
     * Obtenir l'adresse complète
     */
    public function getAdresseCompleteAttribute()
    {
        $parts = array_filter([
            $this->adresse,
            $this->ville,
            $this->province,
            $this->code_postal,
            $this->pays
        ]);

        return implode(', ', $parts);
    }

    /**
     * Vérifier si l'école peut être supprimée
     */
    public function canBeDeleted()
    {
        // Une école ne peut être supprimée que si elle n'a pas de données
        return $this->users()->count() === 0 &&
               $this->cours()->count() === 0 &&
               $this->seminaires()->count() === 0 &&
               $this->paiements()->count() === 0;
    }
}
