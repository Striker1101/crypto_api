<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDepositTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'image' => 'nullable|string',
            'symbol' => 'nullable|string|max:10',
            'currency' => 'nullable|string|max:10',
            'type' => 'nullable|string|max:50',
            'min_limit' => 'nullable|numeric|min:0',
            'max_limit' => 'nullable|numeric|min:0',
            'owner_referral_id' => 'nullable|uuid',
            "details" => 'nullable'
        ];
    }
}
