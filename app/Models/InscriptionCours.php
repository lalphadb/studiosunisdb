<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasEcoleScope;

class InscriptionCours extends Model
{
    use HasFactory, HasEcoleScope;

    protected $table = 'inscriptions_cours';

    protected $fillable = [
        'user_id',
        'cours_id',
        'ecole_id',
        'date_inscription',
        'date_fin',
        'statut',
        'notes',
    ];

    protected $casts = [
        'date_inscription' => 'date',
        'date_fin' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cours()
    {
        return $this->belongsTo(Cours::class);
    }

    public function ecole()
    {
        return $this->belongsTo(Ecole::class);
    }

    public function scopeActifs($query)
    {
        return $query->where('statut', 'actif');
    }
}
