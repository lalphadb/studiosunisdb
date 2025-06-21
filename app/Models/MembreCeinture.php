<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MembreCeinture extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', // Changé de membre_id à user_id
        'ceinture_id',
        'date_obtention',
        'examinateur',
        'commentaires',
        'valide',
    ];

    protected function casts(): array
    {
        return [
            'date_obtention' => 'date',
            'valide' => 'boolean',
        ];
    }

    // Relations
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ceinture(): BelongsTo
    {
        return $this->belongsTo(Ceinture::class);
    }

    // Scopes
    public function scopeValides($query)
    {
        return $query->where('valide', true);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
