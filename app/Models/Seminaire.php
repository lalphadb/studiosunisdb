<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Seminaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'description',
        'type',
        'date_debut',
        'date_fin',
        'heure_debut',
        'heure_fin',
        'lieu',
        'adresse_lieu',
        'ville_lieu',
        'instructeur',
        'prix',
        'niveau_requis',
        'inscription_ouverte',
        'materiel_requis'
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'prix' => 'decimal:2',
        'inscription_ouverte' => 'boolean'
    ];

    // Relations
    public function inscriptions()
    {
        return $this->hasMany(InscriptionSeminaire::class);
    }

    // Accesseurs pour format canadien français
    public function getDateDebutFormateeAttribute()
    {
        return $this->date_debut ? $this->date_debut->format('d/m/Y') : null;
    }

    public function getDateFinFormateeAttribute()
    {
        return $this->date_fin ? $this->date_fin->format('d/m/Y') : null;
    }

    public function getHeureDebutFormateeAttribute()
    {
        return $this->heure_debut ? Carbon::parse($this->heure_debut)->format('H:i') : null;
    }

    public function getHeureFinFormateeAttribute()
    {
        return $this->heure_fin ? Carbon::parse($this->heure_fin)->format('H:i') : null;
    }

    // Scope pour séminaires à venir
    public function scopeAVenir($query)
    {
        return $query->where('date_debut', '>=', now()->toDateString());
    }

    // Scope pour séminaires ouverts aux inscriptions
    public function scopeInscriptionOuverte($query)
    {
        return $query->where('inscription_ouverte', true);
    }

    // Nombre d'inscrits
    public function getNombreInscritsAttribute()
    {
        return $this->inscriptions()->count();
    }

    // Durée en heures
    public function getDureeHeuresAttribute()
    {
        if (!$this->heure_debut || !$this->heure_fin) return null;
        
        $debut = Carbon::parse($this->heure_debut);
        $fin = Carbon::parse($this->heure_fin);
        
        return $debut->diffInHours($fin);
    }
}
