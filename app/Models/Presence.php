<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Presence extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', // Changé de membre_id à user_id
        'cours_id',
        'date_cours',
        'present',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'date_cours' => 'date',
            'present' => 'boolean',
        ];
    }

    // Relations
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cours(): BelongsTo
    {
        return $this->belongsTo(Cours::class);
    }

    // Scopes
    public function scopePresents($query)
    {
        return $query->where('present', true);
    }

    public function scopeAbsents($query)
    {
        return $query->where('present', false);
    }
}
