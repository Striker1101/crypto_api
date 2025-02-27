<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithdrawType extends Model
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
    ];
}
