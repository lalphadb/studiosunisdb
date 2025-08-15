<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class School extends Model
{
    protected $fillable = ['nom','slug','courriel','telephone','adresse'];

    public function users(): HasMany { return $this->hasMany(User::class, 'ecole_id'); }
    public function members(): HasMany { return $this->hasMany(Member::class, 'ecole_id'); }
    public function courses(): HasMany { return $this->hasMany(Course::class, 'ecole_id'); }
}
