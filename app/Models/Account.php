<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'balance',
        'earning',
        'bonus',
        'account_type',
        'account_stage',
        'trade',
        "owner_referral_id"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function accountType()
    {
        return $this->belongsTo(AccountType::class, 'account_type_id');
    }

    //adding evernt to update trading.date on trade equal true
    protected static function boot()
    {
        //test if event fired
        // static::updating(function ($account) {
        //     dd('updating event fired');
        // });

        parent::boot();

        static::updating(function ($account) {
            // Check if the 'trade' attribute is being updated to true
            if ($account->isDirty('trade') && $account->trade == "1")
            {
                // Log the date of the change
                $account->trade_changed_at = now();
            }
        });
    }
}
