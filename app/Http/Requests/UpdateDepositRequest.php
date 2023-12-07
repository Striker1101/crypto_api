<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDepositRequest extends FormRequest
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
                'user_id' => 'required|exists:users,id',
                'wallet_address' => 'required', // Unique wallet address for each deposit
                'amount' => 'required|numeric|min:0.01', // Example validation for deposit amount
                'currency' => 'required|string|max:255',
                'status' => 'in:pending,completed', // Validate that status is one of 'pending' or 'completed'
            ];
        } else {
            return [
                'user_id' => 'sometimes|exists:users,id',
                'wallet_address' => 'sometimes', // Unique wallet address for each deposit
                'amount' => 'sometimes|numeric|min:0.01', // Example validation for deposit amount
                'currency' => 'sometimes|string|max:255',
                'status' => 'in:pending,completed', // Validate that status is one of 'pending' or 'completed'
            ];
        }
    }
}
