<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ecole extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'adresse',
        'ville',
        'telephone',
        'email',
        'description',
    ];

    public function membres()
    {
        return $this->hasMany(Membre::class);
    }

    public function cours()
    {
        return $this->hasMany(Cours::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
