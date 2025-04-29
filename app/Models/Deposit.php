<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'currency',
        'status',
        'added',
        'image_url',
        "owner_referral_id",
        "deposit_type_id"
    ];

    protected $casts = [
        'details' => 'array',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function deposit()
    {
        return $this->hasMany(Deposit::class); // or belongsToMany depending on your schema
    }
    // In the Deposit model:
    public function deposit_type()
    {
        return $this->belongsTo(DepositType::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::updating(function ($deposit) {
            // Check if status is updated to 'completed' and added is 0
            if ($deposit->isDirty('status') && $deposit->status === 'completed' && $deposit->added == 0)
            {
                $user = $deposit->user;
                $account = $user->account;

                // Add the deposit amount to the account's balance
                $account->increment('balance', $deposit->amount);

                // Update 'added' to true (1)
                $deposit->added = "1";
            } elseif ($deposit->isDirty('status') && $deposit->status === 'pending' && $deposit->added == 1)
            {
                // Check if status is updated to 'completed' and added is 1
                $user = $deposit->user;
                $account = $user->account;

                // Subtract the deposit amount from the account's balance
                $account->decrement('balance', $deposit->amount);

                // Update 'added' to false (0)
                $deposit->added = "0";
            }
        });
    }

}
