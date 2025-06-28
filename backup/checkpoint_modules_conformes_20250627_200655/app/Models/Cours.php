<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cours extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ecole_id',
        'nom',
        'description',
        'niveau',
        'capacite_max',
        'duree_minutes',
        'prix',
        'instructeur',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean',
        'prix' => 'decimal:2',
        'capacite_max' => 'integer',
        'duree_minutes' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relations
     */
    public function ecole()
    {
        return $this->belongsTo(Ecole::class);
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

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeByEcole($query, $ecoleId)
    {
        return $query->where('ecole_id', $ecoleId);
    }

    public function scopeByNiveau($query, $niveau)
    {
        return $query->where('niveau', $niveau);
    }

    /**
     * Accesseurs
     */
    public function getNiveauFrancaisAttribute()
    {
        return match($this->niveau) {
            'debutant' => 'Débutant',
            'intermediaire' => 'Intermédiaire',
            'avance' => 'Avancé',
            'tous_niveaux' => 'Tous niveaux',
            default => ucfirst($this->niveau)
        };
    }

    public function getTauxOccupationAttribute()
    {
        if ($this->capacite_max <= 0) {
            return 0;
        }

        return round(($this->inscriptions_count / $this->capacite_max) * 100, 1);
    }

    /**
     * Mutateurs
     */
    public function setPrixAttribute($value)
    {
        $this->attributes['prix'] = $value ? round($value, 2) : null;
    }
}
