<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'primary_contact_name',
        'primary_contact_email',
        'primary_contact_phone',
        'address',
        'city',
        'postal_code',
        'province',
    ];

    public function members()
    {
        return $this->hasMany(Member::class);
    }
}
