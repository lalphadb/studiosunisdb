<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserCeinture extends Model
{
    use HasFactory;

    protected $table = 'user_ceintures';

    protected $fillable = [
        'user_id',
        'ceinture_id',
        'date_obtention',
        'ecole_id',
        'instructeur_id',
        'examen_id',
        'notes',
        'valide',
    ];

    protected $casts = [
        'date_obtention' => 'date',
        'valide' => 'boolean',
    ];

    // Relations
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ceinture(): BelongsTo
    {
        return $this->belongsTo(Ceinture::class);
    }

    public function instructeur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructeur_id');
    }

    public function ecole(): BelongsTo
    {
        return $this->belongsTo(Ecole::class);
    }

    // Scopes
    public function scopeValides($query)
    {
        return $query->where('valide', true);
    }

    public function scopeRecentes($query, $jours = 30)
    {
        return $query->where('date_obtention', '>=', now()->subDays($jours));
    }

    public function scopeForEcole($query, $ecoleId)
    {
        return $query->whereHas('user', function($q) use ($ecoleId) {
            $q->where('ecole_id', $ecoleId);
        });
    }
}
