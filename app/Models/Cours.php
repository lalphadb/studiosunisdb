<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Cours extends Model
{
    use HasFactory, LogsActivity;

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
        'prix',
        'instructeur_principal_id',
        'instructeur_assistant_id',
        'statut',
        'salle',
        'materiel_requis',
        'objectifs'
    ];

    protected $casts = [
        'age_min' => 'integer',
        'age_max' => 'integer',
        'capacite_max' => 'integer',
        'duree_minutes' => 'integer',
        'prix' => 'decimal:2',
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
        return $query->where('statut', 'actif');
    }

    public function scopeParEcole($query, $ecoleId)
    {
        return $query->where('ecole_id', $ecoleId);
    }

    public function scopeParType($query, $type)
    {
        return $query->where('type_cours', $type);
    }

    // Attributs calculés
    public function getNombreInscritsAttribute()
    {
        return $this->inscriptions()->where('statut', 'active')->count();
    }

    public function getPlacesRestantesAttribute()
    {
        return $this->capacite_max - $this->nombre_inscrits;
    }

    public function getEstCompletAttribute()
    {
        return $this->nombre_inscrits >= $this->capacite_max;
    }

    // Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty();
    }
}
