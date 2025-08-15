<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseSchedule extends Model
{
    protected $fillable = ['course_id','weekday','start_time','end_time','instructeur_ids'];
    protected $casts = ['instructeur_ids'=>'array'];

    public function course(): BelongsTo { return $this->belongsTo(Course::class); }
}
