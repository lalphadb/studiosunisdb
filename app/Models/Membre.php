<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Membre extends Model
{
    use HasFactory;

    protected $table = 'membres';

    protected $fillable = [
        'ecole_id',
        'numero_membre',
        'prenom',
        'nom',
        'date_naissance',
        'telephone',
        'email',
        'adresse',
        'ville',
        'code_postal',
        'contact_urgence',
        'telephone_urgence',
        'date_inscription',
        'date_fin_inscription',
        'statut',
        'type_membre',
        'niveau_experience',
        'ceinture_actuelle_id',
        'notes',
        'photo_url',
        'consentement_photo',
        'consentement_email'
    ];

    protected $casts = [
        'date_naissance' => 'date',
        'date_inscription' => 'date',
        'date_fin_inscription' => 'date',
        'consentement_photo' => 'boolean',
        'consentement_email' => 'boolean'
    ];

    // Relations principales
    public function ecole()
    {
        return $this->belongsTo(Ecole::class);
    }

    /**
     * Ceinture actuelle (colonne ceinture_actuelle_id)
     */
    public function ceintureActuelle()
    {
        return $this->belongsTo(Ceinture::class, 'ceinture_actuelle_id');
    }

    /**
     * Relations avec les progressions de ceintures
     */
    public function progressionsCeintures(): HasMany
    {
        return $this->hasMany(MembreCeinture::class);
    }

    /**
     * Dernière ceinture obtenue via progressions (CORRIGÉE)
     */
    public function derniereCeinture(): HasOne
    {
        return $this->hasOne(MembreCeinture::class)->latestOfMany('date_obtention');
    }

    /**
     * Relations avec les cours
     */
    public function inscriptionsCours()
    {
        return $this->hasMany(InscriptionCours::class);
    }

    public function cours()
    {
        return $this->belongsToMany(Cours::class, 'inscriptions_cours')
                   ->withPivot(['date_inscription', 'status', 'montant_paye'])
                   ->withTimestamps();
    }

    /**
     * Relations avec les présences
     */
    public function presences()
    {
        return $this->hasMany(Presence::class);
    }

    /**
     * Relations avec les séminaires
     */
    public function inscriptionsSeminaires()
    {
        return $this->hasMany(InscriptionSeminaire::class);
    }

    public function seminaires()
    {
        return $this->belongsToMany(Seminaire::class, 'inscriptions_seminaires')
                   ->withPivot(['date_inscription', 'statut', 'montant_paye', 'certificat_obtenu'])
                   ->withTimestamps();
    }

    // Attributs calculés
    public function getAgeAttribute()
    {
        if (!$this->date_naissance) {
            return null;
        }
        return $this->date_naissance->age;
    }

    public function getNomCompletAttribute()
    {
        return $this->prenom . ' ' . $this->nom;
    }

    // Scopes
    public function scopeActifs($query)
    {
        return $query->where('statut', 'actif');
    }

    public function scopeParEcole($query, $ecole_id)
    {
        return $query->where('ecole_id', $ecole_id);
    }

    /**
     * Méthodes utiles pour les ceintures
     */
    public function getCeintureActuellePourAffichage()
    {
        return $this->derniereCeinture?->ceinture ?? $this->ceintureActuelle ?? null;
    }

    public function peutPasserCeinture($ceinture_id)
    {
        $ceintureActuelle = $this->getCeintureActuellePourAffichage();
        $nouvelleCeinture = Ceinture::find($ceinture_id);
        
        if (!$ceintureActuelle || !$nouvelleCeinture) {
            return false;
        }
        
        return $nouvelleCeinture->niveau > $ceintureActuelle->niveau;
    }
}
