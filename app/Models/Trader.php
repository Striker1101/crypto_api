<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trader extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'profile_picture',
        'rating',
        'ROI',
        'PnL',
        'investment',
        'ranking'
    ];


    public function user()
    {
        return $this->hasOne(User::class, 'trader_id', 'id');
    }

}
