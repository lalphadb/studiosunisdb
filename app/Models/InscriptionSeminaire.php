<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InscriptionSeminaire extends Model
{
    use HasFactory;

    protected $table = 'inscriptions_seminaires';

    protected $fillable = [
        'user_id', // Changé de membre_id à user_id
        'seminaire_id',
        'date_inscription',
        'statut',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'date_inscription' => 'date',
        ];
    }

    // Relations
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function seminaire(): BelongsTo
    {
        return $this->belongsTo(Seminaire::class);
    }
}
