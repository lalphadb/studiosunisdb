<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membre extends Model
{
    use HasFactory;

    protected $fillable = [
        'prenom',
        'nom',
        'email',
        'telephone',
        'date_naissance',
        'adresse',
        'ville',
        'code_postal',
        'ecole_id',
        'date_inscription',
        'statut',
        'ceinture_actuelle_id',
        'contact_urgence',
        'telephone_urgence',
        'notes'
    ];

    protected $casts = [
        'date_naissance' => 'date',
        'date_inscription' => 'date'
    ];

    public function ecole()
    {
        return $this->belongsTo(Ecole::class);
    }

    public function derniereCeinture()
    {
        return $this->hasOne(MembreCeinture::class)->latest('date_obtention');
    }

    public function progressionsCeintures()
    {
        return $this->hasMany(MembreCeinture::class)->orderBy('date_obtention', 'desc');
    }

    public function ceinture_actuelle()
    {
        return $this->belongsTo(Ceinture::class, 'ceinture_actuelle_id');
    }

    /**
     * ✅ AJOUTER - Méthode manquante pour l'affichage
     */
    public function getCeintureActuellePourAffichage()
    {
        if ($this->ceinture_actuelle) {
            return $this->ceinture_actuelle;
        }
        
        if ($this->derniereCeinture && $this->derniereCeinture->ceinture) {
            return $this->derniereCeinture->ceinture;
        }
        
        return null;
    }

    public function cours()
    {
        return $this->belongsToMany(Cours::class, 'inscription_cours')
                    ->withPivot(['date_inscription', 'statut'])
                    ->withTimestamps();
    }

    public function presences()
    {
        return $this->hasMany(Presence::class);
    }

    public function seminaires()
    {
        return $this->belongsToMany(Seminaire::class, 'inscriptions_seminaires')
                    ->withPivot(['statut', 'date_inscription', 'montant_paye'])
                    ->withTimestamps();
    }

    public function getNomCompletAttribute()
    {
        return $this->prenom . ' ' . $this->nom;
    }

    public function getAgeAttribute()
    {
        return $this->date_naissance ? $this->date_naissance->age : null;
    }

    public function scopeParEcole($query, $ecoleId)
    {
        return $query->where('ecole_id', $ecoleId);
    }

    public function scopeActifs($query)
    {
        return $query->where('statut', 'actif');
    }
}
