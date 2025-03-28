<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepositType extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'image',
        'symbol',
        'currency',
        'type',
        'min_limit',
        'max_limit',
        "owner_referral_id",
        "details"
    ];

    protected $casts = [
        'details' => 'array', // or 'json'
    ];
}
