<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeltProgression extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'from_belt_id',
        'to_belt_id',
        'date',
        'instructor_id',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function fromBelt()
    {
        return $this->belongsTo(Belt::class, 'from_belt_id');
    }

    public function toBelt()
    {
        return $this->belongsTo(Belt::class, 'to_belt_id');
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }
}
