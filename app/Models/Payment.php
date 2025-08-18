<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'type',
        'amount',
        'description',
        'date_echeance',
        'date_paiement',
        'status',
        'methode_paiement',
        'reference_transaction',
        'notes',
    ];

    protected $casts = [
        'date_echeance' => 'date',
        'date_paiement' => 'date',
        'amount' => 'decimal:2',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
