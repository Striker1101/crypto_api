<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investment extends Model
{
    use HasFactory;

    protected $table = 'investments'; // Name of the table in the database

    protected $fillable = ['amount']; // Fillable attribute

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
