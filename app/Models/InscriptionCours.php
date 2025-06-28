<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InscriptionCours extends Model
{
    use HasFactory;

    protected $table = 'inscriptions_cours';

    protected $fillable = [
        'user_id',
        'cours_id',
        'date_inscription',
        'statut',
        'notes'
    ];

    protected $casts = [
        'date_inscription' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cours()
    {
        return $this->belongsTo(Cours::class);
    }
}
