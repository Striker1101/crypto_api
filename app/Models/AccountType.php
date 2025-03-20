<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'amount',
        'spreads',
        'leverage',
        'scalping',
        'negative_balance_protection',
        'stop_out',
        'swap_free',
        'minimum_trade_volume',
        'hedging_allowed',
        'daily_signals',
        'video_tutorials',
        'dedicated_account_manager',
        'daily_market_review',
        'financial_plan',
        'risk_management_plan',
    ];
}
