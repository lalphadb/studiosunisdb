<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InscriptionSeminaire extends Model
{
    use HasFactory;

    protected $table = 'inscriptions_seminaires';

    protected $fillable = [
        'user_id',
        'seminaire_id',
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

    public function seminaire()
    {
        return $this->belongsTo(Seminaire::class);
    }
}
