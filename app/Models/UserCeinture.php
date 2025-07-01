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
        'date_attribution',
        'attribue_par',
        'date_obtention',
        'examinateur',
        'commentaires',
        'certifie',
        'valide',
        'instructeur_id',
        'examen_id',
        'ecole_id',
        'notes',
    ];

    protected $casts = [
        'date_obtention' => 'date',
        'date_attribution' => 'date',
        'valide' => 'boolean',
        'certifie' => 'boolean',
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
        return $query->where('ecole_id', $ecoleId);
    }
}
