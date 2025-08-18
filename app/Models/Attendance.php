<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'member_id',
        'instructor_id',
        'date',
        'status',
        'heure_arrivee',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'heure_arrivee' => 'datetime:H:i',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }
}
