<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Belt extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'color_hex',
        'order',
        'description',
        'prerequis_techniques',
        'duree_minimum_mois',
        'presences_minimum',
        'actif',
    ];

    protected $casts = [
        'prerequis_techniques' => 'array',
        'actif' => 'boolean',
    ];

    public function members()
    {
        return $this->hasMany(Member::class, 'current_belt_id');
    }
}
