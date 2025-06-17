<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InscriptionCours extends Model
{
    use HasFactory;

    protected $table = 'inscriptions_cours';

    protected $fillable = [
        'cours_id',
        'membre_id',
        'date_inscription',
        'status',
        'montant_paye',
        'date_debut_facturation',
        'date_fin_facturation',
        'notes'
    ];

    protected $casts = [
        'date_inscription' => 'date',
        'date_debut_facturation' => 'date',
        'date_fin_facturation' => 'date',
        'montant_paye' => 'decimal:2'
    ];

    public function cours()
    {
        return $this->belongsTo(Cours::class);
    }

    public function membre()
    {
        return $this->belongsTo(Membre::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
