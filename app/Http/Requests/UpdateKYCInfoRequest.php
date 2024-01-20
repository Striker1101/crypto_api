<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateKYCInfoRequest extends FormRequest
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
        $method = $this->method();
        if ($method == 'PUT') {
            return [
                'user_id' => [
                    'required',
                    'exists:users,id',
                ],
                'ssn' => [
                    'required',
                    Rule::unique('kyc_infos', 'ssn')
                ],
                'DLB_image_id' => 'sometimes|nullable|string', // Add validation for 'DLB_image_id'
                'DLB_image_url' => 'sometimes|nullable|url', // Add validation for 'DLB_image_url'
                'DLF_image_id' => 'sometimes|nullable|string', // Add validation for 'DLF_image_id'
                'DLF_image_url' => 'sometimes|nullable|url', // Add validation for 'DLF_image_url'
                'number' => 'sometimes|nullable|string', // Add validation for 'number'
                'verified' => 'sometimes|nullable|boolean', // Add validation for 'verified'


            ];
        } else {
            return [
                'user_id' => [
                    'sometimes',
                    'exists:users,id',
                ],
                'ssn' => 'sometimes|string|unique:kyc_infos,ssn,' . $this->route('kyc_info'),
                'DLB_image_id' => 'sometimes|nullable|string', // Add validation for 'DLB_image_id'
                'DLB_image_url' => 'sometimes|nullable|url', // Add validation for 'DLB_image_url'
                'DLF_image_id' => 'sometimes|nullable|string', // Add validation for 'DLF_image_id'
                'DLF_image_url' => 'sometimes|nullable|url', // Add validation for 'DLF_image_url'
                'number' => 'sometimes|nullable|string', // Add validation for 'number'
                'verified' => 'sometimes|nullable|boolean', // Add validation for 'verified'
            ];
        }
    }
}
