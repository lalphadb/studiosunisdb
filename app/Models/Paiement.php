<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\MultiTenant;

class Paiement extends Model
{
    use HasFactory, MultiTenant;

    protected $table = 'paiements';

    protected $fillable = [
        'user_id',
        'cours_id',
        'ecole_id',
        'montant',
        'statut',
        'methode_paiement',
        'transaction_id',
        'date_paiement',
        'notes'
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'date_paiement' => 'datetime'
    ];

    protected $attributes = [
        'statut' => 'en_attente'
    ];

    // ===== RELATIONS =====

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cours(): BelongsTo
    {
        return $this->belongsTo(Cours::class);
    }
}
