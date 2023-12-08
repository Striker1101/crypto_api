<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWithdrawRequest extends FormRequest
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
            //
            'user_id' => 'required|exists:users,id',
            'withdrawal_type' => 'required|in:crypto,bank_transfer',
            'amount' => 'required|numeric|min:0.01',
            'name' => 'nullable|string',
            'currency' => 'required|string',
            'destination' => 'required|string',
        ];
    }
}
