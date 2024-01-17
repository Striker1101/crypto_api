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
        if ($method == 'PUT') {
            return [
                //
                'user_id' => 'required|exists:users,id',
                'withdrawal_type' => 'required|in:crypto,bank_transfer',
                'amount' => 'required|numeric|min:0.01',
                'name' => 'nullable|string',
                'currency' => 'required|string',
                'destination' => 'required|string',
            ];
        } else {
            return [
                //
                'user_id' => 'sometimes|exists:users,id',
                'withdrawal_type' => 'sometimes|in:crypto,bank_transfer',
                'amount' => 'sometimes|numeric|min:0.01',
                'name' => 'nullable|string',
                'currency' => 'sometimes|string',
                'destination' => 'sometimes|string',
            ];
        }
    }
}
