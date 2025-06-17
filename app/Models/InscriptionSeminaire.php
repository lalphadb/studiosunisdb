<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InscriptionSeminaire extends Model
{
    use HasFactory;

    // ✅ GARDER le nom existant de la table
    protected $table = 'inscriptions_seminaires';

    protected $fillable = [
        'seminaire_id',
        'membre_id',
        'ecole_id',
        'date_inscription',
        'statut',
        'montant_paye',
        'notes',  // ✅ Champ qui existe dans la DB
        'notes_participant',
        'date_paiement',
        'certificat_obtenu',
    ];

    protected $casts = [
        'date_inscription' => 'date',
        'date_paiement' => 'datetime',
        'montant_paye' => 'decimal:2',
        'certificat_obtenu' => 'boolean',
    ];

    public function seminaire()
    {
        return $this->belongsTo(Seminaire::class);
    }

    public function membre()
    {
        return $this->belongsTo(Membre::class);
    }

    public function ecole()
    {
        return $this->belongsTo(Ecole::class);
    }

    /**
     * 🎯 Scope - Par Statut
     */
    public function scopeParStatut($query, $statut)
    {
        return $query->where('statut', $statut);
    }

    /**
     * 🎯 Scope - Présents
     */
    public function scopePresents($query)
    {
        return $query->where('statut', 'present');
    }

    /**
     * 🎯 Scope - Absents
     */
    public function scopeAbsents($query)
    {
        return $query->where('statut', 'absent');
    }
}
