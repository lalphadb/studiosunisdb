<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InscriptionCours extends Model
{
    use HasFactory;

    protected $fillable = [
        'membre_id',
        'cours_id',
        'date_inscription',
        'date_debut',
        'date_fin',
        'statut',
        'tarif',
        'mode_paiement',
        'notes'
    ];

    protected $casts = [
        'date_inscription' => 'date',
        'date_debut' => 'date',
        'date_fin' => 'date',
        'tarif' => 'decimal:2',
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

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('statut', 'active');
    }

    // Attributs calculés
    public function getEstValideAttribute()
    {
        return $this->statut === 'active' && 
               $this->date_debut <= now() &&
               (!$this->date_fin || $this->date_fin >= now());
    }
}
