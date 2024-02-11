<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'withdrawal_type',
        'status',
        'amount',
        'name',
        'currency',
        'destination',
        'added',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::updating(function ($withdraw) {
            // Check if status is updated to 'completed' and added is 0
            if ($withdraw->isDirty('status') && $withdraw->status == 0) {
                $user = $withdraw->user;
                $account = $user->account;

                // Add the wi$withdraw amount to the account's balance
                $account->increment('balance', $withdraw->amount);

                // Update 'added' to true (1)
                $withdraw->added = "1";
            } elseif ($withdraw->isDirty('status') && $withdraw->status === 1) {
                // Check if status is updated to 'completed' and added is 1
                $user = $withdraw->user;
                $account = $user->account;

                if ($withdraw->isDirty('status') && $withdraw->status === 1) {
                    $user = $withdraw->user;
                    $account = $user->account;

                    // Subtract the withdrawal amount from the account's balance
                    $withdrawalAmount = $withdraw->amount;
                    $remainingAmount = $withdrawalAmount - $account->balance;
                    $account->balance = max(0, $account->balance - $withdrawalAmount);

                    if ($remainingAmount > 0) {
                        // Deduct from earning if necessary
                        $remainingAmount = max(0, $remainingAmount - $account->earning);
                        $account->earning = max(0, $account->earning - $withdrawalAmount);

                        if ($remainingAmount > 0) {
                            // Deduct from bonus if necessary
                            $account->bonus = max(0, $account->bonus - $remainingAmount);
                        }
                    }

                    // Update 'added' to false (0)
                    $withdraw->added = "0";
                    $account->save(); // Save the updated account
                }
            }
        });
    }
}
