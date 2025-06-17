<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembreCeinture extends Model
{
    use HasFactory;

    protected $table = 'membre_ceintures';

    protected $fillable = [
        'membre_id',
        'ceinture_id',
        'date_obtention',
        'evaluateur',
        'notes',
    ];

    protected $casts = [
        'date_obtention' => 'date',
    ];

    public function membre()
    {
        return $this->belongsTo(Membre::class);
    }

    public function ceinture()
    {
        return $this->belongsTo(Ceinture::class);
    }
}
