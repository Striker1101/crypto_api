<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KYCInfo extends Model
{
    use HasFactory;

    protected $table = "kyc_infos";

    protected $fillable = [
        'ssn',
        'user_id',
        'DLB_image_id',
        'DLB_image_url',
        'DLF_image_id',
        'DLF_image_url',
        'number',
        'verified'

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
