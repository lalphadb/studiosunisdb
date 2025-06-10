<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seminaire extends Model
{
    use HasFactory;

    protected $table = 'seminaires';

    protected $fillable = [
        'nom',
        'description',
        'type_seminaire',
        'niveau_requis',
        'formateur',
        'date_debut',
        'date_fin',
        'lieu',
        'ecole_organisatrice_id',
        'capacite_max',
        'prix',
        'statut',
        'image_url',
        'programme',
        'materiel_fourni'
    ];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
        'prix' => 'decimal:2'
    ];

    public function ecoleOrganisatrice()
    {
        return $this->belongsTo(Ecole::class, 'ecole_organisatrice_id');
    }

    public function inscriptions()
    {
        return $this->hasMany(InscriptionSeminaire::class);
    }

    public function membres()
    {
        return $this->belongsToMany(Membre::class, 'inscriptions_seminaires')
                    ->withPivot(['date_inscription', 'statut', 'montant_paye', 'certificat_obtenu', 'notes'])
                    ->withTimestamps();
    }

    // Attributs calculés
    public function getPlacesRestantesAttribute()
    {
        return $this->capacite_max - $this->inscriptions()->where('statut', 'confirmee')->count();
    }

    public function getEstCompletAttribute()
    {
        return $this->places_restantes <= 0;
    }

    // Scopes
    public function scopeOuverts($query)
    {
        return $query->where('statut', 'ouvert');
    }

    public function scopeAVenir($query)
    {
        return $query->where('date_debut', '>', now());
    }
}
