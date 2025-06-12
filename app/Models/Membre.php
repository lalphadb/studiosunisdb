<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function ceintureActuelle()
    {
        return $this->belongsTo(Ceinture::class, 'ceinture_actuelle_id');
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
}
