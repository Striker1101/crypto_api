<?php

namespace App\Policies;

use App\Models\KYCInfo;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class KYCInfoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, KYCInfo $kycInfo)
    {
        // Logic to determine if the user can view the KYCInfo
        return $user->id === $kycInfo->user_id; // Example: Only the owner can view
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, KYCInfo $kycInfo): bool
    {
        //
        return $user->id === $kycInfo->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, KYCInfo $kycInfo): bool
    {
        //
        return $user->id === $kycInfo->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, KYCInfo $kYCInfo): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, KYCInfo $kYCInfo): bool
    {
        //
    }
}
