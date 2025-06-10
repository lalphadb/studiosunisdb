<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Presence extends Model
{
    use HasFactory;

    protected $fillable = [
        'membre_id',
        'cours_id',
        'session_date',
        'heure_arrivee',
        'heure_depart',
        'statut',
        'notes',
        'prise_par_user_id',
        'methode_pointage',
        'ip_address'
    ];

    protected $casts = [
        'session_date' => 'date',
        'heure_arrivee' => 'datetime:H:i',
        'heure_depart' => 'datetime:H:i',
    ];

    // Relations
    public function membre()
    {
        return $this->belongsTo(Membre::class);
    }

    public function cours()
    {
        return $this->belongsTo(Cours::class);
    }

    public function priseParUser()
    {
        return $this->belongsTo(User::class, 'prise_par_user_id');
    }

    // Scopes
    public function scopePresent($query)
    {
        return $query->where('statut', 'present');
    }

    public function scopeParDate($query, $date)
    {
        return $query->where('session_date', $date);
    }

    public function scopeParCours($query, $coursId)
    {
        return $query->where('cours_id', $coursId);
    }

    // Attributs calculés
    public function getDureePresenceAttribute()
    {
        if ($this->heure_arrivee && $this->heure_depart) {
            return $this->heure_arrivee->diffInMinutes($this->heure_depart);
        }
        return null;
    }

    public function getEstEnRetardAttribute()
    {
        return $this->statut === 'retard';
    }
}
