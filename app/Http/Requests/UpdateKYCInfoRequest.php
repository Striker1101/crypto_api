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
                    Rule::unique('kyc_infos', 'user_id'), // Ensure a user has only one kyc info
                ],
                'ssn' => [
                    'required',
                    Rule::unique('kyc_infos', 'ssn')
                ]

            ];
        } else {
            return [
                'user_id' => [
                    'sometimes',
                    'exists:users,id',
                    Rule::unique('kyc_infos', 'user_id'), // Ensure a user has only one kyc info
                ],
                'ssn' => 'sometimes|string|unique:kyc_infos,ssn,' . $this->route('kyc_info'),
            ];
        }
    }
}
