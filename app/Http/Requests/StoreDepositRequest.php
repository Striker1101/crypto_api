<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDepositRequest extends FormRequest
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
            'amount' => 'required|numeric|min:0.01', // Example validation for deposit amount
            'currency' => 'required|string|max:255',
            'status' => 'in:pending,completed', // Validate that status is one of 'pending' or 'completed'
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            // Add more validation rules as needed
        ];
    }
}
