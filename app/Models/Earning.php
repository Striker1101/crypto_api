<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Earning extends Model
{
    use HasFactory;
    protected $table = 'earnings'; // Name of the table in the database

    protected $fillable = ['user_id', 'amount', 'balance']; // Fillable attributes
}
