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
        'examinateur',
        'commentaires',
        'valide',
    ];

    protected $casts = [
        'date_obtention' => 'date',
        'valide' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ceinture(): BelongsTo
    {
        return $this->belongsTo(Ceinture::class);
    }
}
