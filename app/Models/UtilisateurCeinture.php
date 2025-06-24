<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UtilisateurCeinture extends Model
{
    use HasFactory;

    protected $table = 'membre_ceintures';

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

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec la ceinture
     */
    public function ceinture()
    {
        return $this->belongsTo(Ceinture::class);
    }

    /**
     * Relation avec l'examinateur (utilisateur)
     */
    public function examinateurUser()
    {
        return $this->belongsTo(User::class, 'examinateur');
    }
}
