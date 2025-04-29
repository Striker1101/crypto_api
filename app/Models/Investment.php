<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investment extends Model
{
    use HasFactory;

    protected $table = 'investments'; // Name of the table in the database

    protected $fillable = [
        'user_id',
        'amount',
        'plan',
        'duration',
        "owner_referral_id"
    ]; // Fillable attribute
}
