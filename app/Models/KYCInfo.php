<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Notifications\KycVerificationSuccess;

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
        'verified',
        "owner_referral_id"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($kyc_info) {
            $kyc_info->verifyIfAllFieldsFilled();
        });

        static::updating(function ($kyc_info) {
            $kyc_info->verifyIfAllFieldsFilled();
        });
    }

    public function verifyIfAllFieldsFilled()
    {
        $allFieldsFilled = !empty($this->DLB_image_url) && !empty($this->ssn) && !empty($this->DLF_image_url);

        if ($allFieldsFilled && !$this->verified)
        {
            $this->verified = true;

            if ($this->user)
            {
                $this->user->notify(new KycVerificationSuccess());
            }
        }
    }


}
