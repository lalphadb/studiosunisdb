<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrivacyConsent extends Model
{
    protected $fillable = ['user_id','member_id','version','given_at','ip','texte_snapshot'];
    protected $casts = ['given_at'=>'datetime'];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function member(): BelongsTo { return $this->belongsTo(Member::class); }
}
