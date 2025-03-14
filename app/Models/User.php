<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'user_id',
        'referral_id',
        'email',
        'password',
        'phone_number',
        'profile_image',
        'district',
        'role',
        'is_active',
        'membership',
        'subscribed_at',
        'is_subscribed',
        'subscription_end_date',
        'referral_name',
        'status',
        'otp',
        'otp_expired_at',
        'otp_verified',
        'reward_points'

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
    public function membership()
    {
        return $this->belongsTo(Membership::class, 'membership_id');
    }
    public function folders()
    {
        return $this->hasMany(Folder::class, 'user_id');
    }

    public function files()
    {
        return $this->hasMany(Folder::class, 'user_id', 'user_id')->where('type', 'file');
    }

    public function subscriptions()
    {
        return $this->hasMany(UserSubscription::class, 'user_id');
    }
    public function supports()
    {
        return $this->hasMany(Support::class, 'user_id');
    }

}
