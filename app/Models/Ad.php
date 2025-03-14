<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    protected $fillable =
    [
        'image',
        'video',
        'district',
        'time',
        'date',
        'is_active'
    ];
}
