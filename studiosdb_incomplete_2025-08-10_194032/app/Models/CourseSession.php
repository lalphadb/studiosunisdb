<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseSession extends Model
{
    protected $fillable = ['course_id','statut','debut','fin','meta'];
    protected $casts = ['debut'=>'date','fin'=>'date','meta'=>'array'];

    public function course(): BelongsTo { return $this->belongsTo(Course::class); }
}
