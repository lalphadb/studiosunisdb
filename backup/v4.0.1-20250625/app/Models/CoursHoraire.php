<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoursHoraire extends Model
{
    use HasFactory;

    protected $table = 'cours_horaires';

    protected $fillable = [
        'cours_id',
        'jour_semaine',
        'heure_debut',
        'heure_fin',
        'date_debut',
        'date_fin',
        'active',
    ];

    protected $casts = [
        'heure_debut' => 'string',
        'heure_fin' => 'string',
        'date_debut' => 'date',
        'date_fin' => 'date',
        'active' => 'boolean',
    ];

    /**
     * Relation avec le cours
     */
    public function cours()
    {
        return $this->belongsTo(Cours::class);
    }

    /**
     * Scope pour les horaires actifs
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Obtenir le libellé du jour en français
     */
    public function getJourFrancaisAttribute()
    {
        $jours = [
            'lundi' => 'Lundi',
            'mardi' => 'Mardi',
            'mercredi' => 'Mercredi',
            'jeudi' => 'Jeudi',
            'vendredi' => 'Vendredi',
            'samedi' => 'Samedi',
            'dimanche' => 'Dimanche',
        ];

        return $jours[$this->jour_semaine] ?? $this->jour_semaine;
    }

    /**
     * Obtenir l'horaire formaté
     */
    public function getHoraireFormatteAttribute()
    {
        return $this->jour_francais . ' de ' . $this->heure_debut . ' à ' . $this->heure_fin;
    }
}
