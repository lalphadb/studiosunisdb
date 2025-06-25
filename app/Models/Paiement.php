<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ecole_id',
        'processed_by_user_id',
        'reference_interne',
        'type_paiement',
        'motif',
        'description',
        'montant',
        'frais',
        'montant_net',
        'email_expediteur',
        'nom_expediteur',
        'reference_interac',
        'message_interac',
        'statut',
        'date_facture',
        'date_echeance',
        'date_reception',
        'date_validation',
        'periode_facturation',
        'annee_fiscale',
        'recu_fiscal_emis',
        'metadonnees',
        'notes_admin'
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'frais' => 'decimal:2',
        'montant_net' => 'decimal:2',
        'date_facture' => 'datetime',
        'date_echeance' => 'datetime',
        'date_reception' => 'datetime',
        'date_validation' => 'datetime',
        'recu_fiscal_emis' => 'boolean',
        'metadonnees' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ecole()
    {
        return $this->belongsTo(Ecole::class);
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by_user_id');
    }
}
