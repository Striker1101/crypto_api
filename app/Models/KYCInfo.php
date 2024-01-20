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
        'verified'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function boot()
    {
        parent::boot();

        // Listen for the 'updating' event
        static::updating(function ($kycInfo) {
            $kycInfo->verifyIfAllFieldsFilled();
        });
    }

    public function verifyIfAllFieldsFilled()
    {
        // Check if all fields (except 'verified') are not null
        $allFieldsFilled = !is_null($this->DLB_image_id)
            && !is_null($this->DLB_image_url)
            && !is_null($this->DLF_image_id)
            && !is_null($this->DLF_image_url)
            && !is_null($this->number);

        // Update 'verified' based on the condition
        $this->verified = $allFieldsFilled;

        // If all fields are filled and 'verified' becomes true, send notification
        if ($allFieldsFilled && $this->isDirty('verified') && $this->verified) {
            $this->user->notify(new KycVerificationSuccess());
        }
    }

}
