<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membre extends Model
{
    use HasFactory;

    protected $fillable = [
        'ecole_id',
        'nom',
        'prenom', 
        'email',
        'telephone',
        'date_naissance',
        'sexe',
        'adresse',
        'ville',
        'code_postal',
        'contact_urgence_nom',
        'contact_urgence_telephone',
        'date_inscription',
        'active',
        'notes'
    ];

    protected $casts = [
        'date_naissance' => 'date',
        'date_inscription' => 'date',
        'active' => 'boolean'
    ];

    // Relations
    public function ecole()
    {
        return $this->belongsTo(Ecole::class);
    }
}
