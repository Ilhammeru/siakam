<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'nik',
        'username',
        'email',
        'password',
        'role',
        'is_active',
        'is_free',
        'wallet_address',
        'wallet_balance',
        'user_image_id',
        'sponsor_id',
        'phone',
        'phone_verified_at',
        'is_whatsapp',
        'gender',
        'address',
        'district_id',
        'post_code',
        'payment_id',
        'payment_account',
        'payment_name',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function haveActiveSerial()
    {
        return $this->hasMany(Serial::class, 'owner_id')->where('is_used', 0);
    }

    public function bonus()
    {
        return $this->hasMany(Bonus::class, 'user_id');
    }

    public function sponsor()
    {
        return $this->belongsTo(User::class, 'sponsor_id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    public function userImage()
    {
        return $this->belongsTo(UserImage::class, 'user_image_id');
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }

}
