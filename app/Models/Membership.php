<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    protected $fillable = [
        'membership_id',
        'title',
        'amount',
        'days',
        'benefit',
        'is_active',
        'remark',
    ];
}
