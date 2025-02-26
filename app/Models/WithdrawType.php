<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithdrawType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'account_name',
        'wallet_address',
        'account_number',
        'bank_name',
        'routing_number',
        'code',
        'type',
        'min_limit',
        'max_limit',
    ];
}
