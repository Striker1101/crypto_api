<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'address', 'balance', 'currency', 'user_id', 'symbol', 'image'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
