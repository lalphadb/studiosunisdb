<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'ecole_id','nom','saison','niveau','age_min','age_max','capacite','tarification'
    ];
    protected $casts = ['tarification'=>'array'];

    public function ecole(): BelongsTo { return $this->belongsTo(School::class, 'ecole_id'); }
    public function sessions(): HasMany { return $this->hasMany(CourseSession::class); }
    public function schedules(): HasMany { return $this->hasMany(CourseSchedule::class); }
    public function registrations(): HasMany { return $this->hasMany(CourseRegistration::class); }
}
