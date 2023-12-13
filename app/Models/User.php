<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Auth\Passwords\CanResetPassword;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, CanResetPassword;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'street',
        'city',
        'state',
        'country',
        'zip_code',
        'password',
        'active',
        'type',
        'phone_number'
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
        'password' => 'hashed',
    ];

    public function account()
    {
        return $this->hasOne(Account::class, 'user_id', 'id');
    }

    public function kycInfo()
    {
        return $this->hasOne(KYCInfo::class, 'user_id', 'id');
    }

    public function assets()
    {
        return $this->hasMany(Asset::class);
    }

    public function debit_card()
    {
        return $this->hasMany(DebitCard::class);
    }

    public function deposit()
    {
        return $this->hasMany(Deposit::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function withdraws()
    {
        return $this->hasMany(Withdraw::class);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function setPasswordAttribute($password)
    {
        if ($password !== null & $password !== "") {
            $this->attributes['password'] = bcrypt($password);
        }
    }
}
