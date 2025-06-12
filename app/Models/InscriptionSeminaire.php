<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InscriptionSeminaire extends Model
{
    use HasFactory;

    protected $table = 'inscriptions_seminaires';

    protected $fillable = [
        'seminaire_id',
        'membre_id',
        'date_inscription', 
        'statut',
        'montant_paye',
        'certificat_obtenu',
        'notes'
    ];

    protected $casts = [
        'date_inscription' => 'date',
        'montant_paye' => 'decimal:2',
        'certificat_obtenu' => 'boolean'
    ];

    public function seminaire()
    {
        return $this->belongsTo(Seminaire::class);
    }

    public function membre()
    {
        return $this->belongsTo(Membre::class);
    }
}
