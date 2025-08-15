<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseRegistration extends Model
{
    protected $fillable = ['course_id','member_id','type_paiement','tarif_personnalise','statut'];
    protected $casts = ['tarif_personnalise'=>'array'];

    public function course(): BelongsTo { return $this->belongsTo(Course::class); }
    public function member(): BelongsTo { return $this->belongsTo(Member::class); }
}
