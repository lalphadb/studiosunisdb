<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FamilyLink extends Model
{
    protected $fillable = ['member_id','related_member_id','type'];

    public function member(): BelongsTo { return $this->belongsTo(Member::class,'member_id'); }
    public function related(): BelongsTo { return $this->belongsTo(Member::class,'related_member_id'); }
}
