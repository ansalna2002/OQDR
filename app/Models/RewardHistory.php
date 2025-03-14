<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RewardHistory extends Model
{
    protected $fillable = [
        'user_id',
        'watch_time',
        'points_earned',
        'points_earn_date'
    ];
    
}
