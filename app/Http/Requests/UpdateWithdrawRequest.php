<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWithdrawRequest extends FormRequest
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
        if ($method == 'PUT')
        {
            return [
                //
                'user_id' => 'required|exists:users,id',
                'withdrawal_type_id' => 'required|exists:withdraw_types,id',
                'amount' => 'required|numeric|min:0.01',
                'name' => 'nullable|string',
            ];
        } else
        {
            return [
                //
                'user_id' => 'sometimes|exists:users,id',
                'withdrawal_type_id' => 'required|exists:withdraw_types,id',
                'amount' => 'sometimes|numeric|min:0.01',
                'name' => 'nullable|string',
            ];
        }
    }
}
