<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasEcoleScope;

class InscriptionSeminaire extends Model
{
    use HasFactory, HasEcoleScope;

    protected $table = 'inscriptions_seminaires';

    protected $fillable = [
        'seminaire_id',
        'user_id',
        'ecole_id',
        'date_inscription',
        'statut',
        'paiement_id',
        'notes',
    ];

    protected $casts = [
        'date_inscription' => 'date',
    ];

    public function seminaire()
    {
        return $this->belongsTo(Seminaire::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ecole()
    {
        return $this->belongsTo(Ecole::class);
    }

    public function paiement()
    {
        return $this->belongsTo(Paiement::class);
    }
}
