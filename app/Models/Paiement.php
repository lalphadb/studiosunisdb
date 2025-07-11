<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasEcoleScope;

class Paiement extends Model
{
    use HasFactory, HasEcoleScope;

    protected $fillable = [
        'user_id',
        'ecole_id',
        'montant',
        'date_paiement',
        'type_paiement',
        'methode_paiement',
        'reference_paiement',
        'statut',
        'description',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'date_paiement' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ecole()
    {
        return $this->belongsTo(Ecole::class);
    }

    public function scopeCompletes($query)
    {
        return $query->where('statut', 'complete');
    }
}
