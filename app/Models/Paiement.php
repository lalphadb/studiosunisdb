<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;

    protected $fillable = [
        'membre_id',
        'inscription_cours_id',
        'montant',
        'type_paiement',
        'methode_paiement',
        'statut',
        'date_paiement',
        'date_echeance',
        'reference_transaction',
        'notes',
        'recu_url',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'date_paiement' => 'date',
        'date_echeance' => 'date',
    ];

    // Relations
    public function membre()
    {
        return $this->belongsTo(Membre::class);
    }

    public function inscriptionCours()
    {
        return $this->belongsTo(InscriptionCours::class);
    }

    // Scopes
    public function scopeConfirme($query)
    {
        return $query->where('statut', 'confirme');
    }

    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'attente');
    }

    // Attributs calculés
    public function getEstEnRetardAttribute()
    {
        return $this->date_echeance && $this->date_echeance < now() && $this->statut !== 'confirme';
    }
}
