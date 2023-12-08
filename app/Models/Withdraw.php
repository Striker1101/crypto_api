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
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
