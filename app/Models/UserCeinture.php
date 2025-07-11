<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasEcoleScope;

class UserCeinture extends Model
{
    use HasFactory, HasEcoleScope;

    protected $fillable = [
        'user_id',
        'ceinture_id',
        'ecole_id',
        'date_obtention',
        'numero_certificat',
        'evaluateur_id',
        'notes',
    ];

    protected $casts = [
        'date_obtention' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ceinture()
    {
        return $this->belongsTo(Ceinture::class);
    }

    public function ecole()
    {
        return $this->belongsTo(Ecole::class);
    }

    public function evaluateur()
    {
        return $this->belongsTo(User::class, 'evaluateur_id');
    }
}
