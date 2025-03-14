<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSubscription extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'membership_id',
        'transaction_id',
        'payment_image',
        'title',
        'amount',
        'validity',
        'remark',
        'is_active',
        'status',
        'admin_approved_date',
        'expiry_date'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
