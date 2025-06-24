<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presence extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'cours_id',
        'date_cours',
        'present',
        'notes',
    ];

    protected $casts = [
        'date_cours' => 'date',
        'present' => 'boolean',
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec le cours
     */
    public function cours()
    {
        return $this->belongsTo(Cours::class);
    }
}
