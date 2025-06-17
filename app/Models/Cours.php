<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cours extends Model
{
    use HasFactory;

    protected $table = 'cours';
    
    protected $fillable = [
        'ecole_id',
        'nom',
        'description',
        'type_cours',
        'niveau_requis',
        'age_min',
        'age_max',
        'capacite_max',
        'duree_minutes',
        'prix_mensuel',
        'prix_session',
        'instructeur_principal_id',
        'instructeur_assistant_id',
        'status',
        'salle',
        'materiel_requis',
        'objectifs',
        'date_debut',
        'date_fin',
        'session_id'
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'prix_mensuel' => 'decimal:2',
        'prix_session' => 'decimal:2'
    ];

    // Relations
    public function ecole()
    {
        return $this->belongsTo(Ecole::class);
    }

    public function instructeurPrincipal()
    {
        return $this->belongsTo(User::class, 'instructeur_principal_id');
    }

    public function instructeurAssistant()
    {
        return $this->belongsTo(User::class, 'instructeur_assistant_id');
    }

    public function horaires()
    {
        return $this->hasMany(CoursHoraire::class);
    }

    public function inscriptions()
    {
        return $this->hasMany(InscriptionCours::class);
    }

    public function presences()
    {
        return $this->hasMany(Presence::class);
    }

    // Scopes
    public function scopeActif($query)
    {
        return $query->where('status', 'actif');
    }

    // Attributs calculés
    public function getPlacesDisponiblesAttribute()
    {
        return $this->capacite_max - $this->inscriptions()->where('status', 'active')->count();
    }

    public function getEstCompletAttribute()
    {
        return $this->places_disponibles <= 0;
    }

    public function getHoraireFormatteAttribute()
    {
        if ($this->horaires->count() > 0) {
            return $this->horaires->map(function($horaire) {
                return ucfirst($horaire->jour_semaine) . ' ' . 
                       $horaire->heure_debut . '-' . 
                       $horaire->heure_fin;
            })->implode(', ');
        }
        
        // Fallback sur les colonnes directes
        if ($this->jour_semaine && $this->heure_debut) {
            return ucfirst($this->jour_semaine) . ' ' . 
                   $this->heure_debut . '-' . 
                   $this->heure_fin;
        }
        
        return 'Non défini';
    }
}
