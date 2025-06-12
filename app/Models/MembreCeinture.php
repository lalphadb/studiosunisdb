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
        'date_examen',
        'examinateur_id',
        'notes_examen',
        'score_technique',
        'score_kata',
        'score_combat', 
        'score_global',
        'statut',
        'certificat_url'
    ];

    protected $casts = [
        'date_obtention' => 'date',
        'date_examen' => 'date',
        'score_technique' => 'decimal:2',
        'score_kata' => 'decimal:2', 
        'score_combat' => 'decimal:2',
        'score_global' => 'decimal:2'
    ];

    public function membre()
    {
        return $this->belongsTo(Membre::class);
    }

    public function ceinture()
    {
        return $this->belongsTo(Ceinture::class);
    }

    public function examinateur()
    {
        return $this->belongsTo(User::class, 'examinateur_id');
    }
}
