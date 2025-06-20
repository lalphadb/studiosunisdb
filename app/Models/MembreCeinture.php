<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MembreCeinture extends Model
{
    use HasFactory;

    protected $fillable = [
        'membre_id',
        'ceinture_id',
        'date_obtention',
        'examinateur',
        'commentaires',
        'valide'
    ];

    protected $casts = [
        'date_obtention' => 'date',
        'valide' => 'boolean',
    ];

    /**
     * Membre qui a obtenu la ceinture
     */
    public function membre(): BelongsTo
    {
        return $this->belongsTo(Membre::class);
    }

    /**
     * Ceinture obtenue
     */
    public function ceinture(): BelongsTo
    {
        return $this->belongsTo(Ceinture::class);
    }
}
