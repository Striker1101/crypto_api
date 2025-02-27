<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'withdrawal_type_id',
        'status',
        'amount',
        'name',
        'destination',
        "routing_number",
        "code",
        'added',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function withdrawType()
    {
        return $this->belongsTo(WithdrawType::class, 'withdrawal_type_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::updating(function ($withdraw) {
            // Check if status is updated to 'completed' and added is 0
            if ($withdraw->isDirty('status') && $withdraw->status == 0 && $withdraw->added == 0)
            {
                $user = $withdraw->user;
                $account = $user->account;

                // Add the wi$withdraw amount to the account's balance
                $account->increment('balance', $withdraw->amount);

                // Update 'added' to true (1)
                $withdraw->added = "1";
            } elseif ($withdraw->isDirty('status') && $withdraw->status === 1 && $withdraw->added == 1)
            {
                // Check if status is updated to 'completed' and added is 1
                $user = $withdraw->user;
                $account = $user->account;

                // Subtract the wi$withdraw amount from the account's balance
                $account->decrement('balance', $withdraw->amount);

                // Update 'added' to false (0)
                $withdraw->added = "0";
            }
        });
    }

}
