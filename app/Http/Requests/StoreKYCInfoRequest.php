<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreKYCInfoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => [
                'required',
                'exists:users,id',
                Rule::unique('kyc_infos', 'user_id'),
            ],
            'ssn' => 'required|string|unique:kyc_infos,ssn,' . $this->route('kyc_info'),
            'DLF_image_url' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'DLB_image_url' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'number' => 'nullable|string',
            'verified' => 'nullable|boolean',
        ];
    }
}
